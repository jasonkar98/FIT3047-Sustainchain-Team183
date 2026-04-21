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
        // paginate() loads products from the database, 12 per page
        $products = $this->paginate($this->Products, ['limit' => 12]);

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

        $this->set(compact('products', 'savedProductIds'));
    }

    public function myListings(): void
    {
        $identity = $this->Authentication->getIdentity();

        $query = $this->Products->find()
            ->where(['Products.user_id' => $identity->getIdentifier()])
            ->orderBy(['Products.created' => 'DESC']);

        $listings = $this->paginate($query, ['limit' => 12]);

        $first_name = $identity->first_name;

        $this->set(compact('listings', 'first_name'));
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