# SustainChain

A sustainable-commerce marketplace connecting buyers, sellers, manufacturers, and farmers. Built by Team 183 for FIT3047 on CakePHP 5.

SustainChain pairs a public-facing eco-marketplace with role-aware dashboards for sellers and manufacturers, a Discover Innovators leaderboard that surfaces top manufacturers by recent sales, and an admin console for user moderation and listing management.

---

## Tech stack

| Layer | Choice |
|---|---|
| Framework | CakePHP 5.3 |
| PHP | 8.2+ |
| Database | MariaDB / MySQL (developed against MariaDB 11.8) |
| Auth | `cakephp/authentication` 4.x |
| Payments | Stripe (`stripe/stripe-php` 20.x) |
| AI chat | Google Gemini API (used by the support chatbot) |
| Sanitisation | `ezyang/htmlpurifier` |
| Migrations | `cakephp/migrations` |
| Local dev | XAMPP (Apache + MariaDB) |
| Production | cPanel-hosted shared environment |

---

## Features

### Marketplace
- Public product browse with keyword search, category and price-range filters, multi-key sorting (newest, name, price, discount).
- Product detail pages with sustainability filter tags, save-to-favourites, discount display.
- Cart and checkout via Stripe (card payments, test-mode by default).
- Per-product sales tracking through `order_items`.

### Roles
The platform supports five non-admin roles plus admins:

| Role | What they do |
|---|---|
| Buyer | Browse, save, purchase products. |
| Seller | List products, manage their own catalogue, fulfil orders. |
| Manufacturer | Same as seller, plus a public innovator profile (goals, values, profile image) and eligibility for the Discover Innovators page. |
| Farmer | Supply-side counterpart. Has a public detail page like a manufacturer. |
| Admin | Platform-wide moderation tools. |

Manufacturer and farmer signups require admin approval before they can log in.

### Discover Innovators
- `/innovators` — top 10 manufacturers ranked by units sold in the last 30 days. Vertical-rectangle cards in a 5×2 grid fitting the viewport.
- `/innovators/{id}` — public detail page showing the manufacturer's goals, values, profile image, and top 3 most-sold products.
- Homepage "Explore category" links route here.

### Admin
Admin dashboard at `/admin` exposes:

- Stats row (enquiry counts).
- Incoming enquiries list with read/resolved toggles.
- Sidebar **Users** widget (newest 5 non-admin signups, clickable).
- Sidebar **Approval Requests** panel (pending farmers + manufacturers).

The full management surfaces:

- **All users** (`/admin/users`) — paginated table with an "All" tab plus per-role filters (Buyers / Sellers / Manufacturers / Farmers), keyword search across name+email, sortable columns (signup date, listings count, name). Sidebar listing all admin accounts. Mobile-responsive (rows collapse to cards).
- **Edit user** (`/admin/users/edit/{id}`) — change name + role, send password reset link, deactivate / reactivate (cascades to product listings), or permanently delete (full data cascade with enquiry detachment for audit retention).
- **Approval queue** (`/admin/users/approvals`) — approve or reject pending manufacturer / farmer signups.
- **Product moderation** — Edit, Unlist / Relist (`unlist_reason = 'admin'`), and Delete buttons appear on every product view page when the viewer is an admin.

### Communication
- Public enquiry form with admin response.
- Site-wide AI chatbot powered by Gemini, accessible via the chat icon (bottom-right of every page).
- Transactional emails for password reset and signup confirmation (SMTP, see `.env`).

---

## Local setup

Prerequisites: PHP 8.2+, Composer, XAMPP (or equivalent Apache + MariaDB/MySQL).

```bash
# 1. Clone and install dependencies
git clone <repo-url> team183-app_fit3047
cd team183-app_fit3047
composer install

# 2. Environment
cp config/.env.example config/.env
# Then edit config/.env and fill in:
#   - APP_NAME, SECURITY_SALT
#   - DATABASE_URL (mysql://user:pass@localhost/sustainchain)
#   - STRIPE_PUBLISHABLE_KEY + STRIPE_SECRET_KEY (test keys are fine)
#   - GEMINI_API_KEY (optional, only needed for the chatbot)
#   - SMTP_PASSWORD (optional, only needed to send real email)

# 3. Database
# Create an empty schema in phpMyAdmin (default name: sustainchain)
# Then import every file in config/schema/ in this order:
#   authentication.sql
#   products.sql
#   favourites.sql
#   enquiries.sql
#   orders.sql
#   order_items.sql
#   sessions.sql
#   i18n.sql
```

If you start from an older database that's missing recent columns, see [`docs/deployment-schema.md`](#schema-deployment) or the consolidated deployment script your team lead maintains.

Once everything is in place, point Apache at `webroot/` (or use XAMPP's default htdocs) and visit:

```
http://localhost/team183-app_fit3047/
```

### Default Apache base path

The app is developed assuming it lives in a subdirectory (`/team183-app_fit3047/`). All internal links go through Cake's `$this->Url->build()` helper, so the base path is transparent — but if you mount it differently, you don't need to touch any templates.

---

## Schema deployment

DB schema isn't tracked in git, only in the `config/schema/*.sql` dumps. When deploying to a server or pulling onto another machine, the columns and tables added since the original dump need to be applied manually.

See [`docs/schema-deployment.sql`](docs/schema-deployment.sql) for the consolidated migration that adds all schema changes made on this branch. Highlights:

- `users.role` ENUM expanded to include buyer / seller / manufacturer / farmer
- `users.goals`, `users.business_values`, `users.profile` — manufacturer profile fields
- `users.is_active` — account activation flag (admin moderation + pending-approval state)
- `products.is_listed`, `products.unlist_reason` — listing-state flags for admin moderation
- `order_items` table — per-product sales tracking for the Discover Innovators ranking

If that doc doesn't exist yet, regenerate it from your latest schema-changes notes before each production push.

---

## Branch strategy

The team uses a four-tier flow:

```
feat/{name}  →  rev  →  dev  →  prod  ↑ main (do not touch)
```

| Branch | Purpose |
|---|---|
| `feat/*` | Active development on a single feature. Branched off `dev`. |
| `rev` | Code under review. Merge `feat/*` here for inspection. |
| `dev` | Stable integration. All passing reviewed code merges here. |
| `prod` | What's running on the live cPanel server. Promoted from `dev`. |
| `main` | Project root. Not modified during the iteration. |

When opening a PR, target `rev`. After review and approval, the reviewer merges to `dev`. `prod` is promoted by the team lead at release time.

---

## Project layout (key folders)

```
config/
  routes.php              # All URL routing
  schema/                 # SQL dumps for fresh installs
  .env.example            # Template — copy to .env and fill in keys

src/Controller/
  Admin/                  # Admin-prefixed controllers (Users, Enquiries, Dashboard)
  AuthController.php      # Register, login, edit profile, password reset
  CartController.php      # Session-backed cart
  ChatController.php      # Gemini chatbot proxy
  InnovatorsController.php  # Discover Innovators landing + detail
  PaymentsController.php  # Stripe checkout + order persistence
  ProductsController.php  # Marketplace browse, add/edit/delete, save toggle

src/Model/
  Entity/                 # User, Product, Order, OrderItem, Favourite, Enquiry, ...
  Table/                  # Corresponding *Table.php with associations + validators

templates/
  Admin/                  # Admin dashboard + user management pages
  Auth/                   # Register / login / my-account / edit / password reset
  Innovators/             # Discover Innovators landing + detail
  Products/               # Marketplace, product detail, my-listings, add/edit
  Cart/, Payments/        # Cart, checkout, success
  element/                # Reusable partials (product_card, top_products, flash)
  layout/                 # default.php (main layout w/ nav and footer)

webroot/
  img/products/           # Product images (uploaded)
  img/profiles/           # Manufacturer profile images (uploaded)
  css/, js/, font/        # Static assets
  index.php               # Front controller — Apache document root
```

---

## Notable third-party integrations

### Stripe (test mode)
Keys in `config/.env` as `STRIPE_PUBLISHABLE_KEY` and `STRIPE_SECRET_KEY`. The checkout uses Stripe Payment Intents with automatic payment methods. Production needs live keys.

Test card numbers: `4242 4242 4242 4242` (success), any future expiry, any 3-digit CVC.

### Google Gemini (chatbot)
Key in `config/.env` as `GEMINI_API_KEY`. If unset, the chatbot icon still renders but conversations error gracefully.

### SMTP (transactional email)
Configured via Cake's standard mailer transport (see `config/app.php`). Local dev usually leaves SMTP unconfigured; the controllers will catch the delivery error and continue rather than crashing.

---

## Common deployment gotchas

### "Missing Controller" after pulling new code on cPanel

Symptom: visiting a route Cake says doesn't exist a controller that's clearly in `src/Controller/`. Cause: production composer install was run with `--optimize-autoloader`, freezing the classmap to what existed at install time.

Fix:
```bash
composer dump-autoload
rm -rf tmp/cache/models/* tmp/cache/persistent/*
```

Or, in cPanel File Manager, edit `vendor/composer/autoload_classmap.php` and add the missing `App\Controller\XController` entry pointing at the file.

### "Unknown column" SQL errors

Database is missing columns that newer code expects. Run the consolidated schema deployment SQL on the live database (see "Schema deployment" above).

### Pending farmer / manufacturer can't log in

By design — these roles require admin approval. As an admin, visit `/admin/users/approvals` and click Approve. Until then the user sees "Your account is awaiting admin approval."

### Discover Innovators page shows the empty state

`order_items` is empty or has no rows in the last 30 days. Either:
- Run through Stripe checkout with a manufacturer-owned product in cart, or
- Insert seed `order_items` rows directly in phpMyAdmin (see team docs).

---

## Team 183

| Member | Focus area |
|---|---|
| Jason Kariuki | Admin management, Discover Innovators, schema design |
| Naveen Sellathurai | User roles, register flow, content polish |
| Other team members | Cart / payments / chat / docs |

See the iteration reports under `docs/` for design rationale and acceptance criteria.

---

## License

This is a coursework project for FIT3047 at Monash University. Not licensed for redistribution.
