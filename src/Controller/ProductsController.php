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
        if ($this->request->is(['patch', 'post', 'put'])) {

            $data = $this->request->getData();

            $current_image = $product['image_url'];

            // Getting the User ID
            $user = $this->Authentication->getIdentity();
            $userId = $user->getIdentifier();
            $data['user_id'] = $userId;

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
        $product = $this->Products->get($id);
        if ($this->Products->delete($product)) {
            $this->Flash->success(__('The product has been deleted.'));
        } else {
            $this->Flash->error(__('The product could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
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
        $isSaved = false;
        if ($identity) {
            $isSaved = $this->fetchTable('Favourites')
                ->exists(['user_id' => $identity->getIdentifier(), 'product_id' => $id]);
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
