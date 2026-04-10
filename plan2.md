# Plan 2 — Marketplace Product Listing (US 7.1)

> **User Stories covered:**
> - US 7.1: As a buyer, I want to view available products so that I can explore what is offered on the platform.
> - AC 7.1.1: Each product shows its name, price, and image.
> - AC 7.1.2: When the marketplace page loads, a list of available products is displayed.

---

## What Already Exists (Do Not Rebuild)

| What | File | Status |
|------|------|--------|
| Products database table | `config/schema/products.sql` | Done |
| ProductsTable model | `src/Model/Table/ProductsTable.php` | Done |
| Product entity | `src/Model/Entity/Product.php` | Done (has `image_url` field) |
| ProductsController with index() | `src/Controller/ProductsController.php` | Done |
| Products listing template | `templates/Products/index.php` | Exists — needs image added |
| Route `/products` | `config/routes.php` | Done |
| 5 test products in database | phpMyAdmin | Done |

## What Is Missing

| What | Why it matters |
|------|---------------|
| Images for each product | AC 7.1.1 requires an image on each product card |
| `image_url` not used in template | The field exists but is never displayed |
| Nav "Marketplace" links to wrong place | Points to `#marketplace` anchor, should go to `/products` |
| Products have no image URLs seeded | The `image_url` column in your database rows is currently NULL |

---

## Phases

| Phase | What You Build | AC it delivers |
|-------|---------------|----------------|
| 1 | Add placeholder images to webroot and update seeded products | AC 7.1.1 (image data) |
| 2 | Create `marketplace.css` matching the project's design system | AC 7.1.1 (styled cards) |
| 3 | Rewrite the products template using the new CSS classes | AC 7.1.1 (image + name + price displayed) |
| 4 | Fix the nav "Marketplace" link to go to `/products` | AC 7.1.2 |
| 5 | Final checks — verify both ACs together | AC 7.1.1 + AC 7.1.2 |

---

---

# Phase 1 — Give Products an Image

## What You Learn
- How CakePHP serves static files (images, CSS, JS) from the `webroot/` folder
- How to update existing database rows using SQL UPDATE
- Why the `webroot/` folder is special

## Background — How Images Work in CakePHP

The `webroot/` folder is the only folder the browser can access directly. Everything inside it is publicly reachable.

```
webroot/
  img/
    products/
      bamboo-bottle.jpg   ← browser reaches this at /img/products/bamboo-bottle.jpg
```

When you store `image_url` in the database, you store the path relative to webroot — for example `/img/products/bamboo-bottle.jpg`. Then in the HTML template you write:

```php
<img src="<?= h($product->image_url) ?>" alt="<?= h($product->name) ?>">
```

The browser fetches the image directly from the webroot folder.

---

## Step 1.1 — Create a products image folder

In your terminal (or Finder), create this folder:

```
webroot/img/products/
```

Via terminal:
```bash
mkdir -p /Users/naman/team183-onboarding-project/webroot/img/products
```

---

## Step 1.2 — Add product images

You need one image per product. For now, use any free images you like.

**Easiest option — download free eco product images:**

Go to https://unsplash.com and search for:
- bamboo bottle
- cotton tote bag
- solar charger
- beeswax wrap
- notebook

Download one image per product and save them into `webroot/img/products/` with these exact filenames:
```
bamboo-bottle.jpg
cotton-tote.jpg
solar-charger.jpg
beeswax-wrap.jpg
recycled-notebook.jpg
```

> **Tip:** Keep images under 500KB each. Large images will slow the page down.

**Alternative — use a placeholder service for now:**

If you just want to get the code working first without downloading images, you can use a URL like:
```
https://placehold.co/400x300?text=Product
```
This is an external URL so you do not need local files. You can replace with real images later.

---

## Step 1.3 — Update the database rows to have image_url values

Go to phpMyAdmin → select `project_3047` → click the **SQL** tab.

**If you used local images (Option A):**

```sql
UPDATE products SET image_url = '/img/products/bamboo-bottle.jpg'    WHERE name = 'Bamboo Water Bottle';
UPDATE products SET image_url = '/img/products/cotton-tote.jpg'      WHERE name = 'Organic Cotton Tote';
UPDATE products SET image_url = '/img/products/solar-charger.jpg'    WHERE name = 'Solar Phone Charger';
UPDATE products SET image_url = '/img/products/beeswax-wrap.jpg'     WHERE name = 'Beeswax Wrap Set';
UPDATE products SET image_url = '/img/products/recycled-notebook.jpg' WHERE name = 'Recycled Notebook';
```

**If you used placeholder URLs (Option B):**

```sql
UPDATE products SET image_url = 'https://placehold.co/400x300?text=Bamboo+Bottle'   WHERE name = 'Bamboo Water Bottle';
UPDATE products SET image_url = 'https://placehold.co/400x300?text=Cotton+Tote'     WHERE name = 'Organic Cotton Tote';
UPDATE products SET image_url = 'https://placehold.co/400x300?text=Solar+Charger'   WHERE name = 'Solar Phone Charger';
UPDATE products SET image_url = 'https://placehold.co/400x300?text=Beeswax+Wrap'    WHERE name = 'Beeswax Wrap Set';
UPDATE products SET image_url = 'https://placehold.co/400x300?text=Notebook'        WHERE name = 'Recycled Notebook';
```

---

## Phase 1 Tests — Check Before Moving On

Go to phpMyAdmin → click the `products` table → **Browse** tab.

- [ ] The `image_url` column should now have a value for all 5 products (not NULL)
- [ ] Each value should start with `/img/products/...` or `https://placehold.co/...`
- [ ] If using local images: go to `http://localhost:8765/img/products/bamboo-bottle.jpg` in your browser — the image should load directly

---

---

# Phase 2 — Create `marketplace.css`

## What You Learn
- How to add a new CSS file to a CakePHP project
- How the project's design system works (CSS variables, fonts, tokens)
- How to load a CSS file only on one specific page (not globally)

## Background — The Project's Design System

Before writing any CSS you need to understand the rules already in `webroot/css/app.css`. The whole site is built on these:

**CSS Variables (colour palette)**
```css
--g0: #0d1f14   /* darkest forest green  — used for dark backgrounds */
--g1: #163824   /* dark forest           — card backgrounds */
--g2: #1f5235   /* forest green          — hover states */
--g3: #2e7d52   /* mid green             — prices, accents */
--g4: #4daa7a   /* light green           — subtle accents */
--g5: #a3cfba   /* pale green            — borders on dark bg */
--g6: #d9ede4   /* very pale green       — light card bg tint */
--e1: #c8e840   /* lime / yellow-green   — primary CTA colour */
--s0: #fff8f0   /* off-white             — page background */
--s1: #f2ebe0   /* warm cream            — section backgrounds */
--s2: #e2d5c3   /* warm tan              — borders on light bg */
--muted: #556652  /* muted green-grey    — body text / descriptions */
--ink:   #0d1209  /* near-black          — headings */
```

**Fonts**
- `Fraunces` (serif) — display headings and prices
- `Cabinet Grotesk` / `Satoshi` (sans-serif) — all UI text, labels, buttons

**Border radius tokens**
- `--r8: 8px`, `--r16: 16px`, `--r24: 24px`, `--r999: 999px` (pill shape)

**Animation**
- `--ease-spring: cubic-bezier(0.34, 1.56, 0.64, 1)` — used on all hover transforms

These are the same tokens you must use in marketplace.css so everything looks consistent.

---

## Step 2.1 — Create the CSS file

Create a new file: `webroot/css/marketplace.css`

Paste the entire contents below:

```css
/* marketplace.css — Products listing page
   Uses the same CSS variables and design tokens as app.css
   --------------------------------------------------------- */

/* ── Page header ─────────────────────────────────────────── */
.marketplace-header {
    background: var(--g0);
    padding: 7rem 2.5rem 4rem;
    position: relative;
    overflow: hidden;
    text-align: center;
}

/* Subtle green glow — same radial-gradient technique as the hero section */
.marketplace-header::before {
    content: "";
    position: absolute;
    inset: 0;
    pointer-events: none;
    background: radial-gradient(
        ellipse 60% 80% at 50% 0%,
        rgba(46, 125, 82, 0.25) 0%,
        transparent 65%
    );
}

.marketplace-header-inner {
    position: relative;
    z-index: 2;
    max-width: 640px;
    margin: 0 auto;
}

/* Reuses .section-tag from app.css but forces lime colour for dark background */
.marketplace-header .section-tag {
    display: inline-block;
    color: var(--e1);
    margin-bottom: 0.75rem;
}

/* Same font/size pattern as .hero-title in app.css */
.marketplace-title {
    font-family: "Fraunces", serif;
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 700;
    letter-spacing: -0.02em;
    line-height: 1;
    color: var(--white);
    margin-bottom: 1rem;
}

/* Italic accent — same pattern as .hero-title em */
.marketplace-title em {
    font-style: italic;
    font-weight: 300;
    color: var(--e1);
}

.marketplace-subtitle {
    font-size: 1rem;
    line-height: 1.75;
    color: rgba(255, 255, 255, 0.5);
}

/* ── Main content area ───────────────────────────────────── */
.marketplace-body {
    max-width: 1180px;
    margin: 0 auto;
    padding: 4rem 2.5rem 6rem;
}

/* ── Empty state ─────────────────────────────────────────── */
.marketplace-empty {
    text-align: center;
    padding: 5rem 2rem;
    color: var(--muted);
}

.marketplace-empty p {
    font-size: 1.1rem;
    margin-bottom: 1.5rem;
}

/* ── Product grid ────────────────────────────────────────── */
.product-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

/* ── Product card ────────────────────────────────────────── */
/* Mirrors .inno-card from app.css but on a light background */
.product-card {
    background: var(--white);
    border: 1px solid var(--s2);
    border-radius: var(--r24);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition:
        transform 0.25s var(--ease-spring),
        box-shadow 0.25s;
}

.product-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 60px rgba(13, 31, 20, 0.1);
}

/* ── Product image ───────────────────────────────────────── */
.product-img-wrap {
    position: relative;
    overflow: hidden;
    aspect-ratio: 4 / 3;   /* all images the same proportion */
    background: var(--s1);  /* cream placeholder while image loads */
}

.product-img {
    width: 100%;
    height: 100%;
    object-fit: cover;      /* crops to fill — never squishes or stretches */
    display: block;
    transition: transform 0.4s ease;
}

/* Subtle zoom on hover */
.product-card:hover .product-img {
    transform: scale(1.04);
}

/* ── Card body ───────────────────────────────────────────── */
.product-card-body {
    padding: 1.5rem 1.5rem 0.75rem;
    display: flex;
    flex-direction: column;
    flex: 1;
    gap: 0.4rem;
}

/* Tiny uppercase label — same pattern as .t-label in app.css */
.product-category {
    font-family: "Cabinet Grotesk", "Satoshi", sans-serif;
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    color: var(--g3);
}

.product-name {
    font-family: "Cabinet Grotesk", "Satoshi", sans-serif;
    font-size: 1.05rem;
    font-weight: 700;
    letter-spacing: -0.02em;
    color: var(--g0);
    line-height: 1.3;
}

/* Price uses Fraunces serif — same as .stat-num in app.css */
.product-price {
    font-family: "Fraunces", serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--g3);
    letter-spacing: -0.03em;
    line-height: 1;
    margin-top: 0.25rem;
}

.product-desc {
    font-size: 0.875rem;
    line-height: 1.65;
    color: var(--muted);
    flex: 1;
    margin-top: 0.25rem;
}

/* ── Card CTA button ─────────────────────────────────────── */
.product-card-footer {
    padding: 0.75rem 1.5rem 1.5rem;
}

/* Dark pill button — matches the site's .btn style */
.btn-product {
    display: block;
    text-align: center;
    padding: 0.65rem 1.25rem;
    background: var(--g0);
    color: var(--white);
    border-radius: var(--r999);
    font-family: "Cabinet Grotesk", "Satoshi", sans-serif;
    font-weight: 700;
    font-size: 0.85rem;
    letter-spacing: -0.01em;
    text-decoration: none;
    transition:
        background 0.18s,
        transform 0.18s var(--ease-spring),
        box-shadow 0.18s;
}

.btn-product:hover {
    background: var(--g2);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(13, 31, 20, 0.2);
}

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 1024px) {
    .product-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 640px) {
    .marketplace-header {
        padding: 5rem 1.5rem 3rem;
    }
    .marketplace-body {
        padding: 2.5rem 1.25rem 4rem;
    }
    .product-grid {
        grid-template-columns: 1fr;
    }
}
```

---

## Step 2.2 — Understand how to load a CSS file on one page only

You do NOT want marketplace.css loading on every page — only on the products page. CakePHP lets you do this from inside a template using the Html helper.

At the very top of `templates/Products/index.php`, add this one line:

```php
<?php $this->Html->css('marketplace', ['block' => true]); ?>
```

**What this does:**
- `'marketplace'` — CakePHP automatically looks for `webroot/css/marketplace.css`
- `['block' => true]` — instead of printing the `<link>` tag right here, it buffers it and injects it into the `<head>` via the layout's `$this->fetch('meta')` block
- The result in the browser's HTML will be: `<link rel="stylesheet" href="/css/marketplace.css">`

---

## Phase 2 Tests — Check Before Moving On

- [ ] File `webroot/css/marketplace.css` exists
- [ ] Visit `http://localhost:8765/products` — open browser DevTools (F12) → Network tab → filter by CSS
- [ ] You should see `marketplace.css` being loaded on the products page
- [ ] Visit `http://localhost:8765/` (the landing page) — `marketplace.css` should NOT be in the network requests (it is page-specific)

---

---

# Phase 3 — Rewrite the Products Template Using the CSS Classes

## What You Learn
- How to replace messy inline styles with clean CSS class names
- How to structure an HTML template to match the design system
- How `$this->Html->css()` with `block => true` works in practice

## Background — Why Class Names Beat Inline Styles

The current template has things like:
```html
<div style="border: 1px solid #ddd; border-radius: 8px; padding: 16px;">
```

This is hard to read, impossible to reuse, and uses hardcoded colours that do not match the design system. After this phase it becomes:
```html
<div class="product-card">
```

Clean, readable, and uses the CSS you wrote in Phase 2.

---

## Step 3.1 — Replace `templates/Products/index.php`

Open `templates/Products/index.php` and **replace the entire file** with this:

```php
<?php
// Load marketplace.css only on this page — injected into <head> by the layout
$this->Html->css('marketplace', ['block' => true]);
?>

<!-- Page header — dark green banner, same style as the landing page hero -->
<div class="marketplace-header">
    <div class="marketplace-header-inner">
        <span class="t-label section-tag">SustainChain</span>
        <h1 class="marketplace-title t-display">
            The <em>sustainable</em> marketplace
        </h1>
        <p class="marketplace-subtitle">
            Browse products from verified eco-friendly sellers, farmers, and manufacturers.
        </p>
    </div>
</div>

<!-- Product grid -->
<div class="marketplace-body">

    <?php if ($products->isEmpty()): ?>
        <div class="marketplace-empty">
            <p>No products available yet. Check back soon!</p>
            <a href="/" class="btn btn-lime">Back to Home</a>
        </div>

    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">

                    <!-- Image -->
                    <div class="product-img-wrap">
                        <?php
                        // Use the product image if set, otherwise a themed placeholder
                        $imageSrc = !empty($product->image_url)
                            ? $product->image_url
                            : 'https://placehold.co/400x300/d9ede4/2e7d52?text=No+Image';
                        ?>
                        <img
                            class="product-img"
                            src="<?= h($imageSrc) ?>"
                            alt="<?= h($product->name) ?>"
                        >
                    </div>

                    <!-- Info -->
                    <div class="product-card-body">
                        <span class="product-category"><?= h($product->category) ?></span>
                        <h3 class="product-name"><?= h($product->name) ?></h3>
                        <p class="product-price">$<?= h(number_format($product->price, 2)) ?></p>
                        <p class="product-desc">
                            <?= h(mb_strimwidth($product->description, 0, 90, '...')) ?>
                        </p>
                    </div>

                    <!-- CTA -->
                    <div class="product-card-footer">
                        <a href="/products/<?= $product->id ?>" class="btn-product">
                            View Product →
                        </a>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>
```

**What changed from the old template:**
- All `style="..."` inline attributes removed — replaced with class names
- Added a proper page header (`marketplace-header`) matching the site's dark-green hero style
- Added `t-display` and `t-label` classes (defined in app.css) for consistent typography
- The placeholder image uses `d9ede4/2e7d52` — the project's actual pale green and mid-green colours
- `number_format($product->price, 2)` ensures prices always show 2 decimal places
- `mb_strimwidth(..., 0, 90, '...')` truncates long descriptions safely (mb_ handles multi-byte characters like emoji)
- `h()` wrapping every variable — prevents XSS attacks

---

## Phase 3 Tests — Check Before Moving On

- [ ] Visit `http://localhost:8765/products`
- [ ] The page has a **dark green header** with "The sustainable marketplace" heading (lime italic word)
- [ ] Below the header: a 3-column grid of product cards on a cream/off-white background
- [ ] Each card has a **rounded image** at the top (4:3 ratio, fills uniformly)
- [ ] Each card shows: category label (small, uppercase, green) → name → price (large, Fraunces font) → short description
- [ ] Each card has a dark pill "View Product →" button that goes slightly lime on hover
- [ ] Cards lift up slightly when you hover over them
- [ ] The page background colour is `--s0` (off-white/cream) — matches the rest of the site
- [ ] On a narrow browser window (< 640px): cards stack to a single column

---

---

# Phase 4 — Fix the Navigation to Link to the Marketplace

## What You Learn
- How the CakePHP layout template works
- How `$this->Html->link()` generates links
- How to use URL arrays vs plain strings

## Background

Currently the nav bar has this link:
```html
<li><a href="#marketplace">Marketplace</a></li>
```

The `#marketplace` is an anchor link — it scrolls to a section on the landing page. It does **not** take the user to the products listing page. For AC 7.1.2 to be satisfied, "Marketplace" in the nav must go to `/products`.

---

## Step 3.1 — Update the nav link in the layout

Open `templates/layout/default.php`.

Find this line (around line 30):
```php
<li><a href="#marketplace">Marketplace</a></li>
```

Replace it with:
```php
<li><?= $this->Html->link('Marketplace', ['controller' => 'Products', 'action' => 'index']) ?></li>
```

**What `$this->Html->link()` does:**
- First argument: the visible text (`Marketplace`)
- Second argument: an array telling CakePHP which controller and action to link to
- CakePHP converts this to the correct URL (`/products`) automatically

**Why use the array format instead of just writing `href="/products"`?**
If the base URL of your app ever changes (e.g. moving to a subdirectory), CakePHP updates all these links automatically. Hardcoded strings do not update themselves.

---

## Phase 4 Tests — Check Before Moving On

- [ ] Visit any page (e.g. the landing page `http://localhost:8765/`)
- [ ] Look at the navigation bar — click **"Marketplace"**
- [ ] You should be taken to `http://localhost:8765/products` (the product listing page)
- [ ] The URL in the browser bar should show `/products` — not `/#marketplace`
- [ ] The products page should load with all 5 product cards visible

---

---

# Phase 5 — Final Checks (Both ACs Together)

This phase has no new code. It is your final verification that both acceptance criteria are fully met before marking the user story as done.

---

## AC 7.1.1 Checklist
> *Given the user is browsing products, when products are displayed, then each product shows its name, price, and image.*

- [ ] Each product card shows a **name**
- [ ] Each product card shows a **price** (formatted with 2 decimal places and a $ sign)
- [ ] Each product card shows an **image** (not a broken image icon, not an empty space)
- [ ] If a product has no image URL, a placeholder image is shown instead of breaking the layout
- [ ] All 5 products are visible

## AC 7.1.2 Checklist
> *Given the user is on the marketplace page, when the page loads, then a list of available products is displayed.*

- [ ] Clicking "Marketplace" in the nav takes the user to `/products`
- [ ] The products page loads without errors
- [ ] Products are displayed as a list/grid
- [ ] Works whether the user is **logged in or not** (it is a public page)
- [ ] Works when accessed directly by typing `http://localhost:8765/products` in the browser

## Edge Case Tests
- [ ] What happens if you visit `/products` and the database has no products? → Should show "No products available yet" (not a crash or blank page)
- [ ] What happens if you visit `/products` while logged out? → Should work fine (no login redirect)

---

---

# Summary

## Files Created / Modified

```
webroot/css/marketplace.css        — NEW: all product page styles
templates/Products/index.php       — MODIFIED: uses CSS classes, adds image + themed header
templates/layout/default.php       — MODIFIED: Marketplace nav link points to /products
```

## Database Changes
```
products table — updated image_url column for all 5 rows
```

---

## What You Learned in This Plan

| Concept | Where You Used It |
|---------|------------------|
| Static files in webroot | Storing images in `webroot/img/products/` |
| SQL UPDATE | Updating existing database rows in phpMyAdmin |
| CSS variables | Using `--g0`, `--e1`, `--s2` etc. from the project's design system |
| Page-specific CSS loading | `$this->Html->css('marketplace', ['block' => true])` |
| `object-fit: cover` | Making images fill their container without distortion |
| `aspect-ratio` CSS | Keeping all cards the same image height regardless of source image |
| Null fallback in PHP | `!empty($product->image_url) ? ... : 'placeholder'` |
| `number_format()` | Displaying prices as `$24.99` not `$24.9` |
| `mb_strimwidth()` | Safely truncating text (works with emoji and accented characters) |
| `h()` escaping | Preventing XSS — always wrap user data in `h()` when printing to HTML |
| CakePHP Html helper | `$this->Html->link()` for safe, portable URL generation |
| CakePHP layout template | How the nav bar wraps every page in the app |

---

*Plan written April 2026. Complete all phase tests before moving to the next phase.*
