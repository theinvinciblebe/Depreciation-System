# 🚀 Deploying DepreSys on Vercel

This guide walks you through deploying **DepreSys** (PHP 8.3+ Asset Depreciation & Loan Management System) on **Vercel** with zero-downtime serverless architecture and cross-container session persistence.

---

## 📋 What Has Been Configured

1. **Vercel Runtime Configuration (`vercel.json`)**:
   - Uses the modern community PHP serverless runtime: `vercel-php@0.9.0` (packaging PHP 8.3/8.4/8.5).
   - Routes static assets (`/css`, `/js`, `/fonts`, `/images`, `/vendor`) directly to Vercel's global CDN.
   - Routes all PHP page requests to `api/index.php`.

2. **Serverless Front-Controller Router (`api/index.php`)**:
   - Safely executes root PHP pages (`index.php`, `login.php`, `customer.php`, `migrate.php`, etc.).
   - Provides clean URL support (e.g., accessing `/customer` automatically maps to `customer.php`).
   - Serves as a fallback for any dynamic asset requests.

3. **Stateless Session Persistence (`api/session.php`)**:
   - **The Problem on Vercel**: Standard PHP sessions save to `/tmp` on a single container. When subsequent requests hit different Vercel containers, users get logged out immediately.
   - **Our Solution**: An HMAC-SHA256 cryptographically signed session handler (`DEPRESYS_SESS`) that keeps users logged in seamlessly across all serverless containers and cold starts without requiring Redis or extra services.

4. **Cloud MySQL Compatibility (`connection.php` & `migrate.php`)**:
   - Supports environment variables (`DB_HOST`, `DB_USER`, `DB_PASSWORD`, `DB_NAME`, `DB_PORT`, or a single `DATABASE_URL`).
   - Automatically supports SSL connections for cloud providers like TiDB, Aiven, PlanetScale, and AWS RDS.
   - Fully backward-compatible with your local Laragon environment (`localhost`, `root`, ``, `db_depreciation`).

5. **Serverless-Safe File Uploads**:
   - Error-suppressed disk operations in customer and user forms to safely handle read-only lambda filesystems.

---

## 🗄️ Step 1: Provision a Free Cloud MySQL Database

Because Vercel is a serverless platform, it cannot connect to `localhost`. You need a cloud-hosted MySQL database. You can use any of these free-tier cloud database providers:

| Provider | Free Tier Details | SSL Supported | Setup Time |
| :--- | :--- | :--- | :--- |
| **[Aiven](https://aiven.io)** (Recommended) | Free MySQL 8.0 instance (5 GB storage) | Yes (built-in) | ~2 mins |
| **[TiDB Cloud](https://tidbcloud.com)** | Serverless MySQL-compatible (5 GB free) | Yes | ~2 mins |
| **[Clever Cloud](https://clever-cloud.com)** | Free MySQL add-on | Yes | ~3 mins |
| **[Railway](https://railway.app)** | MySQL service (trial credits) | Yes | ~2 mins |

Once provisioned, note down your connection details:
- **Host** (e.g. `mysql-xxxx.aivencloud.com`)
- **Port** (e.g. `3306` or custom)
- **User** (e.g. `avnadmin` or `root`)
- **Password** (e.g. `your_db_password`)
- **Database Name** (e.g. `defaultdb` or `db_depreciation`)

---

## 🌐 Step 2: Deploy to Vercel

### Method A: Deploy via GitHub (Recommended 1-Click CI/CD)

1. **Commit and push** your changes to your GitHub repository:
   ```bash
   git add .
   git commit -m "feat: configure Vercel deployment with vercel-php runtime and cloud db support"
   git push origin main
   ```

2. Go to **[vercel.com/new](https://vercel.com/new)** and log in with your GitHub account.
3. Under **Import Git Repository**, find `theinvinciblebe/Depreciation-System` and click **Import**.
4. In the **Configure Project** screen:
   - **Framework Preset**: Leave as **Other**.
   - **Root Directory**: `./` (default).
5. Expand the **Environment Variables** section and add:

   | Key | Example Value | Description |
   | :--- | :--- | :--- |
   | `DB_HOST` | `mysql-xxxx.aivencloud.com` | Cloud MySQL host address |
   | `DB_PORT` | `3306` | MySQL port |
   | `DB_USER` | `avnadmin` | MySQL username |
   | `DB_PASSWORD` | `your_secure_password` | MySQL password |
   | `DB_NAME` | `defaultdb` | MySQL database name |
   | `APP_KEY` | *(Generate a 32+ char random string)* | Used to sign session cookies |

   *(Alternatively, if your provider gives you a full connection URL, simply add `DATABASE_URL=mysql://user:pass@host:port/dbname`)*

6. Click **Deploy**!
   Vercel will build and deploy your project in under a minute.

---

### Method B: Deploy via Vercel CLI

If you prefer using the command line:

1. **Log in to Vercel**:
   ```bash
   npx vercel login
   ```

2. **Deploy and link your project**:
   ```bash
   npx vercel
   ```
   Follow the interactive prompts:
   - Set up and deploy? **Y**
   - Which scope? Select your personal account or team.
   - Link to existing project? **N**
   - Project name? **depre-system** (or press Enter)
   - In which directory is your code located? `./`

3. **Set Environment Variables via CLI**:
   ```bash
   npx vercel env add DB_HOST production
   npx vercel env add DB_PORT production
   npx vercel env add DB_USER production
   npx vercel env add DB_PASSWORD production
   npx vercel env add DB_NAME production
   npx vercel env add APP_KEY production
   ```

4. **Deploy to Production**:
   ```bash
   npx vercel --prod
   ```

---

## ⚡ Step 3: Run Database Migrations on Vercel

Once your deployment completes:

1. Open your browser and navigate to:
   ```
   https://<your-project-name>.vercel.app/migrate.php
   ```
2. You will see the **DepreSys Migration Dashboard**.
3. Click the purple **"Run Pending Migrations"** button (or **"Wipe & Fresh Install"** / **"Seed Sample Data"**).
4. All tables (`tblusername`, `tblcustomer`, `tblamortization`, `migrations`) will be created and populated automatically!
5. Navigate to `https://<your-project-name>.vercel.app/` or `login.php`.
   - **Default Admin Username**: `admin`
   - **Default Admin Password**: `admin123` (or as seeded in your initial migration)

---

## 🔒 Security & Best Practices Checklist

- [x] Session cookie signed with HMAC-SHA256 (`DEPRESYS_SESS`) with `HttpOnly` and `SameSite=Lax`.
- [x] Sensitive files (`.git`, `*.sql`, `.idea`) blocked in `.vercelignore`.
- [x] SQL injection protection via parameterized queries (`$conn->execute_query()`).
- [x] Direct execution of include files like `Head.php`, `Navbar.php`, `footer.php` blocked or routed safely.
- [x] Passwords hashed with `password_hash()` and verified with `password_verify()`.

---

## 🛠️ Local Development (Laragon) Compatibility

All changes remain **100% backward-compatible** with your local Laragon setup:
- If no environment variables are present, `connection.php` and `migrate.php` automatically fall back to `localhost`, `root`, ``, and `db_depreciation`.
- You can continue testing and developing locally at `http://depre_system.test` or `http://localhost/depre_system` without altering any configuration.
