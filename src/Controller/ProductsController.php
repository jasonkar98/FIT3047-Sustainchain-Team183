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