# Kreavio Creative - AI Agent Guidelines & Engineering Standards (ECC Spec)

This document establishes the official engineering guidelines, architectural guardrails, and quality gates for AI agents and developers working on the **Kreavio Creative** codebase. It adheres to the **Everything Claude Code (ECC)** standard operating principles:
**Plan → Test → Implement → Review → Verify → Improve**.

---

## 1. Project Overview & Tech Stack

* **Application**: Kreavio Creative - Digital Graphic Design Order & Payment Web Platform.
* **Backend**: Pure PHP 8.x (native, no heavy framework, optimized for XAMPP / Apache).
* **Database**: MySQL 5.7+ / MariaDB 10.x using **PHP Data Objects (PDO)** with real prepared statements (`EMULATE_PREPARES => false`).
* **Frontend**: Bootstrap 5.3.2, Bootstrap Icons 1.11.3, custom responsive CSS (`assets/css/style.css`).
* **Payment Gateway**: Midtrans Snap API (Production & Sandbox toggle, Demo mode fallback).
* **Theme Support**: Dark Mode & Light Mode synchronized across Customer, Admin, and Landing pages via `localStorage.getItem('kreavio_theme')`.
* **Invoice Engine**: Native single-page A4 print layout with `@media print` rules avoiding pagination overflow.

---

## 2. Directory Structure

```text
kreavio/
├── config/
│   ├── config.php            # Core configs, .env loader, session params, helpers
│   └── database.php          # PDO connection instance with utf8mb4 and ERRMODE_EXCEPTION
├── includes/
│   ├── header.php            # Global navigation, theme toggle, meta tags
│   ├── footer.php            # Global footer and copyright
│   ├── auth.php              # Customer authentication guard (require_login, is_logged_in)
│   └── admin-auth.php        # Admin authentication guard (require_admin, is_admin_logged_in)
├── customer/
│   ├── dashboard.php         # Customer overview
│   ├── orders.php            # Customer order history table
│   ├── order-detail.php      # Order tracker timeline & details
│   └── invoice.php           # Official A4 single-page invoice (theme-aware, instant print/PDF)
├── admin/
│   ├── index.php             # Admin dashboard & stats summary
│   ├── orders.php            # Admin order management
│   ├── order-detail.php      # Status changer and delivery upload
│   ├── services.php          # Service CRUD management
│   ├── sales-report.php      # Financial reports with period filters (Today, Week, Month, Year)
│   ├── export-excel.php      # Excel/CSV download for reporting
│   └── invoice.php           # Admin view of invoice
├── payment/
│   └── notification.php      # Midtrans webhook handler with SHA-512 signature check
├── database/
│   └── schema.sql            # Database DDL and default seed data
├── tests/
│   └── smoke_test.php        # Automated verification & lint test suite
├── .env.example              # Environment variables template
├── .gitignore                # Protected files list (.env, logs, .agents, scratch)
└── AGENTS.md                 # This ECC architecture specification
```

---

## 3. Security & Coding Guardrails (ECC Standards)

### A. SQL Injection Prevention
* **RULE**: NEVER concatenate raw variables into SQL query strings.
* **CORRECT**:
  ```php
  $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? AND status = ?");
  $stmt->execute([$userId, $status]);
  ```
* **FORBIDDEN**:
  ```php
  $pdo->query("SELECT * FROM orders WHERE user_id = " . $_GET['id']); // VIOLATION!
  ```

### B. Cross-Site Scripting (XSS) Prevention
* **RULE**: All dynamic data printed in HTML templates MUST be escaped using the `e()` helper function.
* **CORRECT**:
  ```php
  <p><?= e($order['customer_name']) ?></p>
  ```
* **FORBIDDEN**:
  ```php
  <p><?= $order['customer_name'] ?></p> // VIOLATION!
  ```

### C. Secrets Management
* **RULE**: Never commit actual database passwords, Midtrans Server Keys, or personal credentials to Git.
* Store all environment secrets in `.env`.
* Keep `.env.example` up to date with safe dummy/placeholder values.

### D. Single-Page Invoice Integrity
* The invoice (`customer/invoice.php` and `admin/invoice.php`) is strictly engineered to fit within **1 single A4 page**.
* When modifying invoice layouts, preserve `@page { size: A4 portrait; margin: 8mm 12mm; }` and avoid introducing tall decorative blocks that spill the footer onto page 2.

---

## 4. Verification & Quality Gate Workflow

Before concluding any feature or bugfix:
1. **PHP Syntax Check**: Run `php -l <modified_file.php>`.
2. **Automated Smoke Test**: Execute `php tests/smoke_test.php`. All tests must PASS (status code 0).
3. **Responsive & Theme Verification**: Confirm UI renders correctly on both mobile and desktop, and respects both Light and Dark themes.
4. **Git Workflow Guardrails**:
   - **Local Commit**: Lakukan `git commit` di branch lokal (`dev`) untuk setiap perubahan/fitur yang sudah selesai dan lolos uji, dengan pesan commit yang jelas, profesional, dan **TIDAK mencantumkan embel-embel kata "ECC"**.
   - **NO Auto-Merge / NO Auto-Push**: DILARANG me-merge ke branch `main` atau me-push ke remote GitHub tanpa perintah eksplisit dari USER. Tunggu konfirmasi/perintah pengguna terlebih dahulu.