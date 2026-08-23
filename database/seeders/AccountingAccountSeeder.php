<?php

namespace Database\Seeders;

use App\Models\AccountingAccount;
use App\Models\AccountingAccountMapping;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AccountingAccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['code' => '1000', 'name' => 'Aset', 'type' => 'ASSET', 'normal_balance' => 'DEBIT', 'is_cash_account' => false, 'sort_order' => 10],
            ['code' => '1010', 'name' => 'Kas Cabang', 'type' => 'ASSET', 'normal_balance' => 'DEBIT', 'is_cash_account' => true, 'sort_order' => 11],
            ['code' => '1020', 'name' => 'Bank / Transfer', 'type' => 'ASSET', 'normal_balance' => 'DEBIT', 'is_cash_account' => true, 'sort_order' => 12],
            ['code' => '1030', 'name' => 'QRIS', 'type' => 'ASSET', 'normal_balance' => 'DEBIT', 'is_cash_account' => true, 'sort_order' => 13],
            ['code' => '1040', 'name' => 'Piutang Usaha', 'type' => 'ASSET', 'normal_balance' => 'DEBIT', 'is_cash_account' => false, 'sort_order' => 14],
            ['code' => '1050', 'name' => 'Peralatan & Aset Tetap', 'type' => 'ASSET', 'normal_balance' => 'DEBIT', 'is_cash_account' => false, 'sort_order' => 15],

            ['code' => '2000', 'name' => 'Liabilitas', 'type' => 'LIABILITY', 'normal_balance' => 'CREDIT', 'is_cash_account' => false, 'sort_order' => 20],

            ['code' => '2010', 'name' => 'Uang Muka Pelanggan', 'type' => 'LIABILITY', 'normal_balance' => 'CREDIT', 'is_cash_account' => false, 'sort_order' => 21],
            ['code' => '2020', 'name' => 'Utang Pinjaman', 'type' => 'LIABILITY', 'normal_balance' => 'CREDIT', 'is_cash_account' => false, 'sort_order' => 22],

            ['code' => '3000', 'name' => 'Ekuitas', 'type' => 'EQUITY', 'normal_balance' => 'CREDIT', 'is_cash_account' => false, 'sort_order' => 30],
            ['code' => '3010', 'name' => 'Modal Pemilik', 'type' => 'EQUITY', 'normal_balance' => 'CREDIT', 'is_cash_account' => false, 'sort_order' => 31],
            ['code' => '3020', 'name' => 'Prive / Penarikan Pemilik', 'type' => 'EQUITY', 'normal_balance' => 'DEBIT', 'is_cash_account' => false, 'sort_order' => 32],

            ['code' => '4000', 'name' => 'Pendapatan', 'type' => 'REVENUE', 'normal_balance' => 'CREDIT', 'is_cash_account' => false, 'sort_order' => 40],
            ['code' => '4010', 'name' => 'Pendapatan Laundry', 'type' => 'REVENUE', 'normal_balance' => 'CREDIT', 'is_cash_account' => false, 'sort_order' => 41],
            ['code' => '4020', 'name' => 'Pendapatan Lain-lain', 'type' => 'REVENUE', 'normal_balance' => 'CREDIT', 'is_cash_account' => false, 'sort_order' => 42],
            ['code' => '4090', 'name' => 'Diskon Penjualan', 'type' => 'REVENUE', 'normal_balance' => 'DEBIT', 'is_cash_account' => false, 'sort_order' => 49],

            ['code' => '5000', 'name' => 'Beban', 'type' => 'EXPENSE', 'normal_balance' => 'DEBIT', 'is_cash_account' => false, 'sort_order' => 50],
            ['code' => '5010', 'name' => 'Beban Operasional', 'type' => 'EXPENSE', 'normal_balance' => 'DEBIT', 'is_cash_account' => false, 'sort_order' => 51],
            ['code' => '5020', 'name' => 'Beban Bahan Cuci', 'type' => 'EXPENSE', 'normal_balance' => 'DEBIT', 'is_cash_account' => false, 'sort_order' => 52],
            ['code' => '5030', 'name' => 'Beban Listrik dan Air', 'type' => 'EXPENSE', 'normal_balance' => 'DEBIT', 'is_cash_account' => false, 'sort_order' => 53],
            ['code' => '5040', 'name' => 'Beban Transport', 'type' => 'EXPENSE', 'normal_balance' => 'DEBIT', 'is_cash_account' => false, 'sort_order' => 54],
            ['code' => '5090', 'name' => 'Beban Lain-lain', 'type' => 'EXPENSE', 'normal_balance' => 'DEBIT', 'is_cash_account' => false, 'sort_order' => 59],
        ];

        foreach ($accounts as $row) {
            AccountingAccount::query()->updateOrCreate(
                [
                    'branch_id' => null,
                    'code' => $row['code'],
                ],
                [
                    'id' => AccountingAccount::query()
                        ->whereNull('branch_id')
                        ->where('code', $row['code'])
                        ->value('id') ?? (string) Str::uuid(),
                    'parent_id' => null,
                    'name' => $row['name'],
                    'type' => $row['type'],
                    'normal_balance' => $row['normal_balance'],
                    'is_cash_account' => $row['is_cash_account'],
                    'is_active' => true,
                    'sort_order' => $row['sort_order'],
                ]
            );
        }

        $this->seedDefaultMappings();
    }

    private function seedDefaultMappings(): void
    {
        $accountId = fn (string $code) => AccountingAccount::query()
            ->whereNull('branch_id')
            ->where('code', $code)
            ->value('id');

        $mappings = [
            ['event_key' => 'ORDER_PAID_CASH', 'payment_method' => 'CASH', 'expense_category' => null, 'debit' => '1010', 'credit' => '4010'],
            ['event_key' => 'ORDER_PAID_DP', 'payment_method' => 'DP', 'expense_category' => null, 'debit' => '1010', 'credit' => '2010'],
            ['event_key' => 'ORDER_PAID_QRIS', 'payment_method' => 'QRIS', 'expense_category' => null, 'debit' => '1030', 'credit' => '4010'],
            ['event_key' => 'ORDER_PAID_TRANSFER', 'payment_method' => 'TRANSFER', 'expense_category' => null, 'debit' => '1020', 'credit' => '4010'],
            ['event_key' => 'ORDER_RECEIVABLE_CREATED', 'payment_method' => null, 'expense_category' => null, 'debit' => '1040', 'credit' => '4010'],
            ['event_key' => 'RECEIVABLE_SETTLED_CASH', 'payment_method' => 'CASH', 'expense_category' => null, 'debit' => '1010', 'credit' => '1040'],
            ['event_key' => 'EXPENSE_CASH_BOX', 'payment_method' => null, 'expense_category' => null, 'debit' => '5010', 'credit' => '1010'],
            ['event_key' => 'EXPENSE_NON_CASH', 'payment_method' => null, 'expense_category' => null, 'debit' => '5010', 'credit' => '1020'],
            ['event_key' => 'CASH_OPENING_FLOAT', 'payment_method' => null, 'expense_category' => null, 'debit' => '1010', 'credit' => '3010'],
            ['event_key' => 'CASH_WITHDRAWAL', 'payment_method' => null, 'expense_category' => null, 'debit' => '3020', 'credit' => '1010'],
            ['event_key' => 'CASH_ADJUSTMENT_IN', 'payment_method' => null, 'expense_category' => null, 'debit' => '1010', 'credit' => '3010'],
            ['event_key' => 'CASH_ADJUSTMENT_OUT', 'payment_method' => null, 'expense_category' => null, 'debit' => '5090', 'credit' => '1010'],
            ['event_key' => 'ORDER_DISCOUNT', 'payment_method' => null, 'expense_category' => null, 'debit' => '4090', 'credit' => '4010'],
        ];

        foreach ($mappings as $row) {
            $debitId = $accountId($row['debit']);
            $creditId = $accountId($row['credit']);

            if (!$debitId || !$creditId) {
                continue;
            }

            AccountingAccountMapping::query()->updateOrCreate(
                [
                    'branch_id' => null,
                    'event_key' => $row['event_key'],
                    'payment_method' => $row['payment_method'],
                    'expense_category' => $row['expense_category'],
                ],
                [
                    'id' => AccountingAccountMapping::query()
                        ->whereNull('branch_id')
                        ->where('event_key', $row['event_key'])
                        ->where('payment_method', $row['payment_method'])
                        ->where('expense_category', $row['expense_category'])
                        ->value('id') ?? (string) Str::uuid(),
                    'debit_account_id' => $debitId,
                    'credit_account_id' => $creditId,
                    'is_active' => true,
                ]
            );
        }
    }
}
