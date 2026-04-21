<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * ProductsFiltertags Controller
 *
 * @property \App\Model\Table\ProductsFiltertagsTable $ProductsFiltertags
 */
class ProductsFiltertagsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->ProductsFiltertags->find()
            ->contain(['Products', 'Filtertags']);
        $productsFiltertags = $this->paginate($query);

        $this->set(compact('productsFiltertags'));
    }

    /**
     * View method
     *
     * @param string|null $id Products Filtertag id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $productsFiltertag = $this->ProductsFiltertags->get($id, contain: ['Products', 'Filtertags']);
        $this->set(compact('productsFiltertag'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $productsFiltertag = $this->ProductsFiltertags->newEmptyEntity();
        if ($this->request->is('post')) {
            $productsFiltertag = $this->ProductsFiltertags->patchEntity($productsFiltertag, $this->request->getData());
            if ($this->ProductsFiltertags->save($productsFiltertag)) {
                $this->Flash->success(__('The products filtertag has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The products filtertag could not be saved. Please, try again.'));
        }
        $products = $this->ProductsFiltertags->Products->find('list', limit: 200)->all();
        $filtertags = $this->ProductsFiltertags->Filtertags->find('list', limit: 200)->all();
        $this->set(compact('productsFiltertag', 'products', 'filtertags'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Products Filtertag id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $productsFiltertag = $this->ProductsFiltertags->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $productsFiltertag = $this->ProductsFiltertags->patchEntity($productsFiltertag, $this->request->getData());
            if ($this->ProductsFiltertags->save($productsFiltertag)) {
                $this->Flash->success(__('The products filtertag has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The products filtertag could not be saved. Please, try again.'));
        }
        $products = $this->ProductsFiltertags->Products->find('list', limit: 200)->all();
        $filtertags = $this->ProductsFiltertags->Filtertags->find('list', limit: 200)->all();
        $this->set(compact('productsFiltertag', 'products', 'filtertags'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Products Filtertag id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $productsFiltertag = $this->ProductsFiltertags->get($id);
        if ($this->ProductsFiltertags->delete($productsFiltertag)) {
            $this->Flash->success(__('The products filtertag has been deleted.'));
        } else {
            $this->Flash->error(__('The products filtertag could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
