# The Cheddarain — Premium Restaurant Platform with Full Order Tracking

> **"Dripping in Cheddar, Crafted to Perfection."**

A complete, production-ready restaurant website for **The Cheddarain**, specializing in artisan smashed Angus burgers, double-fried loaded russet fries, and signature liquid gold cheddar sauces. Built with a pure Vanilla HTML5/CSS3/JavaScript frontend and a robust Laravel (PHP) backend, ready for direct deployment on cPanel or Plesk hosting with MySQL.

---

## 🌟 Key Features

### 1. McDonald's-Inspired Live Order Tracking System
- **5-Stage Visual Progress Timeline**:
  1. **Order Placed**: Received by kitchen dispatch.
  2. **Order Confirmed**: Verified and accepted by staff.
  3. **Preparing Your Food**: Patties searing and cheddar melting (with live estimated prep countdown).
  4. **Ready for Pickup / Out for Delivery**: Dynamic status adapted to chosen fulfillment method.
  5. **Delivered / Completed**: Final delivery hand-off.
- **Edge States**: Clear handling for *Pending Confirmation* and *Cancelled / Rejected* tickets with staff cancellation reasons.
- **Security Verification Step**: Requires customer's registered phone number to prevent unauthorized inspection of personal addresses.
- **Real-Time Polling**: Automatic status refresh every 7 seconds via lightweight REST endpoint (`/api/orders/{reference}/track`) without page reloads.
- **Staff Audit Trail Log**: Every status transition records the staff member, exact timestamp, and audit notes.
- **Direct WhatsApp Support**: One-click enquiry button pre-filled with the unique order reference (`#CHED-XXXXXX`).

### 2. Luxury Fast-Casual Customer Website
- **Aesthetic**: Deep charcoal & black backgrounds (`#0a0b0e`, `#12141a`), vibrant cheddar-orange (`#ff9900`) and golden-yellow (`#fcbf49`) accents, cream typography (`#faf6ee`).
- **Pages**:
  - **Home**: Hero showcase, popular meals, 3-step ordering process, verified customer reviews.
  - **Menu**: Category tabs (Burgers, Fries, Signature Sauces, Combos and Meals), live search, dietary badges, calorie counts.
  - **Product Details**: Ingredients, calories, preparation time, allergen warnings, dynamic customization modal.
  - **Cart**: Real-time quantity adjustments, options breakdown, subtotal & delivery fee calculations.
  - **Checkout**: Contact details, delivery vs pickup toggle, address validation, payment method selection, CSRF protection.
  - **Order Confirmation**: Displays order reference, phone verification reminder, itemized receipt, direct link to live tracking.
  - **About Us**: Culinary philosophy, 100% prime Angus standards, fresh Wisconsin cheddar melt recipe.
  - **Contact**: Store address, operating hours, interactive contact form, WhatsApp direct line.
  - **FAQ**: Interactive accordion covering delivery, ordering, allergens, and tracking.

### 3. Protected Restaurant Staff & Admin Portal (`/admin`)
- **Secure Authentication**: Password hashing (Bcrypt), session protection, role-based access (`admin`, `staff`).
- **Operational Dashboard**: Today's orders, revenue, pending tickets, active kitchen pipeline with one-click status transitions.
- **Order Management**: Filter by status and date, search by reference or customer phone, view full customer notes.
- **Menu Management**: Full CRUD for products, prices, categories, images, and availability toggles.
- **Store Settings**: Configure restaurant phone, WhatsApp number, physical address, delivery fees, and opening hours.

---

## 💻 Technology Stack

- **Frontend**: HTML5, CSS3, Vanilla JavaScript (ES6+) — *Strictly zero frameworks (no React, Vue, Angular, Bootstrap, or Tailwind)*.
- **Backend**: Laravel (PHP 8.2+).
- **Database**: MySQL 5.7+ / 8.0+ / MariaDB 10.3+ (SQLite supported out-of-the-box for local testing).
- **Architecture**: MVC, Eloquent ORM, RESTful API, Database Transactions, Role Middleware, CSRF Protection.

---

## 🚀 Local Development Quickstart

1. **Clone or Extract**:
   ```bash
   cd c:\Users\Temeb\Desktop\lamri
   ```

2. **Environment Setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Run Migrations & Seeders**:
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Launch Local Server**:
   ```bash
   php artisan serve
   ```
   Open your browser to: **`http://127.0.0.1:8000`**

---

## 🔐 Default Demo Accounts

| Role | Email | Password | Purpose |
|---|---|---|---|
| **Admin** | `admin@thecheddarain.com` | `Cheddarain2026!` | Full administrative access |
| **Kitchen Staff** | `kitchen@thecheddarain.com` | `Kitchen2026!` | Order status updates & kitchen queue |

### Demo Orders for Instant Tracking Testing:
- `CHED-782901` — **Preparing** (Phone: `5551234567`)
- `CHED-782902` — **Ready for Pickup** (Phone: `5551234567`)
- `CHED-782904` — **Delivered** (Phone: `5551234567`)

---

## 🌐 cPanel / Plesk Deployment Guide

### Method A: Pointing Document Root to `/public` (Recommended)

1. **Create MySQL Database in cPanel**:
   - Go to **cPanel > MySQL Databases**.
   - Create a new database: e.g. `cpaneluser_cheddarain`.
   - Create a database user: e.g. `cpaneluser_cheddar` and set a strong password.
   - Assign the user to the database with **ALL PRIVILEGES**.

2. **Import Database Schema**:
   - Go to **cPanel > phpMyAdmin**.
   - Select your newly created database.
   - Click **Import**, choose the file `database/cheddarain_db.sql`, and click **Go**.

3. **Upload Files**:
   - In cPanel File Manager, upload `the-cheddarain-restaurant.zip` to your account root (e.g. `home/cpaneluser/`).
   - Extract the files into a private directory, e.g. `home/cpaneluser/cheddarain/`.

4. **Configure Document Root**:
   - Go to **cPanel > Domains** (or Addon Domains).
   - Change the Document Root of your domain to:
     `home/cpaneluser/cheddarain/public`

5. **Configure `.env`**:
   - Copy `.env.example` to `.env` inside `cheddarain/`.
   - Update your MySQL credentials:
     ```env
     APP_ENV=production
     APP_DEBUG=false
     APP_URL=https://yourdomain.com

     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=cpaneluser_cheddarain
     DB_USERNAME=cpaneluser_cheddar
     DB_PASSWORD=your_strong_mysql_password

     WHATSAPP_NUMBER="15552433327"
     ```

6. **Set File Permissions**:
   - Ensure the following directories are writable by the web server (chmod `775` or `755`):
     - `storage/`
     - `storage/logs/`
     - `storage/framework/`
     - `bootstrap/cache/`

### Method B: Single `public_html` Shared Hosting Setup
If your shared hosting account forces everything into `public_html`:
- The repository already includes a root `.htaccess` file that automatically redirects requests to `public/` while securing sensitive files (`.env`, `storage/`, `database/`).
- Simply upload the contents into `public_html/`, configure `.env`, and import `database/cheddarain_db.sql`.

---

## 🧪 Automated Test Suite

Run the complete automated test suite verifying checkout integrity, price calculation, phone verification, and admin order transitions:
```bash
php artisan test
```
Result: **13 tests passed, 103 assertions**.
