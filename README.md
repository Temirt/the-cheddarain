# The Cheddarloin — Artisan Burger Restaurant Website

> **"The Art of the Burger — Burgers, elevated. Made your way and prepared for delivery."**

A complete, functional restaurant ordering website for **The Cheddarloin**, crafted with pure HTML5, CSS3, and vanilla JavaScript for the frontend, prepared for direct **Netlify** deployment, featuring **Formspree** order intake and a secure **Telegram Bot** serverless notification integration.

---

## 🌟 Architectural Highlights

- **Currency**: Ethiopian Birr (**ETB**).
- **No Database**: Operates without MySQL, Postgres, MongoDB, Firebase, or external databases.
- **No Email Notifications**: Customer emails are never collected or sent. All order confirmations are conducted directly via customer telephone number.
- **No Customer Accounts**: No registration or login required — zero friction ordering.
- **No Live Order Tracking**: Orders are submitted and acknowledged with an instant unique reference code (`#CHD-XXXXXX`).
- **No Online Payments**: Zero payment gateway dependencies. Customers pay upon delivery or pickup.
- **Browser localStorage**: Used strictly for temporary client cart persistence and recent local order history on that device (not a database).
- **Dual Service Architecture**:
  1. **Formspree**: Processes customer order submissions without sending emails.
  2. **Telegram Bot**: Sends structured, real-time order alerts to the restaurant owner's personal Telegram chat via a secure Netlify serverless function.

---

## 📁 Project Structure

```
├── index.html                          # Luxury single-page restaurant portal & modals
├── css/
│   └── style.css                       # Design system (black, gold, charcoal cards)
├── js/
│   ├── products.js                     # Authoritative product catalog & ETB pricing
│   ├── cart.js                         # LocalStorage shopping cart engine
│   ├── checkout.js                     # Validation, Formspree & Telegram submission
│   └── app.js                          # UI orchestration, menu rendering & modals
├── netlify/
│   └── functions/
│       └── telegram-order.js           # Serverless Node.js function (server validation & Telegram API)
├── netlify.toml                        # Netlify redirects, function routes, security headers
├── .env.example                        # Guide for Telegram & Formspree environment variables
├── .gitignore                          # Standard git ignore rules
└── README.md                           # Documentation & setup guide
```

---

## 🎨 Design & Aesthetic

- **Theme**: Luxury fast-casual restaurant aesthetic.
- **Background**: Deep ultra-black (`#0A0A0A` / `#121212`) with ambient gold highlights.
- **Cards**: Dark charcoal (`#181818`) with gold border accents (`rgba(212, 175, 55, 0.25)`).
- **Buttons**: Gold metallic gradients (`linear-gradient(135deg, #E5C07B, #D4AF37, #AA771C)`).
- **Typography**: `Cinzel` serif headers paired with clean `Plus Jakarta Sans` body copy.
- **Menu Categories**:
  - **Burgers**: The Cheddarloin Royal, Smoked Bacon Melt, Truffle Gold Reserve, Fiery Jalapeño, Classic Artisan.
  - **Sides**: Loaded Cheddar Bacon Fries, Rustic Golden Wedges, Molten Cheddar Bites, Truffle Herb Fries.
  - **Drinks**: Salted Caramel Velvet Shake, Sparkling Hibiscus Lemon Cooler, Mint Lime Sparkler, Botanical Cola.
  - **Meals & Combos**: The Royal Feast, Smokehouse Bacon Combo, Cheddar Lovers Duo for 2.

---

## ⚙️ Integrations & Configuration

### 1. Formspree Setup (Order Intake Without Email)
Formspree is used to record customer orders:
1. Create a free account at [https://formspree.io](https://formspree.io).
2. Create a new form (e.g. named `Cheddarloin Orders`).
3. Copy your Form ID (e.g. `xovjqqwq`).
4. **IMPORTANT — Disable Email Notifications**:
   - In your Formspree dashboard, click on your form.
   - Go to **Settings** &rarr; **Notifications**.
   - **Toggle OFF "Email notifications"** so no emails are sent.
5. In `js/checkout.js`, set your form endpoint:
   ```javascript
   const FORMSPREE_ORDER_ENDPOINT = "https://formspree.io/f/YOUR_FORM_ID";
   ```
   *(During initial development, a placeholder `YOUR_FORM_ID` safely simulates order intake without throwing errors).*

---

### 2. Telegram Bot Setup (Personal Chat Alerts)
New orders are sent to your personal Telegram account via Netlify serverless function (`netlify/functions/telegram-order.js`):

#### Step A: Create your Telegram Bot
1. Open Telegram and search for **`@BotFather`**.
2. Send `/newbot`.
3. Choose a name (e.g. `The Cheddarloin Orders`) and a username ending in `bot` (e.g. `cheddarloin_orders_bot`).
4. `@BotFather` will give you a **Bot Token** (e.g. `1234567890:ABCdefGHIjklMNOpqrSTUvwxYZ`).

#### Step B: Find your Personal Telegram Chat ID
1. Search for **`@userinfobot`** or **`@raw_data_bot`** on Telegram.
2. Press `/start`.
3. It will reply with your numerical **Id** (e.g. `987654321`).
4. Open a chat with your newly created bot (`@cheddarloin_orders_bot`) and click **Start** (or send `/start`) so the bot is authorized to message you.

#### Step C: Configure Netlify Environment Variables
In your Netlify dashboard:
- Go to: **Site configuration** &rarr; **Environment variables**.
- Add two variables:
  - `TELEGRAM_BOT_TOKEN` = `your_bot_token_from_botfather`
  - `TELEGRAM_CHAT_ID` = `your_personal_chat_id`

> **Security Note**: The Telegram Bot Token is **never** exposed in frontend code, HTML, or public git repos. It only exists securely inside the Netlify serverless execution environment.

---

### 3. Telegram Message Format
When an order is submitted, your Telegram bot delivers this exact message:

```text
THE CHEDDARLOIN — NEW ORDER

Order Reference: CHD-782901

Customer: Abebe Bikila
Phone: 0911234567

Order Type: Delivery

Address: Bole Atlas, near Edna Mall, Bldg 4

ITEMS:
2 x The Cheddarloin Royal — ETB 1,300
1 x Loaded Cheddar & Bacon Fries — ETB 240

TOTAL: ETB 1,640 (Includes ETB 100 delivery)

Instructions: Extra hot cheddar, no raw onions

Please contact the customer to confirm the order.
```

---

## 🔒 Server-Side Validation & Security

The Netlify function (`netlify/functions/telegram-order.js`) implements strict server-side safeguards:
- **Price Authority**: Prices and subtotals sent from the browser are **never trusted**. The server maintains its own authoritative catalog in ETB and recalculates all sums independently.
- **Product Verification**: Every incoming product ID and quantity is validated. Unknown IDs or invalid quantities are rejected.
- **HTML Sanitization**: Customer names, addresses, and instructions are escaped to prevent injection attacks against Telegram message parsers.
- **Payload Size Limits**: Rejects requests larger than 50KB to protect serverless memory.
- **Graceful Degradation**: If environment variables have not been configured yet, the function returns a clean status without crashing or blocking customer checkout.

---

## 🚀 Netlify Deployment Guide

1. **Connect Repository to Netlify**:
   - Push this codebase to your GitHub account.
   - Go to [https://app.netlify.com](https://app.netlify.com) &rarr; **Add new site** &rarr; **Import an existing project**.
   - Select your repository.

2. **Build Settings**:
   - **Publish directory**: `.` (or leave default, configured in `netlify.toml`)
   - **Functions directory**: `netlify/functions`
   - **Build command**: *(None required — purely static frontend + serverless functions)*

3. **Set Environment Variables**:
   - Go to **Site settings** &rarr; **Environment variables**.
   - Add `TELEGRAM_BOT_TOKEN` and `TELEGRAM_CHAT_ID`.

4. **Click Deploy**:
   - Netlify will instantly provide your live URL (e.g. `https://the-cheddarloin.netlify.app`).

---

## 🧪 Local Preview & Testing

To test the website locally:

```bash
# Using Node built-in serve or python:
npx serve .
# or
python -m http.server 8000
```

Open `http://localhost:8000` or `http://localhost:3000` in your browser:
- Test adding burgers, sides, and drinks to cart.
- Verify quantity adjustments and subtotal calculations in ETB.
- Test category filters and live menu search.
- Complete checkout with Delivery (verifying address required) and Pickup.
- View your order history under **My Orders**.
