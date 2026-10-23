# The Cheddarloin — Premium Restaurant Platform with Full Order Tracking & Admin Portal

> **"THE ART OF THE BURGER — Burgers, elevated. Made your way and prepared for delivery."**

A complete, production-ready restaurant ordering website for **The Cheddarloin**, built with a pure **HTML5/CSS3/Vanilla JavaScript** frontend and a robust **Laravel 12 (PHP)** backend with **MySQL / SQLite** database persistence.

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
- **Menu Management (Mobile & Desktop CRUD)**:
  - Responsive card-based view on mobile smartphones and high-density data table on desktop.
  - Create, read, update, delete, search, and category filters.
  - Live photo preview and device camera/gallery upload support.
  - 1-touch stock availability toggling (`In Stock` / `Out of Stock`).
- **Store Settings**: Configure store telephone, WhatsApp contact, delivery fees, and operating hours.

---

## 🔐 Default Demo Accounts

| Role | Email | Password | Access Portal |
|---|---|---|---|
| **Admin** | `admin@thecheddarloin.com` | `admin123` | Full administrative control & menu CRUD (`/admin`) |
| **Customer** | `customer@thecheddarloin.com` | `customer123` | Customer login & order history (`/login`) |

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
   - Click the **Auto-Fill Admin** button (or enter `admin@thecheddarloin.com` / `admin123`).
   - View all incoming orders, manage menu CRUD, and track sales!

---

## 🧪 Automated Test Suite

Run the complete test suite verifying checkout integrity, price calculation, database persistence, phone verification, and admin order transitions:

```bash
php artisan test
```

Result: **24 tests passed, 154 assertions**.
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
- **Authentication**: Laravel Session Auth with Role-Based Access Control (`admin`, `customer`)
- **Testing**: Automated PHPUnit test suite (24 tests, 154 assertions)
