<?php
declare(strict_types=1);

namespace App\Controller;

class ProductsController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        // Products listing and detail are public (no login required)
        $this->Authentication->allowUnauthenticated(['index', 'view', 'toggleSave']);
    }

    public function index(): void
    {
        $query = $this->Products->find();
        $search = $this->request->getQuery();

        $identity = $this->Authentication->getIdentity();
        $savedProductIds = [];
        if ($identity) {
            $savedProductIds = $this->fetchTable('Favourites')
                ->find('list', [
                    'keyField' => 'product_id',
                    'valueField' => 'product_id',
                ])
                ->where(['user_id' => $identity->getIdentifier()])
                ->toArray();
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

        // Sorting
        $sortOptions = [
            'price_asc'  => ['Products.price' => 'ASC'],
            'price_desc' => ['Products.price' => 'DESC'],
            'newest'     => ['Products.created' => 'DESC'],
            'name_asc'   => ['Products.name' => 'ASC'],
            'name_desc'  => ['Products.name' => 'DESC'],
        ];
        $sort = $search['sort'] ?? 'newest';
        if (isset($sortOptions[$sort])) {
            $query->orderBy($sortOptions[$sort]);
        }

        // Get distinct categories for sidebar
        $conn = $this->Products->getConnection();
        $result = $conn->execute('SELECT DISTINCT category FROM products WHERE category IS NOT NULL ORDER BY category');
        $categoriesList = $result->fetchAll('assoc');
        $categories = array_column($categoriesList, 'category');

        $products = $this->paginate($query, ['limit' => 12]);

        $this->set(compact('products', 'search', 'categories', 'savedProductIds'));
    }

    public function toggleSave($id = null)
    {
        $this->request->allowMethod(['post']);

        $identity = $this->Authentication->getIdentity();
        if (!$identity) {
            return $this->response->withStatus(401)
                ->withType('application/json')
                ->withStringBody(json_encode(['error' => 'Please log in to save products']));
        }

        if (empty($id) || !$this->Products->exists(['id' => $id])) {
            return $this->response->withStatus(404)
                ->withType('application/json')
                ->withStringBody(json_encode(['error' => 'Product not found']));
        }

        $userId = $identity->getIdentifier();
        $favourites = $this->fetchTable('Favourites');

        $existing = $favourites->find()
            ->where(['user_id' => $userId, 'product_id' => $id])
            ->first();

        if ($existing) {
            if ($favourites->delete($existing)) {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode(['status' => 'success', 'action' => 'removed', 'productId' => $id]));
            }

            return $this->response->withStatus(500)
                ->withType('application/json')
                ->withStringBody(json_encode(['error' => 'Could not remove saved product']));
        }

        $favourite = $favourites->newEmptyEntity();
        $favourite = $favourites->patchEntity($favourite, [
            'user_id' => $userId,
            'product_id' => $id,
        ]);

        if ($favourites->save($favourite)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['status' => 'success', 'action' => 'saved', 'productId' => $id]));
        }

        return $this->response->withStatus(422)
            ->withType('application/json')
            ->withStringBody(json_encode(['error' => 'Could not save product']));
    }
}