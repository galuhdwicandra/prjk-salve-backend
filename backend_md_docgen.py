#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Laravel Full Markdown Doc Generator
-----------------------------------
Mendokumentasikan *SELURUH ISI KODE* untuk:
- app/Http/Controllers
- app/Models
- app/Policies
- app/Http/Requests
- app/Services
- routes/api.php
- app/Providers/AuthServiceProvider.php

Ciri:
- Otomatis deteksi semua file .php pada folder di atas (jika ada).
- Embed isi file *lengkap* ke Markdown (code fence), bisa dalam <details> collapsible.
- Tambahkan ringkasan ringan (path, SHA, ukuran, namespace, daftar class & public method) agar mudah dinavigasi.
- Tanpa dependency eksternal.

Contoh penggunaan:
    python laravel_md_full_docgen.py --root . --out docs/Backend_FullDocs.md
    # tanpa collapsible section:
    python laravel_md_full_docgen.py --root . --out docs/Backend_FullDocs.md --no-collapse

Opsional:
    --title "Judul Kustom"
    --routes "routes/api.php"
    --authp "app/Providers/AuthServiceProvider.php"
"""

from pathlib import Path
from typing import List, Dict, Optional, Tuple
import re
import hashlib
from datetime import datetime
import argparse
import sys
import os

# ---------- Utilities ----------

def sha1_of_file(path: Path) -> str:
    h = hashlib.sha1()
    with path.open('rb') as f:
        for chunk in iter(lambda: f.read(8192), b''):
            h.update(chunk)
    return h.hexdigest()[:12]

def read_text(path: Path) -> str:
    try:
        return path.read_text(encoding='utf-8', errors='replace')
    except Exception:
        return path.read_text(errors='replace')

def php_namespace(text: str) -> Optional[str]:
    m = re.search(r'^\s*namespace\s+([^;]+);', text, re.MULTILINE)
    return m.group(1).strip() if m else None

def find_docblock_before(text: str, start_idx: int) -> Optional[str]:
    upto = text[:start_idx]
    m = re.search(r"/\*\*([\s\S]*?)\*/\s*$", upto, re.MULTILINE)
    if m:
        raw = m.group(1)
        lines = []
        for line in raw.splitlines():
            line = re.sub(r"^\s*\*\s?", "", line.strip())
            lines.append(line)
        cleaned = "\n".join([ln for ln in lines if ln.strip()])
        first_line = cleaned.splitlines()[0].strip() if cleaned else ""
        return first_line or cleaned
    return None

def php_class_info(text: str) -> List[Dict]:
    items = []
    for m in re.finditer(r'^(abstract\s+)?(class|trait|interface)\s+([A-Za-z_]\w*)\s*(?:extends\s+([A-Za-z_\\]\w*))?\s*(?:implements\s+([A-Za-z_\\][^\\{]*))?\s*\{',
                         text, re.MULTILINE):
        kind = m.group(2)
        name = m.group(3)
        extends = (m.group(4) or "").strip()
        implements = (m.group(5) or "").strip()
        start = m.end()
        depth = 1
        i = start
        while i < len(text) and depth > 0:
            if text[i] == '{':
                depth += 1
            elif text[i] == '}':
                depth -= 1
            i += 1
        block = text[start:i-1]
        methods = []
        for fm in re.finditer(
            r'(public|protected|private)\s+(static\s+)?function\s+([A-Za-z_]\w*)\s*\(([^)]*)\)\s*(:\s*([?A-Za-z_\\|\[\]\s]+))?',
            block):
            visibility = fm.group(1)
            name_m = fm.group(3)
            params = ' '.join(fm.group(4).split())
            ret = (fm.group(6) or "").strip()
            doc = find_docblock_before(block, fm.start())
            methods.append({
                "visibility": visibility,
                "name": name_m,
                "params": params,
                "return": ret,
                "doc": doc or ""
            })
        doc = find_docblock_before(text, m.start()) or ""
        items.append({
            "kind": kind,
            "name": name,
            "extends": extends,
            "implements": ' '.join(implements.split()) if implements else "",
            "doc": doc,
            "methods": methods
        })
    return items

def parse_routes(text: str) -> List[Dict]:
    route_re = re.compile(
        r'Route::(get|post|put|patch|delete|options)\s*\(\s*([\'"])(.*?)\2\s*,\s*(.*?)\)\s*',
        re.IGNORECASE
    )
    tokens = re.split(r';\s*\n', text)
    current_prefixes: List[str] = []
    routes = []
    for chunk in tokens:
        chunk_stripped = chunk.strip()
        for pm in re.finditer(r'->prefix\(\s*[\'"]([^\'"]+)[\'"]\s*\)', chunk_stripped):
            current_prefixes.append(pm.group(1))
        for m in route_re.finditer(chunk_stripped):
            method = m.group(1).upper()
            path = m.group(3)
            handler_raw = m.group(4).strip()
            controller = ""
            action = ""
            if '[' in handler_raw and '::class' in handler_raw:
                m2 = re.search(r'\[([A-Za-z_\\]\w*)::class\s*,\s*[\'"]([^\'"]+)[\'"]\]', handler_raw)
                if m2:
                    controller = m2.group(1)
                    action = m2.group(2)
            elif '::class' in handler_raw:
                m2 = re.search(r'([A-Za-z_\\]\w*)::class', handler_raw)
                if m2:
                    controller = m2.group(1)
            elif '@' in handler_raw:
                parts = handler_raw.strip("'\"").split('@', 1)
                if len(parts) == 2:
                    controller, action = parts[0], parts[1]
            prefix = "/".join(p.strip('/') for p in current_prefixes if p)
            full_path = ("/" + prefix + "/" + path.strip("/")).replace("//", "/") if prefix else path
            routes.append({
                "method": method,
                "path": full_path,
                "controller": controller,
                "action": action
            })
        if chunk_stripped.endswith("})") or chunk_stripped.endswith("});"):
            current_prefixes = []
    return routes

def parse_auth_service_provider(text: str) -> Dict:
    policies = []
    m = re.search(r'protected\s+\$policies\s*=\s*\[([\s\S]*?)\];', text)
    if m:
        body = m.group(1)
        for pm in re.finditer(r'([A-Za-z_\\][^=,\n]+)::class\s*=>\s*([A-Za-z_\\][^=,\n]+)::class', body):
            policies.append((pm.group(1).strip(), pm.group(2).strip()))
    gates = []
    for gm in re.finditer(r'Gate::define\(\s*[\'"]([^\'"]+)[\'"]\s*,\s*(?:function|\[|[A-Za-z_\\])', text):
        gates.append(gm.group(1))
    return {"policies": policies, "gates": gates}

def human_size(n: int) -> str:
    for unit in ['B','KB','MB','GB']:
        if n < 1024.0:
            return f"{n:.0f} {unit}"
        n /= 1024.0
    return f"{n:.0f} TB"

# ---------- Scanners ----------

def collect_php_files(root: Path, rel: str) -> List[Path]:
    base = root / rel
    if not base.exists():
        return []
    return sorted([p for p in base.rglob("*.php") if "vendor" not in p.parts])

def summarize_file(path: Path) -> Dict:
    text = read_text(path)
    ns = php_namespace(text) or ""
    classes = php_class_info(text)
    sha = sha1_of_file(path)
    size = path.stat().st_size if path.exists() else 0
    return {
        "path": str(path),
        "namespace": ns,
        "classes": classes,
        "sha": sha,
        "size": size,
        "text": text,
    }

# ---------- Markdown ----------

def anchorize(title: str) -> str:
    a = title.lower().strip()
    a = re.sub(r'[^a-z0-9\s-]', '', a)
    a = re.sub(r'\s+', '-', a)
    a = re.sub(r'-+', '-', a)
    return a

def make_markdown(sections: Dict[str, List[Dict]], routes_file: Optional[Dict], authp_file: Optional[Dict],
                  title: str, root: Path, collapse: bool) -> str:
    now = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    md = []
    md.append(f"# {title}\n")
    md.append(f"_Dihasilkan otomatis: {now}_  \n**Root:** `{root}`\n")

    # TOC
    md.append("\n## Daftar Isi")
    for label, files in sections.items():
        if not files:
            continue
        anc = anchorize(label)
        md.append(f"\n- [{label}](#{anc})")
        for meta in files:
            rel = Path(meta['path'])
            try:
                rel = rel.relative_to(root)
            except Exception:
                pass
            md.append(f"  - [{rel}](#file-{anchorize(str(rel))})")
    if routes_file:
        md.append("\n- [routes/api.php](#routesapiphp)")
    if authp_file:
        md.append("\n- [AuthServiceProvider.php](#authserviceproviderphp)")
    md.append("")

    # Sections
    for label, files in sections.items():
        if not files:
            continue
        md.append(f"\n\n## {label}\n")
        for meta in files:
            p = Path(meta['path'])
            try:
                rel = p.relative_to(root)
            except Exception:
                rel = p
            md.append(f"### {rel}\n")
            md.append(f"- SHA: `{meta['sha']}`  \n- Ukuran: {human_size(meta['size'])}  \n- Namespace: `{meta['namespace']}`")
            if meta['classes']:
                for c in meta['classes']:
                    extends = f" extends `{c['extends']}`" if c.get('extends') else ""
                    implements = f" implements `{c['implements']}`" if c.get('implements') else ""
                    md.append(f"\n**{c['kind'].title()} `{c['name']}`{extends}{implements}**")
                    if c.get('doc'):
                        md.append(f"\n> {c['doc']}")
                    pub_methods = [m for m in c['methods'] if m['visibility'] == 'public']
                    if pub_methods:
                        md.append("\nMetode Publik:")
                        for m in pub_methods:
                            sig = f"- **{m['name']}**({m['params']})"
                            if m.get('return'):
                                sig += f" : *{m['return']}*"
                            if m.get('doc'):
                                sig += f" — {m['doc']}"
                            md.append(sig)
            # Full code block
            code_block = "```php\n" + meta['text'] + "\n```\n"
            if collapse:
                md.append("<details><summary><strong>Lihat Kode Lengkap</strong></summary>\n\n" + code_block + "</details>\n")
            else:
                md.append(code_block)

    # routes/api.php
    if routes_file:
        meta = routes_file
        md.append("\n\n## routes/api.php\n")
        md.append(f"- SHA: `{meta['sha']}`  \n- Ukuran: {human_size(meta['size'])}")
        # quick table
        routes = meta.get("routes_parsed", [])
        if routes:
            md.append("\n**Ringkasan Routes (deteksi heuristik):**\n")
            md.append("| Method | Path | Controller | Action |")
            md.append("|---|---|---|---|")
            for r in routes:
                md.append(f"| {r['method']} | `{r['path']}` | `{r['controller']}` | `{r['action']}` |")
        code_block = "```php\n" + meta['text'] + "\n```\n"
        if collapse:
            md.append("\n<details><summary><strong>Lihat Kode Lengkap</strong></summary>\n\n" + code_block + "</details>\n")
        else:
            md.append("\n" + code_block)

    # app/Providers/AuthServiceProvider.php
    if authp_file:
        meta = authp_file
        md.append("\n\n## AuthServiceProvider.php\n")
        md.append(f"- SHA: `{meta['sha']}`  \n- Ukuran: {human_size(meta['size'])}")
        if meta.get("auth_summary"):
            s = meta["auth_summary"]
            if s.get("policies"):
                md.append("\n**$policies**")
                for m, p in s["policies"]:
                    md.append(f"- `{m}` => `{p}`")
            if s.get("gates"):
                md.append("\n**Gate::define()**")
                for g in s["gates"]:
                    md.append(f"- `{g}`")
        code_block = "```php\n" + meta['text'] + "\n```\n"
        if collapse:
            md.append("\n<details><summary><strong>Lihat Kode Lengkap</strong></summary>\n\n" + code_block + "</details>\n")
        else:
            md.append("\n" + code_block)

    return "\n".join(md)

# ---------- Main ----------

def run():
    ap = argparse.ArgumentParser(description="Generate FULL Markdown docs for a Laravel backend (embed all file contents).")
    ap.add_argument("--root", required=True, help="Path ke root backend (berisi app/, routes/, dll.)")
    ap.add_argument("--out", default="docs/Backend_FullDocs.md", help="Output file Markdown (default: docs/Backend_FullDocs.md)")
    ap.add_argument("--title", default="Dokumentasi Backend (FULL Source)", help="Judul dokumen")
    ap.add_argument("--routes", default="routes/api.php", help="Relative path ke API routes file")
    ap.add_argument("--authp", default="app/Providers/AuthServiceProvider.php", help="Relative path ke AuthServiceProvider")
    ap.add_argument("--no-collapse", action="store_true", help="Tampilkan kode langsung (tanpa <details> collapsible)")
    args = ap.parse_args()

    root = Path(args.root).resolve()
    if not root.exists():
        print(f"[ERR] root tidak ditemukan: {root}", file=sys.stderr)
        sys.exit(2)

    targets = {
        "Controllers (app/Http/Controllers/Api)": "app/Http/Controllers/Api",
        "Models (app/Models)": "app/Models",
        "Policies (app/Policies)": "app/Policies",
        "Form Requests (app/Http/Requests)": "app/Http/Requests",
        "Services (app/Services)": "app/Services",
        "Database (seeders)": "database/seeders",
        "resources (resources)": "resources/views/orders",
    }

    sections: Dict[str, List[Dict]] = {}
    for label, rel in targets.items():
        files = collect_php_files(root, rel)
        sections[label] = [summarize_file(p) for p in files]

    # routes/api.php
    routes_meta = None
    routes_path = (root / args.routes)
    if routes_path.exists():
        rtxt = read_text(routes_path)
        routes_meta = summarize_file(routes_path)
        try:
            routes_meta["routes_parsed"] = parse_routes(rtxt)
        except Exception:
            routes_meta["routes_parsed"] = []

    # AuthServiceProvider
    authp_meta = None
    authp_path = (root / args.authp)
    if authp_path.exists():
        atxt = read_text(authp_path)
        authp_meta = summarize_file(authp_path)
        try:
            authp_meta["auth_summary"] = parse_auth_service_provider(atxt)
        except Exception:
            authp_meta["auth_summary"] = {}

    collapse = (not args.no-collapse) if hasattr(args, "no-collapse") else True  # safeguard for hyphen
    # argparse stores --no-collapse as args.no_collapse, so correct:
    if hasattr(args, "no_collapse"):
        collapse = not args.no_collapse

    md = make_markdown(sections, routes_meta, authp_meta, args.title, root, collapse)

    out_path = Path(args.out)
    if not out_path.is_absolute():
        out_path = (Path.cwd() / out_path).resolve()
    out_path.parent.mkdir(parents=True, exist_ok=True)
    out_path.write_text(md, encoding="utf-8")

    print(f"[OK] Markdown generated -> {out_path} ({len(md.splitlines())} lines)")

if __name__ == "__main__":
    run()
