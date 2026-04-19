# Plan 1 — Dashboard, Products, Favourites, Filters, and Product Detail

> **Who this is for:** A developer who is completely new to PHP and CakePHP.
> **How to use this:** Work through one phase at a time. Do not move on until every test at the end of the phase passes. Each phase teaches you one new CakePHP concept.

---

## What We Are Building

Based on these user stories:

- **US 4** — After login, user lands on a dashboard (not the landing page)
- **US 4.1 / AC 4.1.1** — Dashboard shows the user's favourited products
- **US 4.1 / AC 4.1.2** — Products page has category and price filters
- **US 4.1 / AC 4.1.3** — A product detail page shows product info, the seller, and reviews

---

## The New Database Tables We Will Need

Before writing any PHP, you need to understand what data we are storing.

```
users (already exists)
  id, email, password, ...

products (NEW)
  id, name, description, price, category, seller_id (links to users.id), created, modified

favourites (NEW)
  id, user_id (links to users.id), product_id (links to products.id), created

reviews (NEW)
  id, product_id, user_id, rating (1-5), body, created, modified
```

---

## Phases Overview

| Phase | What You Build | New CakePHP Concept |
|-------|---------------|---------------------|
| **1** | Redirect login → Dashboard | Routing, Controller, View |
| **2** | Products database table + Model | SQL schema, Table class, Entity |
| **3** | Products listing page | Controller action, View template |
| **4** | Seed test data | CakePHP shell / direct SQL |
| **5** | Favourite a product | Database associations, Form POST |
| **6** | Show favourites on dashboard | Querying with associations (contain) |
| **7** | Filter products by category & price | Query conditions from request data |
| **8** | Product detail page with reviews | Multi-table joins, nested data |

---

---

# Phase 1 — The Dashboard (Login Redirect + Basic Page)

## What You Learn
- How CakePHP routing works (`config/routes.php`)
- How to create a new Controller
- How to create a new View template

## What You Build
After a user logs in, instead of going to the landing page, they go to `/dashboard`. The dashboard shows "Welcome, your@email.com" as a starting point.

---

## Step 1.1 — Add a route for /dashboard

Open `config/routes.php`. Find the line that starts with `$routes->connect('/'` and add a new line directly below it:

```php
$routes->connect('/dashboard', ['controller' => 'Dashboard', 'action' => 'index']);
```

**What this does:** It tells CakePHP "when someone visits /dashboard, call DashboardController's index() method."

---

## Step 1.2 — Create DashboardController

Create a new file: `src/Controller/DashboardController.php`

```php
<?php
declare(strict_types=1);

namespace App\Controller;

class DashboardController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        // No unauthenticated actions — the entire dashboard requires login
    }

    public function index(): void
    {
        // Get the currently logged-in user's data from the session
        $currentUser = $this->Authentication->getIdentity();

        // Pass it to the view template so the HTML can display it
        $this->set('currentUser', $currentUser);
    }
}
```

**What this does:**
- `extends AppController` — inherits authentication setup from the base controller
- `getIdentity()` — reads the logged-in user's data from the session
- `$this->set(...)` — makes a variable available inside the HTML template

---

## Step 1.3 — Create the Dashboard template

Create a new folder and file: `templates/Dashboard/index.php`

```php
<div class="dashboard-container" style="max-width: 900px; margin: 40px auto; padding: 0 20px;">
    <h1>Welcome to your Dashboard</h1>
    <p>Logged in as: <strong><?= h($currentUser->email) ?></strong></p>

    <hr>

    <h2>Your Saved Products</h2>
    <p>You have not saved any products yet.</p>
</div>
```

**What `h()` does:** It "escapes" the value for safe display in HTML. Always use `h()` when printing user data in a template. This prevents XSS attacks (someone putting JavaScript in their email to run code on your page).

---

## Step 1.4 — Change the login redirect to go to /dashboard

Open `src/Controller/AuthController.php`. Find the `login()` method. Change the `$fallbackLocation` line:

**Before:**
```php
$fallbackLocation = ['controller' => 'Pages', 'action' => 'landingPage'];
```

**After:**
```php
$fallbackLocation = ['controller' => 'Dashboard', 'action' => 'index'];
```

---

## Phase 1 Tests — Check These Before Moving On

Open your browser and test each one manually:

- [ ] Visit `http://localhost:8765/auth/login` and log in with a valid account
- [ ] You should be redirected to `http://localhost:8765/dashboard` (not the landing page)
- [ ] The page should say "Welcome to your Dashboard"
- [ ] Your email address should appear on the page
- [ ] Visit `http://localhost:8765/dashboard` while NOT logged in → you should be redirected to `/auth/login`

**If something fails:** Check `logs/error.log` for the error message.

---

---

# Phase 2 — The Products Database Table

## What You Learn
- How to write a SQL schema file
- How CakePHP Table classes mirror database tables
- How CakePHP Entity classes represent one database row

## What You Build
A `products` database table and the CakePHP Model to work with it.

---

## Step 2.1 — Create the SQL schema file

Create file: `config/schema/products.sql`

```sql
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    category VARCHAR(100) NOT NULL,
    seller_id INT NOT NULL,
    image_url VARCHAR(500) DEFAULT NULL,
    created DATETIME DEFAULT NULL,
    modified DATETIME DEFAULT NULL
);
```

**Then run it in your terminal:**
```bash
mysql -u sustainchain -p sustainchain < config/schema/products.sql
```

**What each column does:**
- `seller_id` — links to a user in the `users` table (the person selling this product)
- `DECIMAL(10, 2)` — stores money amounts like `19.99` (10 digits total, 2 decimal places)
- `created / modified` — CakePHP's TimestampBehavior will auto-fill these

---

## Step 2.2 — Create the ProductsTable class

Create file: `src/Model/Table/ProductsTable.php`

```php
<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ProductsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('products');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        // Auto-manage created and modified timestamps
        $this->addBehavior('Timestamp');

        // A product BELONGS TO one seller (who is a user)
        $this->belongsTo('Sellers', [
            'className'  => 'Users',
            'foreignKey' => 'seller_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->notEmptyString('name', 'A product name is required')
            ->notEmptyString('description', 'A description is required')
            ->greaterThan('price', 0, 'Price must be greater than 0')
            ->notEmptyString('category', 'A category is required');

        return $validator;
    }
}
```

**What `belongsTo` means:** A product has one seller. This is how you tell CakePHP about the relationship so you can load the seller's data alongside the product later.

---

## Step 2.3 — Create the Product Entity

Create file: `src/Model/Entity/Product.php`

```php
<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Product extends Entity
{
    // These fields can be filled from a form submission
    protected array $_accessible = [
        'name'        => true,
        'description' => true,
        'price'       => true,
        'category'    => true,
        'seller_id'   => true,
        'image_url'   => true,
    ];
}
```

**What `$_accessible` means:** CakePHP protects you from "mass assignment" attacks (where someone hacks a form to set fields you did not intend). Only fields listed as `true` here can be set from a form POST.

---

## Step 2.4 — Seed test data (add some fake products to the database)

You cannot test a product listing without products. Run this SQL to add a few:

First, find the ID of a user in your database:
```bash
mysql -u sustainchain -p -e "SELECT id, email FROM sustainchain.users LIMIT 3;"
```

Then insert some products (replace `1` with an actual user ID from above):
```sql
INSERT INTO products (name, description, price, category, seller_id, created, modified) VALUES
('Bamboo Water Bottle', 'Eco-friendly reusable bottle made from bamboo.', 24.99, 'Lifestyle', 1, NOW(), NOW()),
('Organic Cotton Tote', 'Sturdy shopping bag, GOTS certified organic cotton.', 14.99, 'Bags', 1, NOW(), NOW()),
('Solar Phone Charger', 'Portable solar panel charger for mobile devices.', 59.99, 'Electronics', 1, NOW(), NOW()),
('Beeswax Wrap Set', 'Reusable food wrap, replaces plastic cling film.', 18.99, 'Kitchen', 1, NOW(), NOW()),
('Recycled Notebook', 'A4 notebook made from 100% recycled paper.', 9.99, 'Stationery', 1, NOW(), NOW());
```

Run it:
```bash
mysql -u sustainchain -p sustainchain -e "INSERT INTO ..."
```

Or paste it into a `.sql` file and run with `mysql ... < file.sql`.

---

## Phase 2 Tests — Check These Before Moving On

Run these commands and verify the results:

```bash
# Check the table was created
mysql -u sustainchain -p -e "DESCRIBE sustainchain.products;"

# Check the test data is there
mysql -u sustainchain -p -e "SELECT id, name, price, category FROM sustainchain.products;"
```

Expected: You should see 5 products listed.

Also verify CakePHP can find the model — visit any page in the browser and check there are no errors in `logs/error.log`.

---

---

# Phase 3 — The Products Listing Page

## What You Learn
- How a Controller loads data from a Model and passes it to a View
- How to loop over a list of records in a template
- CakePHP's `paginate()` method

## What You Build
A page at `/products` that lists all products.

---

## Step 3.1 — Add the /products route

Open `config/routes.php` and add:

```php
$routes->connect('/products', ['controller' => 'Products', 'action' => 'index']);
$routes->connect('/products/{id}', ['controller' => 'Products', 'action' => 'view'])
    ->setPatterns(['id' => '\d+'])
    ->setPass(['id']);
```

The second route is for Phase 8 (product detail) but add it now so you do not forget.

---

## Step 3.2 — Create ProductsController

Create file: `src/Controller/ProductsController.php`

```php
<?php
declare(strict_types=1);

namespace App\Controller;

class ProductsController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        // Products listing and detail are public (no login required)
        $this->Authentication->allowUnauthenticated(['index', 'view']);
    }

    public function index(): void
    {
        // paginate() loads products from the database, 12 per page
        $products = $this->paginate($this->Products, ['limit' => 12]);

        $this->set(compact('products'));
    }
}
```

**How CakePHP auto-loads the Model:** Because the controller is called `ProductsController`, CakePHP automatically makes `$this->Products` available, pointing to `ProductsTable`. You do not have to write `$this->fetchTable(...)`.

---

## Step 3.3 — Create the Products listing template

Create folder `templates/Products/` and file `templates/Products/index.php`:

```php
<div style="max-width: 1100px; margin: 40px auto; padding: 0 20px;">
    <h1>Products</h1>

    <?php if ($products->isEmpty()): ?>
        <p>No products found.</p>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
            <?php foreach ($products as $product): ?>
                <div style="border: 1px solid #ddd; border-radius: 8px; padding: 16px;">
                    <h3><?= h($product->name) ?></h3>
                    <p style="color: #666;"><?= h($product->category) ?></p>
                    <p style="font-size: 1.3em; font-weight: bold;">$<?= h($product->price) ?></p>
                    <p><?= h(mb_strimwidth($product->description, 0, 80, '...')) ?></p>
                    <a href="/products/<?= $product->id ?>">View Details →</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
```

**What `foreach` does here:** The `$products` variable is a collection of `Product` entity objects. The `foreach` loop runs once for each product and gives you access to its fields (`$product->name`, `$product->price`, etc.).

---

## Phase 3 Tests — Check These Before Moving On

- [ ] Visit `http://localhost:8765/products`
- [ ] You should see a grid of 5 products (the ones you seeded)
- [ ] Each product shows its name, category, price, and a short description
- [ ] "View Details →" link appears on each product (the page it links to does not work yet — that is Phase 8)
- [ ] Visit `/products` while logged out — it should still work (public page)

---

---

# Phase 4 — The Favourites System

## What You Learn
- Many-to-many database relationships
- How to handle a form POST that is not a "create" form (just an action button)
- CakePHP associations: `belongsToMany`

## What You Build
A heart/favourite button on each product. The dashboard shows your saved products.

---

## Step 4.1 — Create the favourites database table

Create file: `config/schema/favourites.sql`

```sql
CREATE TABLE IF NOT EXISTS favourites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created DATETIME DEFAULT NULL,
    UNIQUE KEY unique_favourite (user_id, product_id)
);
```

The `UNIQUE KEY` prevents a user from favouriting the same product twice.

Run it:
```bash
mysql -u sustainchain -p sustainchain < config/schema/favourites.sql
```

---

## Step 4.2 — Create FavouritesTable

Create file: `src/Model/Table/FavouritesTable.php`

```php
<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class FavouritesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('favourites');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp', ['events' => ['Model.beforeSave' => ['created' => 'new']]]);

        $this->belongsTo('Users', ['foreignKey' => 'user_id']);
        $this->belongsTo('Products', ['foreignKey' => 'product_id']);
    }
}
```

---

## Step 4.3 — Create Favourite Entity

Create file: `src/Model/Entity/Favourite.php`

```php
<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Favourite extends Entity
{
    protected array $_accessible = [
        'user_id'    => true,
        'product_id' => true,
    ];
}
```

---

## Step 4.4 — Add associations to UsersTable and ProductsTable

Open `src/Model/Table/UsersTable.php` and add inside `initialize()`:

```php
$this->hasMany('Favourites', ['foreignKey' => 'user_id']);
$this->belongsToMany('FavouriteProducts', [
    'className'        => 'Products',
    'joinTable'        => 'favourites',
    'foreignKey'       => 'user_id',
    'targetForeignKey' => 'product_id',
]);
```

Open `src/Model/Table/ProductsTable.php` and add inside `initialize()`:

```php
$this->hasMany('Favourites', ['foreignKey' => 'product_id']);
```

**What `belongsToMany` means:** A user can have many products (through the favourites table) AND a product can be favourited by many users. This is a many-to-many relationship. CakePHP handles the join table automatically.

---

## Step 4.5 — Add favourite/unfavourite actions to ProductsController

Open `src/Controller/ProductsController.php` and add these two methods:

```php
public function favourite(int $productId): \Cake\Http\Response
{
    // Only accept POST requests (a link click is GET — we need a form POST for actions that change data)
    $this->request->allowMethod('post');

    $currentUser = $this->Authentication->getIdentity();
    $Favourites = $this->fetchTable('Favourites');

    // Check if already favourited
    $existing = $Favourites->find()
        ->where(['user_id' => $currentUser->id, 'product_id' => $productId])
        ->first();

    if ($existing) {
        $this->Flash->warning('You have already saved this product.');
    } else {
        $favourite = $Favourites->newEntity([
            'user_id'    => $currentUser->id,
            'product_id' => $productId,
        ]);
        if ($Favourites->save($favourite)) {
            $this->Flash->success('Product saved to your dashboard!');
        } else {
            $this->Flash->error('Could not save product. Please try again.');
        }
    }

    return $this->redirect(['action' => 'index']);
}

public function unfavourite(int $productId): \Cake\Http\Response
{
    $this->request->allowMethod('post');

    $currentUser = $this->Authentication->getIdentity();
    $Favourites = $this->fetchTable('Favourites');

    $favourite = $Favourites->find()
        ->where(['user_id' => $currentUser->id, 'product_id' => $productId])
        ->first();

    if ($favourite) {
        $Favourites->delete($favourite);
        $this->Flash->success('Product removed from your dashboard.');
    }

    return $this->redirect($this->referer(['action' => 'index']));
}
```

Also update the `allowUnauthenticated` list to keep `favourite` and `unfavourite` protected (do not add them — only `index` and `view` should be unauthenticated).

---

## Step 4.6 — Add the favourite routes

Open `config/routes.php` and add:

```php
$routes->connect('/products/{id}/favourite', ['controller' => 'Products', 'action' => 'favourite'])
    ->setPatterns(['id' => '\d+'])
    ->setPass(['id']);

$routes->connect('/products/{id}/unfavourite', ['controller' => 'Products', 'action' => 'unfavourite'])
    ->setPatterns(['id' => '\d+'])
    ->setPass(['id']);
```

---

## Step 4.7 — Add a "Save" button to the products listing

Open `templates/Products/index.php`. Replace the "View Details" anchor with:

```php
<a href="/products/<?= $product->id ?>">View Details →</a>

<?php if ($this->request->getAttribute('identity')): ?>
    <form method="post" action="/products/<?= $product->id ?>/favourite" style="display:inline;">
        <?= $this->Form->hidden('_Token') ?>
        <?= $this->Form->create(null, ['url' => '/products/' . $product->id . '/favourite', 'style' => 'display:inline']) ?>
        <button type="submit" style="background:none;border:1px solid green;padding:4px 10px;cursor:pointer;">
            ♥ Save
        </button>
        <?= $this->Form->end() ?>
    </form>
<?php endif; ?>
```

**Why a form POST and not a link (GET)?** Any action that *changes data* (saving, deleting, updating) must use POST — not a plain link. Links are GET requests. Using GET for data-changing actions is a security problem because it can be triggered accidentally (e.g. by a browser prefetch).

**Why `$this->Form->create()`?** CakePHP's Form helper automatically adds the CSRF token to the form. This prevents cross-site request forgery attacks.

---

## Step 4.8 — Show favourites on the Dashboard

Open `src/Controller/DashboardController.php` and replace the `index()` method:

```php
public function index(): void
{
    $currentUser = $this->Authentication->getIdentity();

    // Load the user's favourited products
    $Users = $this->fetchTable('Users');
    $userWithFavourites = $Users->find()
        ->where(['Users.id' => $currentUser->id])
        ->contain(['FavouriteProducts'])   // join in the products via the favourites table
        ->first();

    $favouriteProducts = $userWithFavourites->favourite_products ?? [];

    $this->set(compact('currentUser', 'favouriteProducts'));
}
```

Open `templates/Dashboard/index.php` and replace the placeholder with:

```php
<div class="dashboard-container" style="max-width: 900px; margin: 40px auto; padding: 0 20px;">
    <h1>Welcome to your Dashboard</h1>
    <p>Logged in as: <strong><?= h($currentUser->email) ?></strong></p>

    <hr>

    <h2>Your Saved Products</h2>

    <?php if (empty($favouriteProducts)): ?>
        <p>You have not saved any products yet. <a href="/products">Browse products →</a></p>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
            <?php foreach ($favouriteProducts as $product): ?>
                <div style="border: 1px solid #ddd; border-radius: 8px; padding: 16px;">
                    <h3><?= h($product->name) ?></h3>
                    <p style="color: #666;"><?= h($product->category) ?></p>
                    <p style="font-weight: bold;">$<?= h($product->price) ?></p>
                    <a href="/products/<?= $product->id ?>">View →</a>

                    <?= $this->Form->create(null, ['url' => '/products/' . $product->id . '/unfavourite', 'style' => 'display:inline']) ?>
                    <button type="submit" style="color:red;background:none;border:none;cursor:pointer;">
                        Remove
                    </button>
                    <?= $this->Form->end() ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
```

---

## Phase 4 Tests — Check These Before Moving On

- [ ] Visit `/products` while logged in
- [ ] Click "♥ Save" on a product — you should see a success flash message
- [ ] Visit `/dashboard` — the saved product should appear
- [ ] Click "Remove" on the dashboard — the product should disappear
- [ ] Try saving the same product twice — you should see a "already saved" warning
- [ ] Visit `/products` while logged out — the Save button should NOT appear (only "View Details")
- [ ] Try visiting `/products/1/favourite` directly via the browser URL bar (GET) — you should get a Method Not Allowed error (good — this proves the POST protection works)

---

---

# Phase 5 — Filter Products by Category and Price

## What You Learn
- Reading query parameters from the URL (e.g. `?category=Kitchen&max_price=30`)
- Adding conditions to a CakePHP query dynamically
- Passing current filter values back to the form

## What You Build
A filter form above the product grid. When submitted, only matching products are shown.

---

## Step 5.1 — Update ProductsController::index to handle filters

Open `src/Controller/ProductsController.php`. Replace the `index()` method:

```php
public function index(): void
{
    // Read filter values from the URL query string
    // e.g. /products?category=Kitchen&max_price=30
    $category = $this->request->getQuery('category');
    $maxPrice  = $this->request->getQuery('max_price');

    // Build the query — start with all products
    $query = $this->Products->find()->orderByDesc('Products.created');

    // Apply category filter only if one was provided
    if (!empty($category)) {
        $query = $query->where(['Products.category' => $category]);
    }

    // Apply price filter only if one was provided
    if (!empty($maxPrice) && is_numeric($maxPrice)) {
        $query = $query->where(['Products.price <=' => (float)$maxPrice]);
    }

    $products = $this->paginate($query, ['limit' => 12]);

    // Pass current filter values back to the view so the form stays filled in
    $this->set(compact('products', 'category', 'maxPrice'));
}
```

---

## Step 5.2 — Add the filter form to the products template

Open `templates/Products/index.php`. Add this block at the very top, before the `<h1>`:

```php
<form method="get" action="/products" style="background:#f5f5f5; padding:16px; border-radius:8px; margin-bottom:24px; display:flex; gap:16px; align-items:flex-end; flex-wrap:wrap;">
    <div>
        <label for="category" style="display:block; font-weight:bold; margin-bottom:4px;">Category</label>
        <select name="category" id="category" style="padding:8px; min-width:150px;">
            <option value="">All Categories</option>
            <?php foreach (['Lifestyle', 'Bags', 'Electronics', 'Kitchen', 'Stationery'] as $cat): ?>
                <option value="<?= h($cat) ?>" <?= ($category ?? '') === $cat ? 'selected' : '' ?>>
                    <?= h($cat) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="max_price" style="display:block; font-weight:bold; margin-bottom:4px;">Max Price ($)</label>
        <input type="number" name="max_price" id="max_price" min="0" step="0.01"
               value="<?= h($maxPrice ?? '') ?>"
               placeholder="e.g. 50.00"
               style="padding:8px; width:120px;" />
    </div>

    <div style="display:flex; gap:8px;">
        <button type="submit" style="padding:8px 16px; background:#2d7a4f; color:white; border:none; border-radius:4px; cursor:pointer;">
            Apply Filters
        </button>
        <a href="/products" style="padding:8px 16px; background:#ddd; border-radius:4px; text-decoration:none; color:#333;">
            Clear
        </a>
    </div>
</form>
```

**Why `method="get"` and not `method="post"` here?** Filters do not change data — they just change what you see. Using GET means the filters are in the URL (`/products?category=Kitchen`), which means users can bookmark or share a filtered view. This is correct HTTP practice.

---

## Phase 5 Tests — Check These Before Moving On

- [ ] Visit `/products` — all 5 products should be visible, filter form is at the top
- [ ] Select "Kitchen" from the dropdown → click Apply Filters — only the Beeswax Wrap should appear
- [ ] Set Max Price to `20` → Apply Filters — only products under $20 should appear
- [ ] Apply both category AND price together — results should satisfy both conditions
- [ ] Click "Clear" — all products return
- [ ] Check the URL bar changes when you filter (e.g. `/products?category=Kitchen`) — this proves the GET method is working correctly

---

---

# Phase 6 — Product Detail Page

## What You Learn
- Loading a single record by ID
- Loading related records (seller, reviews) alongside the main record using `contain`
- How CakePHP raises a 404 automatically for missing records

## What You Build
A page at `/products/1` showing the full product details, the seller's email, and a list of reviews.

---

## Step 6.1 — Create the reviews table

Create file: `config/schema/reviews.sql`

```sql
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    body TEXT NOT NULL,
    created DATETIME DEFAULT NULL,
    modified DATETIME DEFAULT NULL
);
```

Run it:
```bash
mysql -u sustainchain -p sustainchain < config/schema/reviews.sql
```

Seed two test reviews (replace product_id and user_id with real values):
```sql
INSERT INTO reviews (product_id, user_id, rating, body, created, modified) VALUES
(1, 1, 5, 'Absolutely love this bottle! Great quality and stays cold for hours.', NOW(), NOW()),
(1, 1, 4, 'Good product. Packaging could be better but the bottle itself is excellent.', NOW(), NOW());
```

---

## Step 6.2 — Create ReviewsTable

Create file: `src/Model/Table/ReviewsTable.php`

```php
<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ReviewsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('reviews');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Products', ['foreignKey' => 'product_id']);
        $this->belongsTo('Users', ['foreignKey' => 'user_id']);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->range('rating', [1, 5], 'Rating must be between 1 and 5')
            ->notEmptyString('body', 'Review text is required');

        return $validator;
    }
}
```

---

## Step 6.3 — Create Review Entity

Create file: `src/Model/Entity/Review.php`

```php
<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Review extends Entity
{
    protected array $_accessible = [
        'product_id' => true,
        'user_id'    => true,
        'rating'     => true,
        'body'       => true,
    ];
}
```

---

## Step 6.4 — Add associations to ProductsTable

Open `src/Model/Table/ProductsTable.php` and add inside `initialize()`:

```php
$this->hasMany('Reviews', ['foreignKey' => 'product_id']);
```

---

## Step 6.5 — Add the view() action to ProductsController

Open `src/Controller/ProductsController.php` and add:

```php
public function view(int $id): void
{
    // get() loads a single record by primary key
    // If the ID does not exist, CakePHP automatically returns a 404 page
    $product = $this->Products->get($id, [
        'contain' => [
            'Sellers',   // load the seller (joined from users table via seller_id)
            'Reviews' => ['Users'],  // load reviews AND each review's user
        ],
    ]);

    $this->set(compact('product'));
}
```

**What `contain` means:** When you load a product, CakePHP normally only gives you the product's own columns. `contain` tells it to also run JOIN queries to load related data. Here: load the seller and load the reviews (and within each review, load who wrote it).

---

## Step 6.6 — Create the product detail template

Create file: `templates/Products/view.php`

```php
<div style="max-width: 900px; margin: 40px auto; padding: 0 20px;">

    <a href="/products" style="color:#2d7a4f;">← Back to Products</a>

    <h1 style="margin-top: 16px;"><?= h($product->name) ?></h1>
    <p style="color:#666; font-size:0.9em;">Category: <?= h($product->category) ?></p>
    <p style="font-size: 2em; font-weight:bold; color:#2d7a4f;">$<?= h($product->price) ?></p>

    <p style="margin-top: 16px; line-height: 1.6;"><?= h($product->description) ?></p>

    <!-- Save button (only shown when logged in) -->
    <?php if ($this->request->getAttribute('identity')): ?>
        <?= $this->Form->create(null, ['url' => '/products/' . $product->id . '/favourite', 'style' => 'display:inline']) ?>
        <button type="submit" style="padding:10px 20px; background:#2d7a4f; color:white; border:none; border-radius:4px; cursor:pointer;">
            ♥ Save to Dashboard
        </button>
        <?= $this->Form->end() ?>
    <?php endif; ?>

    <hr style="margin: 32px 0;">

    <!-- Seller Information -->
    <h2>Sold by</h2>
    <?php if ($product->seller): ?>
        <p><?= h($product->seller->email) ?></p>
    <?php else: ?>
        <p>Seller information not available.</p>
    <?php endif; ?>

    <hr style="margin: 32px 0;">

    <!-- Reviews -->
    <h2>Reviews (<?= count($product->reviews) ?>)</h2>

    <?php if (empty($product->reviews)): ?>
        <p>No reviews yet. Be the first!</p>
    <?php else: ?>
        <?php foreach ($product->reviews as $review): ?>
            <div style="border: 1px solid #eee; border-radius: 8px; padding: 16px; margin-bottom: 16px;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <strong><?= h($review->user->email ?? 'Anonymous') ?></strong>
                    <span style="color:#f5a623;">
                        <?= str_repeat('★', $review->rating) ?><?= str_repeat('☆', 5 - $review->rating) ?>
                        (<?= h($review->rating) ?>/5)
                    </span>
                </div>
                <p style="margin-top:8px; line-height:1.6;"><?= h($review->body) ?></p>
                <small style="color:#999;"><?= $review->created->format('d M Y') ?></small>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>
```

---

## Phase 6 Tests — Check These Before Moving On

- [ ] Visit `http://localhost:8765/products/1`
- [ ] You should see the product name, description, price, and category
- [ ] You should see a "Sold by" section with the seller's email
- [ ] You should see 2 reviews with star ratings
- [ ] Click "← Back to Products" — should return to the products listing
- [ ] Visit `http://localhost:8765/products/9999` (a non-existent ID) — you should get a 404 error page (not a crash)
- [ ] On the products listing (`/products`), the "View Details →" link should now work and take you to the product page
- [ ] While logged in on the product page, click "♥ Save to Dashboard" — check it appears on `/dashboard`

---

---

# Summary — What You Have Built

| Phase | Feature | URL | Status |
|-------|---------|-----|--------|
| 1 | Dashboard (login redirect) | `/dashboard` | ✓ |
| 2 | Products database + model | (database only) | ✓ |
| 3 | Products listing | `/products` | ✓ |
| 4 | Favourite / unfavourite products | `/products/{id}/favourite` | ✓ |
| 4 | Dashboard shows favourites | `/dashboard` | ✓ |
| 5 | Filter products by category/price | `/products?category=X&max_price=Y` | ✓ |
| 6 | Product detail page with seller + reviews | `/products/{id}` | ✓ |

---

# Files Created / Modified Summary

**New files:**
```
config/schema/products.sql
config/schema/favourites.sql
config/schema/reviews.sql
src/Controller/DashboardController.php
src/Controller/ProductsController.php
src/Model/Table/ProductsTable.php
src/Model/Table/FavouritesTable.php
src/Model/Table/ReviewsTable.php
src/Model/Entity/Product.php
src/Model/Entity/Favourite.php
src/Model/Entity/Review.php
templates/Dashboard/index.php
templates/Products/index.php
templates/Products/view.php
```

**Modified files:**
```
config/routes.php              — added routes for /dashboard, /products, /products/{id}, /favourite, /unfavourite
src/Controller/AuthController.php  — changed login redirect to /dashboard
src/Model/Table/UsersTable.php     — added FavouriteProducts association
src/Model/Table/ProductsTable.php  — added Reviews and Favourites associations
```

---

# Key Concepts You Have Practised

| Concept | Where You Used It |
|---------|------------------|
| Creating a Controller | DashboardController, ProductsController |
| Creating a View template | Dashboard/index, Products/index, Products/view |
| Reading the logged-in user | `$this->Authentication->getIdentity()` |
| Loading data from the database | `$this->Products->find()`, `$this->Products->get()` |
| Loading related data | `contain(['Sellers', 'Reviews'])` |
| Passing data to a template | `$this->set(compact('products'))` |
| Printing safely in HTML | `h($variable)` |
| Form POST vs GET | POST for data changes (save/delete), GET for filters |
| CSRF-safe forms | `$this->Form->create()` / `$this->Form->end()` |
| Dynamic query conditions | `.where(['category' => $category])` only if value is set |
| Adding a route | `$routes->connect(...)` in `config/routes.php` |
| Database associations | `belongsTo`, `hasMany`, `belongsToMany` |

---

*Plan written April 2026. Work through one phase at a time and test before proceeding.*
