# DepreSys - Depreciation & Asset Loan Management System

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.3%20%2F%208.4-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP Version">
  <img src="https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Bootstrap-5.3.2-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap 5">
  <img src="https://img.shields.io/badge/Language-EN%20%7C%20KH%20%28%E1%9E%81%E1%9F%92%E1%9E%98%E1%9F%82%E1%9E%84%29-4F46E5?style=for-the-badge" alt="Bilingual">
  <img src="https://img.shields.io/badge/Security-Prepared%20Statements-10B981?style=for-the-badge" alt="Security">
</p>

A modern, fast, and secure **Asset Depreciation and Installment Financing Management System** built with **PHP 8.3+** and a responsive fluid UI. Designed for businesses, lenders, and asset managers to calculate amortization schedules, track payments, generate auditing reports, and monitor overdue accounts in real-time.

---

## 🌟 Key Features

### 💼 Customer & Loan Management
- **Customer Profiles**: Record customer information, phone numbers, National IDs, and residential addresses with photo attachments.
- **Automated Amortization Engine**: Automatically generates monthly installment schedules (Principal + Monthly Interest) over custom financing periods (1 to 60+ months).
- **Interactive Schedule Viewer**: View total paid, remaining balance, settlement progress bars, and due dates per account.
- **One-Click Settlement**: Clear monthly installments with user audit tracking and automatic status updates.

### 📊 Auditing & Comprehensive Reports
- **Due Collections (`report_1.php`)**: Identify payments maturing within the next 14 days.
- **Overdue Delinquency (`report_2.php`)**: Track late payments past their scheduled maturity dates.
- **Pending Loans (`report_3.php`)**: Filter all active customer amortizations with remaining balances.
- **Settled Loans (`report_4.php`)**: Review customers who have completed all repayments.
- **Customer Directory (`report_customer.php`)**: Export-ready customer roster with contact and asset details.
- **Print Optimization**: Built-in clean print formatting with `@media print` styling for paper audits and receipts.

### 🛡️ Modern PHP 8.3 Architecture & Security
- **Strict Typing & Null-Safety**: Built with `declare(strict_types=1);` and null-safe output helpers preventing PHP 8.1+ deprecation notices.
- **Parameterized Queries**: 100% prepared SQL execution via native `$conn->execute_query()` protecting against SQL injection attacks.
- **Cryptographic Password Hashing**: Passwords stored via `password_hash()` and verified with `password_verify()`. Features seamless automatic hash migration for legacy accounts.
- **Session Protection**: Guarded session state, CSRF awareness, and self-deletion lockout prevention.
- **UTF-8mb4 Support**: Native full Unicode support for Khmer text (ភាសាខ្មែរ) and international characters.

### 🎨 Fluid UX & Bilingual Localization
- **Dual Themes**: Smooth dark mode and light mode with instant synchronization, morphing icons, and system preference detection (`prefers-color-scheme`).
- **Multi-Language (EN / KH)**: Instant toggle between **English** and **ភាសាខ្មែរ (Khmer)** with persistent session cookies and query parameter preservation.
- **Responsive Layout**: Powered by Bootstrap 5, FontAwesome 6, and custom glassmorphism styles.

---

## 🏗️ Project Architecture

```
depre_system/
├── css/
│   └── modern-theme.css        # Fluid design system tokens & dark mode
├── js/
│   ├── sweetalert.js           # SweetAlert2 modal bindings
│   └── (custom utilities)
├── images/                     # Uploaded customer & user avatar assets
├── db_depreciation.sql         # Database schema, seed data & SQL views
├── connection.php              # MySQLi connection & null-safe helpers
├── auth.php                    # Authentication guard & security headers
├── lang.php                    # Bilingual dictionary (EN & KH)
├── login.php                   # Modern glassmorphism login portal
├── logout.php                  # Safe session destruction & cookie removal
├── Head.php                    # Global HTML <head>, fonts & CDN styles
├── Navbar.php                  # Modern header, navigation & quick controls
├── footer.php                  # Footer scripts & theme controller
├── index.php                   # App dashboard router
├── dashboard.php               # KPI cards, metrics & quick shortcuts
├── customer.php                # Customer list & directory
├── customer-add.php            # Customer registration & loan generator
├── customer-edit.php           # Customer profile modification
├── customer-delete.php         # Customer deletion & depreciation cleanup
├── depreciation.php            # Schedule breakdown & settlement table
├── clearAmount.php             # Installment payment clearing action
├── UserAccount.php             # Staff & Administrator roster
├── useraccount-add.php         # User account creation
├── useraccount-edit.php        # User account editing & password updater
├── useraccount-delete.php      # User removal with anti-lockout check
├── report_1.php                # Report: Due Collections (Next 14 Days)
├── report_2.php                # Report: Overdue / Late Accounts
├── report_3.php                # Report: Pending Loans
├── report_4.php                # Report: Fully Cleared Loans
└── report_customer.php         # Report: Customer Master Directory
```

---

## 🚀 Getting Started

### Prerequisites
- **Web Server**: Apache / Nginx (Laragon, XAMPP, or WampServer)
- **PHP**: PHP 8.2, 8.3, or 8.4
- **Database**: MySQL 8.0+ or MariaDB 10.4+
- **PHP Extensions**: `mysqli`, `session`, `mbstring`

---

### Step 1: Clone or Place Project
Place the project in your local web server root directory:
```bash
# Example for Laragon
cd d:/laragon/www/
git clone https://github.com/theinvinciblebe/Depreciation-System.git depre_system
```

### Step 2: Set Up Database
1. Open your database administration tool (**phpMyAdmin**, **HeidiSQL**, or **Navicat**).
2. Create a new database named:
   ```sql
   CREATE DATABASE db_depreciation CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
3. Import the database dump file:
   - File path: `d:/laragon/www/depre_system/db_depreciation.sql`

### Step 3: Configure Database Connection
Edit [connection.php](file:///d:/laragon/www/depre_system/connection.php) if your local MySQL settings differ from default:
```php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "db_depreciation";
```

### Step 4: Run the Application
Open your browser and navigate to:
```
http://localhost/depre_system/
```
*(or `http://depre_system.test/` if using Laragon auto-virtual hosts)*

---

## 🔑 Default Credentials

| Username | Password | Role |
| :--- | :--- | :--- |
| **`Admin`** | **`123`** | System Administrator |
| **`Thea`** | **`001`** | Staff / Auditor |

> [!NOTE]
> Upon your first successful login, legacy plaintext passwords will automatically and transparently be rehashed using PHP's `PASSWORD_DEFAULT` algorithm.

---

## 🛠️ Verification & Testing

To lint and verify all PHP files for syntax compatibility:
```powershell
Get-ChildItem -Filter *.php | ForEach-Object { php -l $_.FullName }
```

To run a quick built-in local development server without Apache:
```bash
php -S localhost:8000
```
Then visit `http://localhost:8000/login.php`.

---

## 📄 License
This project is open-source and available under the [MIT License](LICENSE).
