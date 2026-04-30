<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Favourites Controller
 *
 * @property \App\Model\Table\FavouritesTable $Favourites
 */
class FavouritesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Favourites->find()
            ->contain(['Users', 'Products']);
        $favourites = $this->paginate($query);

        $this->set(compact('favourites'));
    }

    /**
     * View method
     *
     * @param string|null $id Favourite id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $favourite = $this->Favourites->get($id, contain: ['Users', 'Products']);
        $this->set(compact('favourite'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $favourite = $this->Favourites->newEmptyEntity();
        if ($this->request->is('post')) {
            $favourite = $this->Favourites->patchEntity($favourite, $this->request->getData());
            if ($this->Favourites->save($favourite)) {
                $this->Flash->success(__('The favourite has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The favourite could not be saved. Please, try again.'));
        }
        $users = $this->Favourites->Users->find('list', limit: 200)->all();
        $products = $this->Favourites->Products->find('list', limit: 200)->all();
        $this->set(compact('favourite', 'users', 'products'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Favourite id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $favourite = $this->Favourites->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $favourite = $this->Favourites->patchEntity($favourite, $this->request->getData());
            if ($this->Favourites->save($favourite)) {
                $this->Flash->success(__('The favourite has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The favourite could not be saved. Please, try again.'));
        }
        $users = $this->Favourites->Users->find('list', limit: 200)->all();
        $products = $this->Favourites->Products->find('list', limit: 200)->all();
        $this->set(compact('favourite', 'users', 'products'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Favourite id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $favourite = $this->Favourites->get($id);
        if ($this->Favourites->delete($favourite)) {
            $this->Flash->success(__('The favourite has been deleted.'));
        } else {
            $this->Flash->error(__('The favourite could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
