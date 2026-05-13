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

        // Hide unlisted products from the public marketplace. Admins see
        // everything (they need to be able to moderate unlisted listings).
        $viewerIsAdmin = $identity && $identity->get('role') === 'admin';
        if (!$viewerIsAdmin) {
            $query->where(['Products.is_listed' => 1]);
        }

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

        // Sorting
        $sortOptions = [
            'price_asc'     => ['(Products.price * (1 - COALESCE(Products.discount, 0) / 100))' => 'ASC'],
            'price_desc'    => ['(Products.price * (1 - COALESCE(Products.discount, 0) / 100))' => 'DESC'],
            'newest'        => ['Products.created' => 'DESC'],
            'name_asc'      => ['Products.name' => 'ASC'],
            'name_desc'     => ['Products.name' => 'DESC'],
            'discount_desc' => ['Products.discount' => 'DESC'],
        ];

        $sort = $search['sort'] ?? 'newest';
        if (isset($sortOptions[$sort])) {
            foreach ($sortOptions[$sort] as $field => $direction) {
                $query->orderBy($query->newExpr($field . ' ' . $direction));
            }
        }

        // Fixed category list — matches the add/edit form options
        $categories = ['Apparel', 'Bathroom', 'Beauty', 'Food', 'Kitchenware', 'Outdoors', 'Sporting', 'Supplements', 'Other'];

        $products = $this->paginate($query, ['limit' => 12]);

        $this->set(compact('products', 'search', 'categories', 'savedProductIds'));
    }
    
        /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $product = $this->Products->newEmptyEntity();
        if ($this->request->is('post')) {

            $data = $this->request->getData();

            // Getting the User ID
            $user = $this->Authentication->getIdentity();
            $userId = $user->getIdentifier();
            $data['user_id'] = $userId;

            // Adding the Image
            
            $image = $data['image_url'];
            $image_name = $image->getClientFilename();

            // Target directory - ensure this folder exists/has permissions
            $targetPath = WWW_ROOT . '/img/products/' . $image_name;

            // Store the image name in the database
            $data['image_url'] = $image_name; 
            
            if (!empty($image_name)) {
                // Move file to webroot folder
                $image->moveTo($targetPath);
            }
            
            $product = $this->Products->patchEntity($product, $data);
            
            if ($this->Products->save($product)) {
                $this->Flash->success(__('The product has been saved.'));

                return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
            }
            $this->Flash->error(__('The product could not be saved. Please, try again.'));
        }
        
        $filtertags = $this->Products->Filtertags->find('list', limit: 200)->all();
        $this->set(compact('product', 'filtertags'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Product id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $product = $this->Products->get($id, contain: ['Filtertags']);

        $identity = $this->Authentication->getIdentity();
        $isAdmin  = $identity && $identity->get('role') === 'admin';
        $isOwner  = $identity && (int)$identity->getIdentifier() === (int)$product->user_id;

        // Only the owner OR an admin can edit a product.
        if (!$identity || (!$isAdmin && !$isOwner)) {
            throw new \Cake\Http\Exception\ForbiddenException('You cannot edit this product.');
        }

        if ($this->request->is(['patch', 'post', 'put'])) {

            $data = $this->request->getData();

            $current_image = $product['image_url'];

            // Preserve seller ownership. For the owner, this is a no-op; for an
            // admin edit, this prevents the seller's user_id from being silently
            // overwritten with the admin's identity.
            $data['user_id'] = (int)$product->user_id;

            // Adding the Image
            $image = $data['image_url'];
            $image_name = $image->getClientFilename();

            if (empty($image_name)) {
                $data['image_url'] = $current_image;
            } else {

                // Target directory - ensure this folder exists/has permissions
                $targetPath = WWW_ROOT . '/img/products/' . $image_name;

                // Store the image name in the database
                $data['image_url'] = $image_name;

                if (!empty($image_name)) {
                    // Move file to webroot folder
                    $image->moveTo($targetPath);
                }

            }

            $product = $this->Products->patchEntity($product, $data);

            if ($this->Products->save($product)) {
                $this->Flash->success(__('The product has been saved.'));

                // Admins land back on the product page they came from. Owners
                // continue to their dashboard (existing behaviour).
                if ($isAdmin && !$isOwner) {
                    return $this->redirect(['action' => 'view', $product->id]);
                }
                return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
            }
            $this->Flash->error(__('The product could not be saved. Please, try again.'));
        }

        $filtertags = $this->Products->Filtertags->find('list', limit: 200)->all();
        $this->set(compact('product', 'filtertags'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Product id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $identity = $this->Authentication->getIdentity();
        $product = $this->Products->get($id);

        $isAdmin = $identity && $identity->get('role') === 'admin';
        $isOwner = $identity && (int)$identity->getIdentifier() === (int)$product->user_id;

        if (!$identity || (!$isAdmin && !$isOwner)) {
            throw new \Cake\Http\Exception\ForbiddenException('You cannot delete this product.');
        }

        $productName = $product->name;

        if ($this->Products->delete($product)) {
            $this->Flash->success(__('"{0}" has been deleted.', $productName));
        } else {
            $this->Flash->error(__('The product could not be deleted. Please, try again.'));
            return $this->redirect($this->referer(['action' => 'index']));
        }

        // Admin (non-owner) lands back on the marketplace; the owner returns
        // to their My Listings flow.
        if ($isAdmin && !$isOwner) {
            return $this->redirect(['action' => 'index']);
        }
        return $this->redirect(['action' => 'myListings']);
    }

    /**
     * View method
     *
     * @param string|null $id Product id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $product = $this->Products->get($id, contain: ['Users', 'Filtertags']);

        $identity = $this->Authentication->getIdentity();

        // Has the viewer favourited / saved this product?
        $isSaved = false;
        if ($identity) {
            $isSaved = $this->fetchTable('Favourites')
                ->exists(['user_id' => $identity->getIdentifier(), 'product_id' => $id]);
        }

        // Hide unlisted products from anyone other than:
        //   - the admin (moderation)
        //   - the owner (so they keep seeing their own listing)
        //   - a viewer who has it saved (so they can unsave it / see its status)
        $viewerIsAdmin = $identity && $identity->get('role') === 'admin';
        $viewerIsOwner = $identity && (int)$identity->getIdentifier() === (int)$product->user_id;
        if ((int)$product->is_listed === 0 && !$viewerIsAdmin && !$viewerIsOwner && !$isSaved) {
            throw new \Cake\Http\Exception\NotFoundException('Product not found.');
        }

        $this->set(compact('product', 'isSaved'));
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

    /**
     * Admin-only: unlist a currently-listed product, or relist it if it was
     * already unlisted (for any reason — admin, deactivation cascade, etc.).
     *
     * Sets unlist_reason to 'admin' when unlisting so a future reactivation
     * cascade leaves it alone. Clears unlist_reason on relist regardless of
     * what it was — the admin's action wins.
     */
    public function adminToggleListing($id = null)
    {
        $this->request->allowMethod(['post']);

        $identity = $this->Authentication->getIdentity();
        if (!$identity || $identity->get('role') !== 'admin') {
            throw new \Cake\Http\Exception\ForbiddenException('Admin access only.');
        }

        $product = $this->Products->get($id);
        $wasListed = (int)$product->is_listed === 1;

        if ($wasListed) {
            $product->is_listed     = 0;
            $product->unlist_reason = 'admin';
        } else {
            $product->is_listed     = 1;
            $product->unlist_reason = null;
        }

        if (!$this->Products->save($product)) {
            $this->Flash->error('Could not update the product. Please try again.');
            return $this->redirect($this->referer(['action' => 'view', $id]));
        }

        $this->Flash->success(
            'Product "' . h($product->name) . '" has been ' . ($wasListed ? 'unlisted' : 'relisted') . '.'
        );

        return $this->redirect(['action' => 'view', $id]);
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

        $product = empty($id) ? null : $this->Products->find()
            ->where(['id' => $id])
            ->first();

        if (!$product) {
            return $this->response->withStatus(404)
                ->withType('application/json')
                ->withStringBody(json_encode(['error' => 'Product not found']));
        }

        $userId = $identity->getIdentifier();

        // Owners cannot favourite their own products.
        if ((int)$product->user_id === (int)$userId) {
            return $this->response->withStatus(403)
                ->withType('application/json')
                ->withStringBody(json_encode(['error' => 'You cannot save your own product']));
        }
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
