# The Cheddarain — Premium Restaurant Platform with Full Order Tracking & Admin Portal

> **"Dripping in Cheddar, Crafted to Perfection."**

A complete, production-ready restaurant ordering website for **The Cheddarain**, built with a pure **HTML5/CSS3/Vanilla JavaScript** frontend and a robust **Laravel 12 (PHP)** backend with **MySQL / SQLite** database persistence.

All customer orders are stored directly in the database, automatically calculated with server-side validation, and instantly displayed in the protected restaurant management admin portal (`/admin`).

---

## 🌟 Key Features

### 1. Database-Driven Order Management & Admin Portal (`/admin`)
- **Direct Database Persistence**: Orders are saved using database transactions across `orders`, `order_items`, and `order_status_histories`.
- **Protected Operational Dashboard**:
  - Daily order counters, sales summaries, and pending ticket alerts.
  - Real-time kitchen queue with one-click status transitions.
- **Admin Orders Manager (`/admin/orders`)**:
  - Filter by status (`pending`, `confirmed`, `preparing`, `ready_for_pickup`, `out_for_delivery`, `delivered`, `cancelled`).
  - Search by unique Order Reference (`#CHED-XXXXXX`), customer name, or telephone number.
  - Filter by order creation date.
- **Staff Audit Trail**: Every status transition logs the staff member's name, timestamp, and audit notes.
- **Menu Management**: Full CRUD for products, prices, categories, and availability toggles.
- **Store Settings**: Configure store telephone, WhatsApp contact, delivery fees, and operating hours.

---

### 2. Customer Fast-Casual Website
- **Pure Vanilla Architecture**: Strictly zero heavy frontend frameworks (no React, Vue, Angular, Bootstrap, or Tailwind).
- **Luxury Fast-Casual Aesthetic**: Deep black backgrounds (`#0a0b0e`), artisan cheddar-orange and gold accents (`#ff9900`, `#fcbf49`), cream typography (`#faf6ee`).
- **Pages**:
  - **Home**: Hero banner, popular meals, ordering instructions, verified customer reviews.
  - **Menu**: Category tabs (Burgers, Fries, Signature Sauces, Combos and Meals), live search, dietary badges.
  - **Product Details**: Customization modal with buns, extra sauces, and add-ons.
  - **Cart**: Real-time quantity adjustments, options breakdown, subtotal & delivery fee calculations.
  - **Checkout**: Delivery vs Pickup toggle, address inputs, customer contact details, CSRF protection.
  - **Order Confirmation**: Displays unique order reference `#CHED-XXXXXX`, receipt breakdown, and in-person payment guidance.
  - **Track My Order**: McDonald's-style 5-stage live order progress timeline with security phone verification.
  - **Customer Portal (`/account`)**: Customer registration (`/register`) and login (`/login`) to view past orders.
  - **About Us & Contact**: Culinary philosophy, store hours, location, and WhatsApp enquiry button.

---

### 3. In-Person Payment Workflow (No Payment Gateway Needed)
- Customers do **not** enter credit cards online.
- When an order is submitted, the system instantly generates an **Order Reference / Order ID** (e.g. `#CHED-782901`).
- **Pickup Orders**: Customers bring their Order ID to counter #1 to pay with Cash, Card, or Contactless upon collection.
- **Delivery Orders**: Customers show their Order ID to the delivery courier and pay upon arrival.

---

## 💻 Technology Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Database**: SQLite (out-of-the-box local testing) / MySQL 5.7+ / 8.0+ (cPanel / Plesk production)
- **Frontend**: HTML5, CSS3, Vanilla JavaScript (ES6+)
- **Authentication**: Laravel Session Auth with Role-Based Access Control (`admin`, `staff`, `customer`)
- **Testing**: Automated PHPUnit test suite (18 tests, 125 assertions)

---

## 🔐 Default Demo Accounts

| Role | Email | Password | Access Portal |
|---|---|---|---|
| **Admin** | `admin@thecheddarain.com` | `admin123` | Full administrative control (`/admin`) |
| **Kitchen Staff** | `kitchen@thecheddarain.com` | `Kitchen2026!` | Order updates & kitchen dispatch queue (`/admin`) |
| **Customer** | `customer@thecheddarain.com` | `customer123` | Customer login & order history (`/login`) |

### Demo Orders for Live Tracking:
- `CHED-782901` — **Preparing** (Phone: `5551234567`)
- `CHED-782902` — **Ready for Pickup** (Phone: `5551234567`)
- `CHED-782904` — **Delivered** (Phone: `5551234567`)

---

## 🚀 Quickstart & Local Testing

1. **Environment Configuration**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

2. **Run Migrations & Seeders**:
   ```bash
   php artisan migrate --seed
   ```

3. **Start Development Server**:
   ```bash
   php artisan serve --host=127.0.0.1 --port=8000
   ```
   Open your browser to: **`http://127.0.0.1:8000`**

4. **Access the Admin Portal**:
   - Go to: **`http://127.0.0.1:8000/admin/login`**
   - Click the **Auto-Fill Admin** button (or enter `admin@thecheddarain.com` / `admin123`).
   - View all incoming orders, metrics, and manage the kitchen pipeline!

---

## 🧪 Automated Test Suite

Run the complete test suite verifying checkout integrity, price calculation, database persistence, phone verification, and admin order transitions:

```bash
php artisan test
```

Result: **18 tests passed, 125 assertions**.
