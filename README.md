# Restaurant POS

Aplikasi Point of Sale (POS) untuk restoran berbasis Laravel 12 + Vue 3.

## Test Accounts

| Role | Email | Password |
|------|-------|----------|
| Kasir | `kasir@example.com` | `password` |
| Pelayan | `pelayan@example.com` | `password` |

## Local Setup

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js & npm
- MySQL / MariaDB

### Installation

```bash
# 1. Clone repository
git clone <repo-url>
cd test-sismedika

# 2. Install PHP dependencies
composer install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env
# DB_DATABASE=test_sismedika
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Run migrations & seeders
php artisan migrate --seed

# 6. Install frontend dependencies
npm install

# 7. Run development server
composer dev
```

> `composer dev` will start Laravel server, queue worker, log viewer (Pail), and Vite dev server concurrently.

Alternatively, run them separately:
```bash
php artisan serve    # Backend (port 8000)
npm run dev          # Frontend (Vite)
```

### Production Build

```bash
npm run build
```

## Key Features

- **POS (Point of Sale)** — Select table, browse food menu with category filter & search, build order with quantity controls, and submit
- **Order Management** — View all orders with sort (newest/oldest) and filter by creator, update order status (`on progress` / `closed` / `cancelled`), edit order detail quantities
- **Food Management** — CRUD for food items with image upload, description, and category assignment
- **Food Categories** — CRUD for organizing food items into categories
- **Table Management** — View table availability with status indicators (available, occupied, reserved, inactive), change table status from POS
- **Receipt** — View and download order receipts as PDF
- **Authentication** — Login with Sanctum token-based auth, role-based access (Kasir, Pelayan)

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 12, Sanctum, Spatie Permission, Spatie Media Library, Spatie PDF |
| Frontend | Vue 3, Vue Router, Pinia, Tailwind CSS 4, Vite |
| Architecture | Atomic Design (atoms, molecules, organisms, templates), Service Layer pattern |
