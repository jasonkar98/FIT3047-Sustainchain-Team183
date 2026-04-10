<?php
declare(strict_types=1);

namespace App\Controller;

class ProductsController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->Authentication->allowUnauthenticated(['index', 'view']);
    }

    public function index(): void
    {
        $query = $this->Products->find();

        $search = $this->request->getQuery();

        // Keyword search
        if (!empty($search['keyword'])) {
            $keyword = '%' . trim($search['keyword']) . '%';
            $query->where([
                'OR' => [
                    'Products.name LIKE'        => $keyword,
                    'Products.description LIKE' => $keyword,
                    'Products.category LIKE'    => $keyword,
                ]
            ]);
        }

        // Price range filter
        if (!empty($search['price_min'])) {
            $query->where(['Products.price >=' => (float)$search['price_min']]);
        }
        if (!empty($search['price_max'])) {
            $query->where(['Products.price <=' => (float)$search['price_max']]);
        }

        $products = $this->paginate($query, ['limit' => 12]);

        $this->set(compact('products', 'search'));
    }
}