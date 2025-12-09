
#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
salve_auto_accounting.py
Usage:
  python salve_auto_accounting.py /path/to/db-salve.sql [--from=YYYY-MM-DD] [--to=YYYY-MM-DD]

Reads a PostgreSQL dump produced by pg_dump (with COPY ... FROM stdin; blocks),
builds accounting summaries (Income Statement, Balance Sheet, Trial Balance),
and writes CSV outputs to ./salve_reports .
"""

import re
import sys
import csv
import argparse
from pathlib import Path
from decimal import Decimal
from datetime import datetime
import pandas as pd

def to_utc_naive_series(series):
    # Konversi ke timezone-aware UTC, lalu drop tz → naive
    return pd.to_datetime(series, errors="coerce", utc=True).dt.tz_localize(None)

def to_utc_naive_ts(ts_str_or_none):
    # Untuk argumen CLI --from/--to
    if ts_str_or_none is None:
        return None
    return pd.to_datetime(ts_str_or_none, utc=True).tz_localize(None)

def parse_args():
    ap = argparse.ArgumentParser()
    ap.add_argument("sql_path", type=str, help="Path to PostgreSQL dump (.sql)")
    ap.add_argument("--from", dest="date_from", type=str, default=None, help="Filter from date (YYYY-MM-DD)")
    ap.add_argument("--to", dest="date_to", type=str, default=None, help="Filter to date (YYYY-MM-DD)")
    ap.add_argument("--csv-sep", dest="csv_sep", type=str, default=",",
                help="CSV separator (default ','). Gunakan ';' untuk Excel Indonesia.")
    ap.add_argument("--xlsx-only", action="store_true",
                help="Hanya buat satu file Excel (tanpa membuat file CSV).")
    ap.add_argument("--branch-id", dest="branch_id", type=str, default=None,
                help="Filter per cabang (branch_id UUID).")
    ap.add_argument("--recognize", dest="recognize", type=str,
                default="COMPLETED,PICKED_UP",
                help="Comma-list status yang diakui sebagai pendapatan (default: COMPLETED,PICKED_UP).")
    return ap.parse_args()

def parse_postgres_copy(sql_text: str):
    tables = {}
    lines = sql_text.splitlines()
    i = 0
    copy_re = re.compile(r'^COPY\s+"?public"?\."?([a-zA-Z0-9_]+)"?\s*\((.*?)\)\s+FROM\s+stdin;?$')
    while i < len(lines):
        m = copy_re.match(lines[i])
        if m:
            table = m.group(1)
            columns = [c.strip().strip('"') for c in m.group(2).split(",")]
            i += 1
            rows = []
            while i < len(lines) and lines[i] != r'\.':
                rows.append(lines[i])
                i += 1
            tables[table] = (columns, rows)
        i += 1
    return tables

def rows_to_dataframe(columns, rows):
    data = []
    for r in rows:
        fields = r.split("\t")
        if len(fields) < len(columns):
            fields += [""] * (len(columns) - len(fields))
        rec = {}
        for c, v in zip(columns, fields):
            if v == r'\N':
                rec[c] = None
            else:
                rec[c] = v
        data.append(rec)
    return pd.DataFrame(data, columns=columns)

def to_decimal(s):
    if s is None or s == "":
        return Decimal("0")
    try:
        return Decimal(str(s))
    except Exception:
        return Decimal("0")

def main():
    args = parse_args()
    sql_path = Path(args.sql_path)
    if not sql_path.exists():
        print(f"File not found: {sql_path}")
        sys.exit(1)

    sql_text = sql_path.read_text(encoding="utf-8", errors="ignore")
    tables = parse_postgres_copy(sql_text)

    def get_df(name):
        if name in tables:
            cols, rows = tables[name]
            return rows_to_dataframe(cols, rows)
        return pd.DataFrame()

    orders = get_df("orders")
    order_vouchers = get_df("order_vouchers")
    payments = get_df("payments")
    expenses = get_df("expenses")
    receivables = get_df("receivables")
    order_items = get_df("order_items")
    services    = get_df("services")
    service_categories = get_df("service_categories")
    if service_categories.empty:
        # fallback bila nama tabel kategori berbeda
        service_categories = get_df("categories")

    if orders.empty:
        print("orders table missing/empty in SQL dump; cannot compute reports.")
        sys.exit(2)

    # Cast types
    num_cols = ["subtotal","discount","grand_total","paid_amount","due_amount","dp_amount","loyalty_discount"]
    for col in num_cols:
        if col in orders.columns:
            orders[col] = orders[col].map(to_decimal)

    for col in ["created_at","paid_at","received_at","ready_at"]:
        if col in orders.columns:
            orders[col] = to_utc_naive_series(orders[col])

    if "status" in orders.columns:
        orders["status"] = orders["status"].astype(str).str.upper()

    if not order_vouchers.empty and "applied_amount" in order_vouchers.columns:
        order_vouchers["applied_amount"] = order_vouchers["applied_amount"].map(to_decimal)

    if not payments.empty:
        payments["amount"] = payments["amount"].map(to_decimal)
        if "paid_at" in payments.columns:
            payments["paid_at"] = to_utc_naive_series(payments["paid_at"])
        if "created_at" in payments.columns:
            payments["created_at"] = to_utc_naive_series(payments["created_at"])
        if "method" in payments.columns:
            payments["method"] = payments["method"].astype(str).str.upper()

    if not expenses.empty and "amount" in expenses.columns:
        expenses["amount"] = expenses["amount"].map(to_decimal)
        if "created_at" in expenses.columns:
            expenses["created_at"] = to_utc_naive_series(expenses["created_at"])

    if not receivables.empty:
        receivables["remaining_amount"] = receivables["remaining_amount"].map(to_decimal)
        for col in ["created_at","updated_at","due_date"]:
            if col in receivables.columns:
                receivables[col] = to_utc_naive_series(receivables[col])
        if "status" in receivables.columns:
            receivables["status"] = receivables["status"].astype(str).str.upper()

    # ---- Branch filter (jika diminta) ----
    if args.branch_id:
        # orders
        if "branch_id" in orders.columns:
            orders = orders[orders["branch_id"] == args.branch_id]
        # payments (via order_id -> orders.branch_id, atau langsung branch_id)
        if not payments.empty:
            if "order_id" in payments.columns and "id" in orders.columns:
                _p = payments.merge(
                    orders[["id","branch_id"]].rename(columns={"id":"o_id"}),
                    left_on="order_id", right_on="o_id", how="left")
                payments = _p[_p["branch_id"] == args.branch_id].drop(columns=["o_id"])
            elif "branch_id" in payments.columns:
                payments = payments[payments["branch_id"] == args.branch_id]
        # receivables (via order_id atau langsung branch_id)
        if not receivables.empty:
            if "order_id" in receivables.columns and "id" in orders.columns:
                _r = receivables.merge(
                    orders[["id","branch_id"]].rename(columns={"id":"o_id"}),
                    left_on="order_id", right_on="o_id", how="left")
                receivables = _r[_r["branch_id"] == args.branch_id].drop(columns=["o_id"])
            elif "branch_id" in receivables.columns:
                receivables = receivables[receivables["branch_id"] == args.branch_id]
        # expenses
        if not expenses.empty and "branch_id" in expenses.columns:
            expenses = expenses[expenses["branch_id"] == args.branch_id]

    # Period filter (applies to orders.created_at, payments.paid_at, expenses.created_at)
    date_from = to_utc_naive_ts(args.date_from) if args.date_from else None
    date_to   = to_utc_naive_ts(args.date_to)   if args.date_to   else None

    if date_from is not None or date_to is not None:
        if "created_at" in orders.columns:
            mask = pd.Series(True, index=orders.index)
            if date_from is not None:
                mask &= orders["created_at"] >= date_from
            if date_to is not None:
                mask &= orders["created_at"] <= date_to
            orders = orders[mask]

        if not payments.empty and "paid_at" in payments.columns:
            mask = pd.Series(True, index=payments.index)
            if date_from is not None:
                mask &= payments["paid_at"] >= date_from
            if date_to is not None:
                mask &= payments["paid_at"] <= date_to
            payments = payments[mask]

        if not expenses.empty and "created_at" in expenses.columns:
            mask = pd.Series(True, index=expenses.index)
            if date_from is not None:
                mask &= expenses["created_at"] >= date_from
            if date_to is not None:
                mask &= expenses["created_at"] <= date_to
            expenses = expenses[mask]

    # Accounting logic
    completed_statuses = {s.strip().upper() for s in args.recognize.split(",") if s.strip()}
    orders_recognized = orders[orders["status"].isin(completed_statuses)].copy()
    orders_unearned = orders[~orders["status"].isin(completed_statuses)].copy()

    voucher_by_order = order_vouchers.groupby("order_id")["applied_amount"].sum() if not order_vouchers.empty else pd.Series(dtype="object")
    voucher_by_order = voucher_by_order.reindex(orders_recognized["id"]).fillna(Decimal("0"))

    if len(voucher_by_order) == len(orders_recognized):
        orders_recognized = orders_recognized.assign(voucher_amount=voucher_by_order.values)
    else:
        orders_recognized = orders_recognized.assign(voucher_amount=Decimal("0"))

    net_revenue = orders_recognized["grand_total"].sum() if "grand_total" in orders_recognized.columns else Decimal("0")
    discount_total = orders_recognized["discount"].sum() if "discount" in orders_recognized.columns else Decimal("0")
    voucher_total = orders_recognized["voucher_amount"].sum() if "voucher_amount" in orders_recognized.columns else Decimal("0")
    loyalty_discount_total = orders_recognized["loyalty_discount"].sum() if "loyalty_discount" in orders_recognized.columns else Decimal("0")

    gross_revenue = (orders_recognized["subtotal"].sum() if "subtotal" in orders_recognized.columns else net_revenue + discount_total + voucher_total + loyalty_discount_total)
    contra_revenue = discount_total + voucher_total + loyalty_discount_total

    operating_expenses = expenses["amount"].sum() if not expenses.empty else Decimal("0")

    income_statement = pd.DataFrame([
        {"Item": "Gross Service Revenue", "Amount": gross_revenue},
        {"Item": "Less: Discounts", "Amount": -discount_total},
        {"Item": "Less: Vouchers (Applied)", "Amount": -voucher_total},
        {"Item": "Less: Loyalty Discount", "Amount": -loyalty_discount_total},
        {"Item": "Net Service Revenue", "Amount": net_revenue},
        {"Item": "Operating Expenses", "Amount": -operating_expenses},
        {"Item": "Net Income (Loss)", "Amount": net_revenue - operating_expenses},
    ])

    # Balance Sheet
    ar_open = receivables[receivables["status"].isin(["OPEN","PARTIAL"])]["remaining_amount"].sum() if not receivables.empty else Decimal("0")

    cash_total = Decimal("0")
    bank_total = Decimal("0")
    if not payments.empty:
        cash_total = payments.loc[payments["method"]=="CASH", "amount"].sum()
        bank_total = payments.loc[payments["method"].isin(["QRIS","TRANSFER"]), "amount"].sum()

    dp_outstanding = orders_unearned["dp_amount"].sum() if "dp_amount" in orders_unearned.columns else Decimal("0")

    assets = pd.DataFrame([
        {"Account": "Cash on Hand", "Amount": cash_total},
        {"Account": "Bank & e-Payment", "Amount": bank_total},
        {"Account": "Accounts Receivable", "Amount": ar_open},
    ])
    total_assets = assets["Amount"].sum()

    liabilities = pd.DataFrame([
        {"Account": "Unearned Revenue (DP Outstanding)", "Amount": dp_outstanding},
    ])
    total_liabilities = liabilities["Amount"].sum()

    retained_earnings = total_assets - total_liabilities
    equity = pd.DataFrame([
        {"Account": "Retained Earnings (Auto)", "Amount": retained_earnings},
    ])
    total_equity = equity["Amount"].sum()

    trial_rows = [
        {"Account": "Cash on Hand", "Debit": cash_total, "Credit": Decimal("0")},
        {"Account": "Bank & e-Payment", "Debit": bank_total, "Credit": Decimal("0")},
        {"Account": "Accounts Receivable", "Debit": ar_open, "Credit": Decimal("0")},
        {"Account": "Unearned Revenue (DP)", "Debit": Decimal("0"), "Credit": dp_outstanding},
        {"Account": "Service Revenue (Net)", "Debit": Decimal("0"), "Credit": net_revenue},
        {"Account": "Operating Expenses", "Debit": operating_expenses, "Credit": Decimal("0")},
        {"Account": "Retained Earnings (Auto)", "Debit": Decimal("0"), "Credit": retained_earnings},
    ]
    trial_balance = pd.DataFrame(trial_rows)

    # -------------------- Detail Analytics --------------------
    # a) Payments by Method (periode & cabang terfilter)
    payments_by_method = pd.DataFrame()
    if not payments.empty and "method" in payments.columns and "amount" in payments.columns:
        _pm = payments.copy()
        payments_by_method = _pm.groupby("method", as_index=False)["amount"].sum().rename(
            columns={"method":"Method","amount":"Total"})

    # b) Receivables Aging (bucket: 0-30, 31-60, 61-90, >90)
    receivables_aging = pd.DataFrame()
    receivables_aging_detail = pd.DataFrame()
    if not receivables.empty and "remaining_amount" in receivables.columns and "due_date" in receivables.columns:
        # Pakai as_of yang UTC-naive agar kompatibel dengan due_date yang sudah naive
        as_of = date_to if date_to is not None else pd.to_datetime("now", utc=True).tz_localize(None)
        _ra = receivables.copy()
        # (Aman) pastikan due_date juga UTC-naive
        if "due_date" in _ra.columns:
            _ra["due_date"] = to_utc_naive_series(_ra["due_date"])
        _ra["days_overdue"] = (_ra["due_date"] - as_of).dt.days * -1  # overdue positif
        def bucket(d):
            if pd.isna(d): return "No Due Date"
            d = int(d)
            if d <= 0: return "Not Yet Due"
            if d <= 30: return "0-30"
            if d <= 60: return "31-60"
            if d <= 90: return "61-90"
            return ">90"
        _ra["Aging"] = _ra["days_overdue"].map(bucket)
        receivables_aging = _ra.groupby("Aging", as_index=False)["remaining_amount"].sum().rename(
            columns={"remaining_amount":"Total"})
        receivables_aging_detail = _ra[["order_id","remaining_amount","due_date","days_overdue","status"]].sort_values(
            by=["days_overdue"], ascending=False)

    # c) Monthly Trend (Revenue recognized, Expenses, Payments) — aman saat salah satu kosong
    revenue_by_month = pd.DataFrame(columns=["Month","Revenue","Expenses","Payments"])

    # Revenue by month (recognized)
    if not orders_recognized.empty and "created_at" in orders_recognized.columns:
        _om = orders_recognized.copy()
        _om["Month"] = _om["created_at"].dt.to_period("M").dt.to_timestamp()
        _r = _om.groupby("Month", as_index=False)["grand_total"].sum().rename(columns={"grand_total":"Revenue"})
    else:
        _r = pd.DataFrame(columns=["Month","Revenue"])

    # Expenses by month
    if not expenses.empty and "created_at" in expenses.columns and "amount" in expenses.columns:
        _ex = expenses.copy()
        _ex["Month"] = _ex["created_at"].dt.to_period("M").dt.to_timestamp()
        _e = _ex.groupby("Month", as_index=False)["amount"].sum().rename(columns={"amount":"Expenses"})
    else:
        _e = pd.DataFrame(columns=["Month","Expenses"])

    # Payments by month
    if not payments.empty and "paid_at" in payments.columns and "amount" in payments.columns:
        _px = payments.copy()
        _px["Month"] = _px["paid_at"].dt.to_period("M").dt.to_timestamp()
        _p = _px.groupby("Month", as_index=False)["amount"].sum().rename(columns={"amount":"Payments"})
    else:
        _p = pd.DataFrame(columns=["Month","Payments"])

    # Outer-merge hanya pada frame yang memiliki 'Month'
    frames = [df for df in [_r, _e, _p] if not df.empty and "Month" in df.columns]
    if frames:
        tmp = frames[0]
        for f in frames[1:]:
            tmp = tmp.merge(f, on="Month", how="outer")
        for col in ["Revenue","Expenses","Payments"]:
            if col not in tmp.columns:
                tmp[col] = 0
        revenue_by_month = tmp.fillna(0).sort_values("Month")

    # d) Top Services & Categories (hanya untuk orders yang recognized)
    top_services = pd.DataFrame()
    top_categories = pd.DataFrame()
    if not order_items.empty and "order_id" in order_items.columns and "id" in orders_recognized.columns:
        _oi = order_items[order_items["order_id"].isin(orders_recognized["id"])]
        # qty & line_total kalkulasi fleksibel
        _oi = _oi.copy()
        qty_col = "qty" if "qty" in _oi.columns else ("quantity" if "quantity" in _oi.columns else None)
        if qty_col is None:
            _oi["__qty__"] = 0
            qty_col = "__qty__"
        price_col = None
        for cand in ["line_total","total","amount","price","unit_price"]:
            if cand in _oi.columns:
                price_col = cand
                break
        if price_col is None:
            _oi["__line_total__"] = 0
            price_col = "__line_total__"
        # bila cuma ada price & qty → hitung line_total
        if "line_total" not in _oi.columns and "price" in _oi.columns and qty_col in _oi.columns:
            try:
                _oi["line_total"] = pd.to_numeric(_oi["price"], errors="coerce").fillna(0) * pd.to_numeric(_oi[qty_col], errors="coerce").fillna(0)
                price_col = "line_total"
            except Exception:
                pass
        # join ke services & categories
        if "service_id" in _oi.columns and not services.empty and "id" in services.columns:
            _oi = _oi.merge(services[["id","name","category_id"]].rename(columns={"id":"service_id","name":"service_name"}), on="service_id", how="left")
            if not service_categories.empty and "id" in service_categories.columns:
                _oi = _oi.merge(service_categories[["id","name"]].rename(columns={"id":"category_id","name":"category_name"}), on="category_id", how="left")
        # agregasi
        _oi["__qty__num"] = pd.to_numeric(_oi[qty_col], errors="coerce").fillna(0)
        _oi["__amt__num"] = pd.to_numeric(_oi[price_col], errors="coerce").fillna(0)
        if "service_name" in _oi.columns:
            top_services = _oi.groupby("service_name", as_index=False).agg(
                Qty=("__qty__num","sum"), Amount=("__amt__num","sum")
            )
            top_services = top_services.sort_values(["Amount","Qty"], ascending=False).head(20)
        if "category_name" in _oi.columns:
            top_categories = _oi.groupby("category_name", as_index=False).agg(
                Qty=("__qty__num","sum"), Amount=("__amt__num","sum")
            ).sort_values(["Amount","Qty"], ascending=False)

    out_dir = Path("./salve_reports")
    out_dir.mkdir(parents=True, exist_ok=True)

    if not args.xlsx_only:
        # ---- CSV (tanpa desimal) ----
        sep = args.csv_sep
        def to_int_money(df, cols):
            df = df.copy()
            for c in cols:
                if c in df.columns:
                    df[c] = df[c].apply(
                        lambda v: int(Decimal(str(v)).quantize(Decimal("1"))) if v is not None else 0
                    )
            return df

        csv_income       = to_int_money(income_statement, ["Amount"])
        csv_assets       = to_int_money(assets, ["Amount"])
        csv_liabilities  = to_int_money(liabilities, ["Amount"])
        csv_equity       = to_int_money(equity, ["Amount"])
        csv_trial        = to_int_money(trial_balance, ["Debit", "Credit"])

        csv_income.to_csv(out_dir / "income_statement.csv", index=False, sep=sep)
        csv_assets.to_csv(out_dir / "balance_sheet_assets.csv", index=False, sep=sep)
        csv_liabilities.to_csv(out_dir / "balance_sheet_liabilities.csv", index=False, sep=sep)
        csv_equity.to_csv(out_dir / "balance_sheet_equity.csv", index=False, sep=sep)
        csv_trial.to_csv(out_dir / "trial_balance.csv", index=False, sep=sep)


    # === XLSX multi-sheet (rapi untuk dibaca di Excel) ===
    # Pastikan xlsxwriter terpasang: pip install xlsxwriter
    def to_int_money(df, cols):
        df = df.copy()
        for c in cols:
            if c in df.columns:
                df[c] = df[c].apply(
                    lambda v: int(Decimal(str(v)).quantize(Decimal("1"))) if v is not None else 0
                )
        return df

    x_income = to_int_money(income_statement, ["Amount"])
    x_assets = to_int_money(assets, ["Amount"])
    x_liabs  = to_int_money(liabilities, ["Amount"])
    x_equity = to_int_money(equity, ["Amount"])
    x_trial  = to_int_money(trial_balance, ["Debit", "Credit"])

        # --- Overview (ringkasan kunci) ---
    try:
        net_income_val = int(Decimal(str(
            income_statement.loc[income_statement["Item"] == "Net Income (Loss)", "Amount"].iloc[0]
        )).quantize(Decimal("1")))
    except Exception:
        net_income_val = 0
    overview = pd.DataFrame([
        {"Metric": "Total Assets", "Value": int(Decimal(str(x_assets["Amount"].sum())).quantize(Decimal("1")))},
        {"Metric": "Total Liabilities", "Value": int(Decimal(str(x_liabs["Amount"].sum())).quantize(Decimal("1")))},
        {"Metric": "Total Equity", "Value": int(Decimal(str(x_equity["Amount"].sum())).quantize(Decimal("1")))},
        {"Metric": "Assets - (Liabilities + Equity)", "Value":
            int(Decimal(str(x_assets["Amount"].sum() - (x_liabs["Amount"].sum() + x_equity["Amount"].sum()))).quantize(Decimal("1")))},
        {"Metric": "Net Income (Loss)", "Value": net_income_val},
    ])

    # --- Konversi angka ke integer untuk mencegah tampilan .00 di Excel ---
    def to_int_cols(df, cols):
        if df is None or df.empty:
            return df
        df = df.copy()
        for c in cols:
            if c in df.columns:
                df[c] = df[c].apply(
                    lambda v: int(Decimal(str(v)).quantize(Decimal("1"))) if pd.notna(v) else 0
                )
        return df

    # Versi integer untuk semua analytics
    x_payments = to_int_cols(payments_by_method, ["Total"]) if not payments_by_method.empty else payments_by_method
    x_revenue  = to_int_cols(revenue_by_month, ["Revenue","Expenses","Payments"]) if not revenue_by_month.empty else revenue_by_month
    x_rec_age  = to_int_cols(receivables_aging, ["Total"]) if not receivables_aging.empty else receivables_aging
    x_rec_det  = receivables_aging_detail.copy()
    if x_rec_det is not None and not x_rec_det.empty:
        for cc in ["remaining_amount","days_overdue"]:
            if cc in x_rec_det.columns:
                x_rec_det[cc] = x_rec_det[cc].apply(
                    lambda v: int(Decimal(str(v)).quantize(Decimal("1"))) if pd.notna(v) else 0
                )
    x_top_cat  = to_int_cols(top_categories, ["Qty","Amount"]) if not top_categories.empty else top_categories
    x_top_serv = to_int_cols(top_services, ["Qty","Amount"])   if not top_services.empty   else top_services

    xlsx_path = out_dir / "salve_reports.xlsx"
    with pd.ExcelWriter(xlsx_path, engine="xlsxwriter") as writer:
        # Overview + seluruh laporan
        overview.to_excel(writer, sheet_name="Overview", index=False)
        x_income.to_excel(writer, sheet_name="Income_Statement", index=False)
        x_assets.to_excel(writer, sheet_name="Balance_Sheet_Assets", index=False)
        x_liabs.to_excel(writer, sheet_name="Balance_Sheet_Liabilities", index=False)
        x_equity.to_excel(writer, sheet_name="Balance_Sheet_Equity", index=False)
        x_trial.to_excel(writer, sheet_name="Trial_Balance", index=False)
        if x_payments is not None and not x_payments.empty:
            x_payments.to_excel(writer, sheet_name="Payments_By_Method", index=False)
        if x_revenue is not None and not x_revenue.empty:
            x_revenue.to_excel(writer, sheet_name="Revenue_By_Month", index=False)
        if x_rec_age is not None and not x_rec_age.empty:
            x_rec_age.to_excel(writer, sheet_name="Receivables_Aging", index=False)
        if x_rec_det is not None and not x_rec_det.empty:
            x_rec_det.to_excel(writer, sheet_name="Receivables_Detail", index=False)
        if x_top_cat is not None and not x_top_cat.empty:
            x_top_cat.to_excel(writer, sheet_name="Top_Categories", index=False)
        if x_top_serv is not None and not x_top_serv.empty:
            x_top_serv.to_excel(writer, sheet_name="Top_Services", index=False)

        wb = writer.book
        fmt_header = wb.add_format({"bold": True, "bg_color": "#F2F2F2"})
        fmt_money = wb.add_format({"num_format": "#,##0"})
        fmt_int   = wb.add_format({"num_format": "#,##0"})

        # Overview
        ws = writer.sheets["Overview"]
        ws.set_row(0, None, fmt_header)
        ws.set_column("A:A", 36)
        ws.set_column("B:B", 22, fmt_int)

        # Income Statement
        ws = writer.sheets["Income_Statement"]
        ws.set_row(0, None, fmt_header)
        ws.set_column("A:A", 32)
        ws.set_column("B:B", 18, fmt_money)

        # Balance Sheet - Assets
        ws = writer.sheets["Balance_Sheet_Assets"]
        ws.set_row(0, None, fmt_header)
        ws.set_column("A:A", 32)
        ws.set_column("B:B", 18, fmt_money)

        # Balance Sheet - Liabilities
        ws = writer.sheets["Balance_Sheet_Liabilities"]
        ws.set_row(0, None, fmt_header)
        ws.set_column("A:A", 32)
        ws.set_column("B:B", 18, fmt_money)

        # Balance Sheet - Equity
        ws = writer.sheets["Balance_Sheet_Equity"]
        ws.set_row(0, None, fmt_header)
        ws.set_column("A:A", 32)
        ws.set_column("B:B", 18, fmt_money)

        # Trial Balance
        ws = writer.sheets["Trial_Balance"]
        ws.set_row(0, None, fmt_header)
        ws.set_column("A:A", 32)
        ws.set_column("B:C", 18, fmt_money)

        # Payments by Method (format & pie chart)
        if "Payments_By_Method" in writer.sheets:
            ws = writer.sheets["Payments_By_Method"]
            ws.set_row(0, None, fmt_header)
            ws.set_column("A:A", 28)
            ws.set_column("B:B", 18, fmt_money)
            chart = wb.add_chart({"type":"pie"})
            # categories = A2:A{n}, values = B2:B{n}
            n = len(payments_by_method) + 1
            chart.add_series({
                "name":"Payments by Method",
                "categories": ["Payments_By_Method", 1, 0, n-1, 0],
                "values":     ["Payments_By_Method", 1, 1, n-1, 1],
                "data_labels": {"value": True, "num_format": "#,##0"}
            })
            chart.set_title({"name":"Payments by Method"})
            ws.insert_chart("D2", chart, {"x_scale":1.2, "y_scale":1.2})

        # Revenue vs Expenses vs Payments by Month (line chart)
        if "Revenue_By_Month" in writer.sheets:
            ws = writer.sheets["Revenue_By_Month"]
            ws.set_row(0, None, fmt_header)
            ws.set_column("A:A", 16)  # Month
            ws.set_column("B:D", 18, fmt_money)
            n = len(revenue_by_month) + 1
            chart = wb.add_chart({"type":"line"})
            chart.add_series({"name":"Revenue","categories":["Revenue_By_Month",1,0,n-1,0],
                              "values":["Revenue_By_Month",1,1,n-1,1]})
            if "Expenses" in revenue_by_month.columns:
                chart.add_series({"name":"Expenses","categories":["Revenue_By_Month",1,0,n-1,0],
                                  "values":["Revenue_By_Month",1,2,n-1,2]})
            if "Payments" in revenue_by_month.columns:
                chart.add_series({"name":"Payments","categories":["Revenue_By_Month",1,0,n-1,0],
                                  "values":["Revenue_By_Month",1,3,n-1,3]})
            chart.set_title({"name":"Monthly Trend"})
            chart.set_y_axis({"num_format":"#,##0"})
            chart.set_x_axis({"num_format":"yyyy-mm"})
            ws.insert_chart("F2", chart, {"x_scale":1.5, "y_scale":1.2})

        # Receivables Aging formatting
        if "Receivables_Aging" in writer.sheets:
            ws = writer.sheets["Receivables_Aging"]
            ws.set_row(0, None, fmt_header)
            ws.set_column("A:A", 20)
            ws.set_column("B:B", 18, fmt_money)
        if "Receivables_Detail" in writer.sheets:
            ws = writer.sheets["Receivables_Detail"]
            ws.set_row(0, None, fmt_header)
            ws.set_column("A:A", 40)   # order_id
            ws.set_column("B:B", 18, fmt_money)
            ws.set_column("C:D", 14)
            ws.set_column("E:E", 14)

        # Top Categories & Services formatting
        if "Top_Categories" in writer.sheets:
            ws = writer.sheets["Top_Categories"]
            ws.set_row(0, None, fmt_header)
            ws.set_column("A:A", 28)
            ws.set_column("B:B", 12, fmt_int)
            ws.set_column("C:C", 18, fmt_money)
        if "Top_Services" in writer.sheets:
            ws = writer.sheets["Top_Services"]
            ws.set_row(0, None, fmt_header)
            ws.set_column("A:A", 36)
            ws.set_column("B:B", 12, fmt_int)
            ws.set_column("C:C", 18, fmt_money)

    print("Done. Files written to:", out_dir.resolve())

if __name__ == "__main__":
    main()
