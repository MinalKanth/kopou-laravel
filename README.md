<div align="center">

# 🌿 KOPOU

### Authentic Assam, Delivered Across India

*A full-stack e-commerce platform celebrating the heritage, craft, and flavor of Assam — from hand-picked tea to handloom silk.*

[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Vite](https://img.shields.io/badge/Vite-6-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev)
[![Razorpay](https://img.shields.io/badge/Razorpay-Integrated-0C2451?style=for-the-badge&logo=razorpay&logoColor=white)](https://razorpay.com)
[![License](https://img.shields.io/badge/License-Proprietary-lightgrey?style=for-the-badge)]()

</div>

---

## ✨ Overview

**KOPOU** (Assamese for *flower*) is a premium storefront built to bring Assam's finest — Pat silk dupattas, orthodox tea, bamboo handicrafts, and more — to customers across India. It's a complete Laravel application: a cinematic, animation-driven storefront on the front end, and a real transactional backbone (cart, checkout, payments, order management, admin operations) underneath.

This isn't a template — every page is server-rendered from a real database, every add-to-cart click persists, and every order that gets paid for is a real row in a real `orders` table.

---

## 🧭 Table of Contents

- [Feature Highlights](#-feature-highlights)
- [Tech Stack](#-tech-stack)
- [Architecture at a Glance](#-architecture-at-a-glance)
- [Getting Started](#-getting-started)
- [Environment Variables](#-environment-variables)
- [Default Accounts](#-default-accounts)
- [Project Structure](#-project-structure)
- [Available Commands](#-available-commands)
- [Roadmap](#-roadmap)

---

## 🎯 Feature Highlights

### 🛍️ Storefront
- Cinematic, GSAP-animated homepage with parallax category rails, scroll reveals, and a custom cursor
- Full product catalog with category browsing, quick view, and image galleries
- Product detail pages with **live variant selection**, quantity stepper, and delivery estimates
- Search overlay and responsive mobile navigation

### ❤️ Wishlist
- One-click save, persisted server-side per session/account — not just a cosmetic toggle
- Wishlist state hydrates correctly across page loads and devices once logged in

### 🛒 Cart
- Real database-backed cart — works for guests (session-based) *and* signed-in users (account-based)
- **Guest-to-account merge**: anything added before logging in automatically folds into your account cart on sign-in, nothing is ever lost
- Live quantity controls, line-item removal, and running subtotal — all synced to the server

### 💳 Checkout & Payments
- Full checkout flow: saved address selection or new address entry, order summary, and secure payment
- **Razorpay** integration — order creation, hosted payment modal, and server-side signature verification (HMAC-SHA256) before anything is marked paid
- Atomic inventory reservation at checkout, released automatically if payment fails or is cancelled — **stock never leaks**
- Order confirmation page + full order history in the customer account area

### 👤 Customer Accounts
- Registration, login, password security settings, profile management
- Order history with per-order detail (items, pricing, shipping address)
- Multiple saved addresses with a default address flag

### 🛠️ Admin Panel
- Dedicated `/admin` control center, gated by authentication + an `is_admin` role flag
- **Dashboard** — live revenue, order, product, and user stats, plus a low-stock watchlist
- **Product management** — full CRUD, pricing, stock levels, images, categories
- **Category management** — create, edit, activate/deactivate, delete
- **Order management** — inspect any order and move it through its fulfillment lifecycle
- **User management** — search, suspend/reactivate accounts, grant or revoke admin access

### 🔒 Security
- CSRF protection on every state-changing request
- Session regeneration on login/registration to prevent session fixation
- Strong password policy (length, character mix, breach-checked via `Password::uncompromised()`)
- Rate-limited authentication and sensitive account actions
- Payment signatures verified server-side — the browser is never trusted to confirm its own payment

---

## 🧱 Tech Stack

| Layer | Technology |
|---|---|
| Backend Framework | Laravel 11 (PHP 8.2+) |
| Database | MySQL 8 |
| Frontend Tooling | Vite 6, vanilla JS + GSAP |
| Styling | Custom CSS design system (no framework lock-in) |
| Payments | Razorpay (REST API, Orders + signature verification) |
| Auth | Laravel's native session-based auth |

---

## 🏗️ Architecture at a Glance

```
Guest visitor                Logged-in customer
     │                              │
     ▼                              ▼
 Session Cart  ──── login/register ───►  Account Cart
     │                                        │
     └──────────────► Checkout ◄──────────────┘
                          │
              ┌───────────┴───────────┐
              ▼                       ▼
       Reserve Inventory      Create Razorpay Order
              │                       │
              └───────────┬───────────┘
                           ▼
                  Customer pays (Razorpay modal)
                           │
                           ▼
              Server verifies signature (HMAC)
                     │            │
                 ✅ Valid      ❌ Invalid/Cancelled
                     │            │
              Order → Paid   Release Inventory
              Cart cleared   Order → Cancelled
```

Every order lifecycle transition — reservation, payment, fulfillment — happens inside a database transaction, so a failure at any step can't leave stock or orders in an inconsistent state.

---

## 🚀 Getting Started

### Prerequisites
- PHP `8.2+` with the `mysql`, `mbstring`, `xml`, and `curl` extensions
- Composer
- Node.js `18+` and npm
- MySQL `8.0+`
- A [Razorpay](https://dashboard.razorpay.com/app/keys) account (test keys are free)

### Installation

```bash
# 1. Install PHP dependencies
composer install

# 2. Install & build frontend assets
npm install
npm run build

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# 4. Set your database + Razorpay credentials in .env (see below)

# 5. Run migrations and seed sample data
php artisan migrate --seed

# 6. Launch
php artisan serve
```

Visit **`http://127.0.0.1:8000`** — the storefront, cart, checkout, and admin panel are all live.

> 💡 **Frontend changes?** Re-run `npm run build` after editing anything in `resources/js` or `resources/css`. For active development, use `npm run dev` instead for hot reload.

---

## 🔐 Environment Variables

Beyond the standard Laravel `DB_*` and `APP_*` variables, KOPOU needs:

```env
# Razorpay — get test keys from https://dashboard.razorpay.com/app/keys
RAZORPAY_KEY_ID=
RAZORPAY_KEY_SECRET=
```

Checkout will return a clear, non-fatal error if these are left blank — the app degrades gracefully rather than crashing.

---

## 👥 Default Accounts

| Role | Email | Password |
|---|---|---|
| Admin | `admin@kopou.test` | `password` |

> ⚠️ **Change this password immediately** in any environment beyond local development. Regular customer accounts aren't pre-seeded — register one via `/register`, or promote any existing user to admin from **Admin → Users → Make Admin**.

---

## 📁 Project Structure

```
kopou/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Dashboard, Products, Categories, Orders, Users
│   │   ├── Account/        # Profile, Security, Orders, Addresses
│   │   ├── CartController.php
│   │   └── CheckoutController.php
│   ├── Models/              # Product, Order, Cart, Address, User, ...
│   └── Services/
│       ├── CartService.php      # guest/account cart resolution + merge
│       └── RazorpayService.php  # payment gateway integration
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── css/app.css          # full custom design system
│   ├── js/app.js            # storefront interactions, cart, checkout
│   └── views/
│       ├── admin/           # admin panel views
│       ├── account/         # customer account views
│       ├── checkout/
│       └── products/
└── routes/
    ├── web.php
    ├── cart.php
    ├── checkout.php
    └── admin.php
```

---

## 🧰 Available Commands

| Command | Purpose |
|---|---|
| `php artisan migrate` | Run pending migrations |
| `php artisan db:seed` | Seed categories, products, and admin user |
| `php artisan db:seed --class=Database\Seeders\AdminUserSeeder` | Seed/reset just the admin account |
| `npm run dev` | Start Vite in watch/hot-reload mode |
| `npm run build` | Build production frontend assets |
| `php artisan serve` | Start the local dev server |

---

## 🗺️ Roadmap

- [ ] Razorpay webhook handler for payment confirmations initiated outside the browser session
- [ ] Configurable shipping rates (currently free shipping across the board)
- [ ] Real PIN-code delivery serviceability check (courier/zone API)
- [ ] Transactional order-confirmation emails
- [ ] Product reviews & ratings submission
- [ ] Coupon / promo code engine

---

<div align="center">

*Crafted with care for the weavers, growers, and artisans of Assam.* 🍃

</div>

Admin login:

Email: admin@kopou.test
Password: password

