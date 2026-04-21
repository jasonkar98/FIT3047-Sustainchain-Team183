<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Filtertags Controller
 *
 * @property \App\Model\Table\FiltertagsTable $Filtertags
 */
class FiltertagsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Filtertags->find();
        $filtertags = $this->paginate($query);

        $this->set(compact('filtertags'));
    }

    /**
     * View method
     *
     * @param string|null $id Filtertag id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $filtertag = $this->Filtertags->get($id, contain: ['Products']);
        $this->set(compact('filtertag'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $filtertag = $this->Filtertags->newEmptyEntity();
        if ($this->request->is('post')) {
            $filtertag = $this->Filtertags->patchEntity($filtertag, $this->request->getData());
            if ($this->Filtertags->save($filtertag)) {
                $this->Flash->success(__('The filtertag has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The filtertag could not be saved. Please, try again.'));
        }
        $products = $this->Filtertags->Products->find('list', limit: 200)->all();
        $this->set(compact('filtertag', 'products'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Filtertag id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $filtertag = $this->Filtertags->get($id, contain: ['Products']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $filtertag = $this->Filtertags->patchEntity($filtertag, $this->request->getData());
            if ($this->Filtertags->save($filtertag)) {
                $this->Flash->success(__('The filtertag has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The filtertag could not be saved. Please, try again.'));
        }
        $products = $this->Filtertags->Products->find('list', limit: 200)->all();
        $this->set(compact('filtertag', 'products'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Filtertag id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $filtertag = $this->Filtertags->get($id);
        if ($this->Filtertags->delete($filtertag)) {
            $this->Flash->success(__('The filtertag has been deleted.'));
        } else {
            $this->Flash->error(__('The filtertag could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
