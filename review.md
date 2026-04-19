# SustainChain — Project Review & Study Guide

> A teacher's reference document. Come back here whenever you are confused about how something works.
> Last reviewed: April 2026 | CakePHP 5.3 | PHP 8.2+

---

## Table of Contents

1. [What Is This Project?](#1-what-is-this-project)
2. [The Framework: CakePHP and MVC](#2-the-framework-cakephp-and-mvc)
3. [How a User Experiences the App](#3-how-a-user-experiences-the-app)
4. [What Happens in the Backend](#4-what-happens-in-the-backend)
5. [File Structure — Where Things Live](#5-file-structure--where-things-live)
6. [The Database](#6-the-database)
7. [Authentication — Login, Register, Password Reset](#7-authentication--login-register-password-reset)
8. [The Contact Form and CAPTCHA](#8-the-contact-form-and-captcha)
9. [Security Mechanisms](#9-security-mechanisms)
10. [Controllers at a Glance](#10-controllers-at-a-glance)
11. [Things That Are Currently Disabled](#11-things-that-are-currently-disabled)
12. [What Is Missing / Could Be Improved](#12-what-is-missing--could-be-improved)
13. [How to Run It Locally](#13-how-to-run-it-locally)
14. [Common Errors and Fixes](#14-common-errors-and-fixes)
15. [Key Vocabulary](#15-key-vocabulary)

---

## 1. What Is This Project?

**SustainChain** is a sustainable commerce web platform. The concept is a marketplace connecting buyers, sellers, manufacturers, and farmers who trade eco-friendly products.

Right now, the project is in **early development**. Think of it as a skeleton — the structure is there, but most of the marketplace features do not exist yet. What does exist:

- A **landing page** that describes the platform and its vision
- A **user account system** (register, log in, log out, change/reset password)
- A **contact/enquiry form** for visitors to reach the team
- A basic **user management panel** for administrators

---

## 2. The Framework: CakePHP and MVC

This project is built with **CakePHP 5** — a PHP framework (similar to Laravel, Symfony, or Rails for Ruby).

CakePHP follows the **MVC pattern** — Model, View, Controller. This is the single most important concept to understand before reading any code.

```
Browser Request
      |
      v
  ROUTER  (config/routes.php)
      |
      v
 CONTROLLER  (src/Controller/)
   /       \
  v         v
MODEL      VIEW
(Database) (HTML Template)
      \       /
       v     v
    Response to Browser
```

### Model (M)
- Lives in `src/Model/`
- Talks to the **database** — saves, loads, updates, deletes rows
- Validates data ("email must be unique", "password must be at least 8 chars")
- Two sub-parts:
  - **Table** (`src/Model/Table/`) — represents a whole database table
  - **Entity** (`src/Model/Entity/`) — represents a single row from that table

### View (V)
- Lives in `templates/`
- Pure **HTML + PHP** — only presentation, no business logic
- One template file per action (e.g. `templates/Auth/login.php` for the login page)

### Controller (C)
- Lives in `src/Controller/`
- The **traffic controller** — receives the HTTP request, calls the Model, passes data to the View
- Contains the application logic: "if password matches, start a session; if not, show an error"

---

## 3. How a User Experiences the App

Here is the complete user journey from first visit to using the app.

### Step 1 — Landing Page
- User visits the website root `/`
- They see the SustainChain homepage with sections describing features: Eco Marketplace, B2B Relationships, Farmers Direct, Impact Tracking, Trust & Verification
- There are call-to-action buttons like "Shop now" and "Join us"

### Step 2 — Register
- User clicks a CTA button → taken to `/auth/register`
- Fills in: **email address** and **password** (+ confirm password)
- Submits the form
- The app creates their account and redirects them to login

### Step 3 — Log In
- User visits `/auth/login`
- Enters email and password
- If correct → session is created → they are redirected to the landing page (or wherever they were trying to go before being redirected to login)
- If incorrect → they see an error flash message and stay on the login page

### Step 4 — Logged-In Experience
- The navigation bar shows their **email address** (visual confirmation they are logged in)
- They can access protected pages
- They can change their password at `/auth/change-password`
- They can log out at `/auth/logout`

### Step 5 — Contact Form (Any Visitor, Login Not Required)
- Any visitor can go to `/enquiries/add`
- Fills in: name, email, subject, message
- Must pass a **CAPTCHA** (Cloudflare Turnstile bot check)
- On success → message saved to database → redirected to landing page with a success message

### Step 6 — Forgot Password
- User visits `/auth/login` and clicks "Forgot password"
- Enters their email address
- App sends a **reset email** with a one-time link (contains a token called a "nonce")
- User clicks the link → taken to `/auth/reset-password?nonce=XXXX`
- Enters new password → saved → can log in with new password

---

## 4. What Happens in the Backend

This section explains what the server is doing while the user clicks around.

### When any URL is visited

```
1. The web server (Apache/Nginx) receives the HTTP request
2. Everything is forwarded to webroot/index.php
3. CakePHP boots up — config/bootstrap.php runs
4. The Router reads config/routes.php and picks the right Controller + Action
5. Middleware runs in a chain (security checks, authentication, CSRF, etc.)
6. The Controller Action method runs
7. The Action queries the Model (database) if needed
8. Data is passed to the View template
9. The template renders HTML
10. HTML is sent back to the browser as the HTTP response
```

### When a user logs in (detailed walkthrough)

```
1. Browser POSTs email + password to /auth/login
2. CsrfProtectionMiddleware checks the hidden CSRF token in the form
   → If missing or wrong: request rejected immediately (security)
3. AuthenticationMiddleware intercepts the request
4. It tries each Authenticator in order:
   a. Session Authenticator: Is there already a valid session?
      → Yes: user is already logged in, skip to step 8
      → No: continue to next authenticator
   b. Form Authenticator: Are there email + password POST fields?
      → Yes: query the database
5. Queries: SELECT * FROM users WHERE email = 'submitted@email.com'
6. Hashes the submitted password and compares it with the stored bcrypt hash
7. If match: a session is created (server-side); browser receives a session cookie
8. User is redirected to their intended page or the homepage
9. All future requests: the Session Authenticator finds the session → user stays logged in
```

### When a user registers

```
1. Browser POSTs email + password + password_confirm to /auth/register
2. AuthController::register() creates a new User entity from the POST data
3. The User entity's _setPassword() method automatically hashes the password
   using bcrypt BEFORE it reaches the database — plain text never touches the DB
4. UsersTable::validationDefault() validates:
   - Email is a valid email format
   - Email does not already exist in the users table
   - Password and password_confirm match
5. If validation fails → errors shown on the form, nothing saved
6. If validation passes → INSERT INTO users (email, password, ...) executed
7. Flash success message → redirect to login
```

### When the contact form is submitted

```
1. Browser POSTs form data to /enquiries/add
2. EnquiriesController::add() receives the data
3. Before saving ANYTHING, it calls TurnstileComponent::validateTurnstile()
   with the CAPTCHA token that Cloudflare put in the form
4. TurnstileComponent makes a server-to-server HTTP POST to Cloudflare's API:
   POST https://challenges.cloudflare.com/turnstile/v0/siteverify
   → Sends: secret key + token + optional user IP
5. Cloudflare responds: { "success": true } or { "success": false, "error-codes": [...] }
6. If CAPTCHA failed → flash error message, re-render form (nothing saved)
7. If CAPTCHA passed → validate form fields, save to enquiries table
8. Redirect to landing page with success flash message
```

---

## 5. File Structure — Where Things Live

```
team183-onboarding-project/
│
├── config/
│   ├── app.php              ← Main config (cache, sessions, email transport)
│   ├── app_local.php        ← YOUR LOCAL config (DB password, API keys) — never commit this
│   ├── bootstrap.php        ← Runs on every request — loads config, sets up connections
│   ├── routes.php           ← Maps URLs to Controller Actions
│   └── schema/
│       ├── authentication.sql   ← SQL to create the users table
│       └── enquiries.sql        ← SQL to create the enquiries table
│
├── src/
│   ├── Application.php          ← Registers middleware, configures authentication
│   ├── Controller/
│   │   ├── AppController.php        ← Base controller — all others extend this
│   │   ├── AuthController.php       ← Login, register, password management
│   │   ├── EnquiriesController.php  ← Contact form
│   │   ├── PagesController.php      ← Landing page and static pages
│   │   ├── UsersController.php      ← User admin panel
│   │   └── Component/
│   │       └── TurnstileComponent.php  ← Cloudflare CAPTCHA validation helper
│   │
│   └── Model/
│       ├── Table/
│       │   ├── UsersTable.php       ← Validation rules and query setup for users
│       │   └── EnquiriesTable.php   ← Validation rules and query setup for enquiries
│       ├── Entity/
│       │   ├── User.php             ← A single user row as a PHP object
│       │   └── Enquiry.php          ← A single enquiry row as a PHP object
│       └── Behavior/
│           └── CanAuthenticateBehavior.php  ← Reusable auth validation logic
│
├── templates/
│   ├── layout/
│   │   ├── default.php          ← Main page wrapper (nav bar, footer, flash messages)
│   │   └── login.php            ← Minimal wrapper for auth pages
│   ├── Auth/                    ← Login, register, forgot/reset/change password
│   ├── Enquiries/               ← Contact form and admin views
│   ├── Pages/                   ← Landing page
│   ├── Users/                   ← User management pages
│   ├── Error/                   ← 400 / 500 error pages
│   └── element/flash/           ← Reusable flash message snippets
│
├── webroot/                     ← Everything here is publicly accessible by browser
│   └── index.php                ← Entry point — ALL web requests start here
│
└── vendor/                      ← Third-party packages (do not edit; managed by Composer)
```

**Golden rule:** `config/app_local.php` contains secrets (DB password, API keys). It must **never** be committed to git.

---

## 6. The Database

Two tables currently exist.

### `users` table

| Column | Type | Notes |
|--------|------|-------|
| `id` | INT, auto-increment | Primary key |
| `email` | VARCHAR(255) | Unique. This is the "username" for login |
| `password` | VARCHAR(255) | Bcrypt hash — never plain text |
| `nonce` | VARCHAR(255) | One-time token for password reset |
| `nonce_expiry` | DATETIME | Token becomes invalid after this date (7 days) |
| `created` | DATETIME | Auto-set when row is created (TimestampBehavior) |
| `modified` | DATETIME | Auto-updated on every save (TimestampBehavior) |

### `enquiries` table

| Column | Type | Notes |
|--------|------|-------|
| `id` | INT, auto-increment | Primary key |
| `full_name` | VARCHAR(255) | Visitor's name |
| `email` | VARCHAR(255) | Visitor's email |
| `date` | TIMESTAMP | Auto-set to submission time |
| `subject` | TEXT | Subject line |
| `body` | TEXT | Full message |
| `email_sent` | TINYINT(1) | 0 = email not sent, 1 = sent (feature not yet implemented) |

---

## 7. Authentication — Login, Register, Password Reset

This app uses the **cakephp/authentication** plugin (v4.0).

### Key concepts

**Session Authenticator** — After a successful login, the user's identity is stored in the PHP session on the server. A session cookie is sent to the browser. Every subsequent request, CakePHP reads the session and knows who the user is — no re-entering of password needed.

**Form Authenticator** — On the login page specifically, CakePHP reads the `email` and `password` POST fields, queries the database, and compares bcrypt hashes.

**Unauthenticated Redirect** — If a user tries to visit a protected page (like `/users/index`) without being logged in, CakePHP automatically redirects them to `/auth/login?redirect=%2Fusers%2Findex`. After login, they are sent to where they originally wanted to go.

### How actions are protected

Inside each Controller's `initialize()` method, only certain actions are whitelisted as public:

```php
// Only these actions can be accessed without logging in
$this->Authentication->allowUnauthenticated(['login', 'register', 'forgetPassword']);
// Everything else in this controller requires authentication
```

### Password Reset — Step by Step

```
1. User clicks "Forgot Password" → /auth/forget-password
2. User enters their email address
3. Server generates a 128-character random token (the "nonce")
4. Stores the nonce in users.nonce column
5. Sets nonce_expiry = now + 7 days
6. Sends email: "Click this link → /auth/reset-password?nonce=TOKEN"

7. User clicks the link
8. App queries: SELECT * FROM users WHERE nonce = 'TOKEN'
9. Checks: is nonce_expiry in the future? If not → reject
10. If valid → show password reset form
11. User submits new password
12. Password is hashed and saved
13. nonce and nonce_expiry are cleared (token is now consumed, cannot be reused)

14. User can now log in with their new password
```

---

## 8. The Contact Form and CAPTCHA

The enquiry form at `/enquiries/add` is publicly accessible — no login required.

### Why CAPTCHA?

Without it, bots could flood the database with thousands of fake enquiries in seconds. The CAPTCHA proves the submission came from a human.

### How Cloudflare Turnstile Works Here

```
1. Page loads → JavaScript from Cloudflare loads a widget in the form
2. Cloudflare runs invisible checks in the browser
   (user behaviour, timing, browser fingerprint)
3. On success, Cloudflare injects a hidden field: cf-turnstile-response=TOKEN
4. User submits the form — this token is included in the POST data
5. Server (TurnstileComponent) sends the token to Cloudflare's API:
   POST https://challenges.cloudflare.com/turnstile/v0/siteverify
   Body: secret=SECRET_KEY&response=TOKEN&remoteip=USER_IP
6. Cloudflare verifies the token and responds:
   { "success": true }  ← proceed with saving
   { "success": false, "error-codes": ["invalid-input-response"] }  ← reject
7. Only if verified does the server save the enquiry to the database
```

Currently, **test keys** are configured in `config/app_local.php` — these always pass verification, so you can test locally without solving a real CAPTCHA challenge.

---

## 9. Security Mechanisms

| Mechanism | What It Protects Against | Where It Is |
|-----------|--------------------------|-------------|
| **CSRF Tokens** | Cross-Site Request Forgery (another website tricking your browser into submitting a form) | `CsrfProtectionMiddleware` in `src/Application.php` |
| **Bcrypt Password Hashing** | If the database is stolen, passwords cannot be reversed | `User::_setPassword()` in `src/Model/Entity/User.php` |
| **Authentication Middleware** | Unauthenticated users accessing protected pages | `src/Application.php` + each controller's `initialize()` |
| **Cloudflare Turnstile** | Bot form submissions | `src/Controller/Component/TurnstileComponent.php` |
| **Nonce Expiry** | Password reset links that work forever | `nonce_expiry` column, checked in `AuthController::resetPassword` |
| **Input Validation** | Bad or malicious data being saved to the database | `UsersTable` and `EnquiriesTable` validation rules |
| **Host Header Validation** | Host Header Injection attacks in production | `config/bootstrap.php` enforces `App.fullBaseUrl` |

---

## 10. Controllers at a Glance

### AuthController — `/auth/*`

| Action | URL | Public? | What it does |
|--------|-----|---------|--------------|
| `login` | `/auth/login` | Yes | Shows login form; authenticates user on POST |
| `register` | `/auth/register` | Yes | Shows registration form; creates account on POST |
| `logout` | `/auth/logout` | No | Destroys session; redirects to landing page |
| `forgetPassword` | `/auth/forget-password` | Yes | Sends password reset email |
| `resetPassword` | `/auth/reset-password?nonce=X` | Yes | Validates nonce; saves new password |
| `changePassword` | `/auth/change-password` | No | Lets logged-in user change their password |

### EnquiriesController — `/enquiries/*`

| Action | URL | Public? | What it does |
|--------|-----|---------|--------------|
| `add` | `/enquiries/add` | Yes | Contact form with CAPTCHA |
| `index` | `/enquiries` | No | **DISABLED** — redirects to landing page |
| `view` | `/enquiries/{id}` | No | **DISABLED** — redirects to landing page |
| `edit` | `/enquiries/{id}/edit` | No | **DISABLED** — redirects to landing page |
| `delete` | `/enquiries/{id}/delete` | No | **DISABLED** — redirects to landing page |

### PagesController — `/` and `/pages/*`

| Action | URL | Public? | What it does |
|--------|-----|---------|--------------|
| `landingPage` | `/` | Yes | Homepage |
| `display` | `/pages/{path}` | Yes | Renders static page templates |

### UsersController — `/users/*`

| Action | URL | Public? | What it does |
|--------|-----|---------|--------------|
| `index` | `/users` | No | Lists all users (paginated) |
| `view` | `/users/{id}` | No | Shows one user's details |
| `add` | `/users/add` | No | Admin adds a user |
| `edit` | `/users/{id}/edit` | No | Edit user details |
| `delete` | `/users/{id}/delete` | No | Delete a user |

---

## 11. Things That Are Currently Disabled

These features exist as code stubs but are intentionally turned off. Code comments say they will be re-enabled once admin accounts are implemented.

- **Enquiries index/view/edit/delete** — Any authenticated user hitting these URLs is redirected to the landing page. The idea is that only admins should see submitted enquiries.
- **Email notification on new enquiry** — The `email_sent` column exists in the database, but no code currently sends a notification email to the team when a new enquiry arrives.
- **Admin role system** — There is no `role` column on the users table. Currently all authenticated users are treated identically.

---

## 12. What Is Missing / Could Be Improved

These are honest observations for learning purposes.

### Missing Features

1. **Admin role / authorization** — Any logged-in user can access the Users admin panel (`/users`). There is no distinction between a regular user and an admin. This is a significant security gap.
2. **Email on new enquiry** — The `email_sent` flag exists but the sending logic is not implemented.
3. **The marketplace itself** — Products, listings, orders, sellers, categories — none of these exist yet. The landing page describes them but there is no backend for any of it.
4. **Profile management** — The `User` entity references `first_name`, `last_name`, `avatar`, `blog_articles` fields, but these columns do not exist in the database schema. If someone tried to use them, they would get an error.

### Code Quality Observations

1. `UsersController` has its own `login` action — this appears to be a legacy leftover. The actual login is in `AuthController`. The `UsersController::login` is redundant.
2. `config/app_local.php` contains real-looking credentials (`fit3047sustainchain`) — in a real project, this file must be in `.gitignore` and never committed.
3. `App.base` is hardcoded to `/team183-onboarding-project` in `app.php` — this will break if the project is ever deployed to a root domain.

### Potential Security Concerns

1. **No rate limiting on login** — a bot could attempt unlimited passwords (brute force attack).
2. **No email verification on registration** — anyone can register with an email address they do not own.
3. **No authorization on the user admin panel** — any registered user can view, edit, or delete other users' accounts.

---

## 13. How to Run It Locally

### What You Need

| Tool | Purpose |
|------|---------|
| PHP 8.2+ | The language the app runs on |
| Composer | PHP package manager (like npm for JavaScript) |
| MySQL | The database |
| MAMP / XAMPP | Easiest way to get PHP + MySQL on Mac/Windows |

### Steps

**1. Install dependencies**
```bash
composer install
```

**2. Set up your local config**

The `config/app_local.php` file contains your local database credentials. It is not in git. Create it from the example:
```bash
cp config/app_local.example.php config/app_local.php
```
Then open it and update the database username and password to match your local MySQL.

**3. Create the database and tables**
```sql
CREATE DATABASE sustainchain CHARACTER SET utf8mb4;
```
Then run the schema files:
```bash
mysql -u root -p sustainchain < config/schema/authentication.sql
mysql -u root -p sustainchain < config/schema/enquiries.sql
```

**4. Start the development server**
```bash
bin/cake server -p 8765
```
Then visit: `http://localhost:8765`

**5. Fix permissions if needed**
```bash
chmod -R 777 tmp/ logs/
```

---

## 14. Common Errors and Fixes

**"Database connection failed"**
Wrong credentials in `config/app_local.php`. Double-check `username`, `password`, `host`, and `database`.

**Missing classes / white screen**
You forgot `composer install`. Run it.

**"Missing config/app_local.php"**
Copy from example: `cp config/app_local.example.php config/app_local.php`

**CSRF error on form submission**
Clear browser cookies for localhost and try again.

**Permission denied on tmp/ or logs/**
```bash
chmod -R 777 tmp/ logs/
```

**Password reset emails not arriving**
Email is configured to send via localhost SMTP (port 25) in development. You need a real SMTP server configured in `config/app_local.php` for email to work.

**500 error / white screen**
Check the error log:
```bash
tail -f logs/error.log
```

---

## 15. Key Vocabulary

| Term | Plain-English Meaning |
|------|-----------------------|
| **MVC** | Model-View-Controller: a pattern that separates data, display, and logic into three layers |
| **Controller Action** | A PHP method that handles one specific URL request |
| **Entity** | A PHP object representing one row from a database table |
| **Table class** | A PHP class representing a whole database table — handles queries and validation |
| **Behavior** | Reusable logic that can be attached to multiple Table classes |
| **Component** | Reusable logic that can be attached to multiple Controllers |
| **Middleware** | Code that runs on every request before the Controller — used for auth, CSRF, body parsing |
| **Session** | Server-side storage that remembers who a user is between requests |
| **CSRF** | Cross-Site Request Forgery — an attack where another website tricks your browser into submitting a form |
| **Bcrypt** | A slow, one-way hashing algorithm used for passwords — cannot be reversed |
| **Nonce** | A one-time random token — used here for password reset links |
| **Flash message** | A one-time notification that appears after a redirect (e.g. "Login successful") |
| **CAPTCHA** | A challenge to prove the user is a human, not a bot |
| **Route** | The mapping between a URL and a Controller Action |
| **Pagination** | Splitting a large list into pages (e.g. 20 users per page) |
| **Middleware stack** | The ordered chain of middleware components that every request passes through |
| **Authenticator** | A strategy for verifying who the user is (Session, Form, JWT, etc.) |
| **Nonce expiry** | A datetime after which a one-time token is no longer valid |

---

*This document covers the full state of the project as of April 2026. Update it as the project evolves.*
