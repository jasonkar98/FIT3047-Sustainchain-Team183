<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\DateTime;

/**
 * Products Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 */
class ProductsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Products->find();
        $products = $this->paginate($query);

        $this->set(compact('products'));
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
        $this->set(compact('product'));
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

                return $this->redirect(['controller' => 'Pages', 'action' => 'landingPage']);
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

                return $this->redirect(['controller' => 'Pages', 'action' => 'landingPage']);
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
}
