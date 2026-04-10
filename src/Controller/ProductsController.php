<?php
declare(strict_types=1);

namespace App\Controller;

class ProductsController extends AppController
{
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

    // Category filter
    if (!empty($search['category'])) {
    $query->where(['Products.category IN' => (array)$search['category']]);
}

    // Price range filter
    if (!empty($search['price_min'])) {
        $query->where(['Products.price >=' => (float)$search['price_min']]);
    }
    if (!empty($search['price_max'])) {
        $query->where(['Products.price <=' => (float)$search['price_max']]);
    }

    // Get distinct categories for sidebar
    $conn = $this->Products->getConnection();
    $result = $conn->execute('SELECT DISTINCT category FROM products WHERE category IS NOT NULL ORDER BY category');
    $categoriesList = $result->fetchAll('assoc');
    $categories = array_column($categoriesList, 'category');

    $products = $this->paginate($query, ['limit' => 12]);

    $this->set(compact('products', 'search', 'categories'));
}   
}