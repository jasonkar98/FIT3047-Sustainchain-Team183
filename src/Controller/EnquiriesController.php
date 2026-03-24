<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * enquiries Controller
 *
 * @property \App\Model\Table\EnquiriesTable $enquiries
 */
class EnquiriesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->enquiries->find();
        $enquiries = $this->paginate($query);

        // Load Turnstile component for CAPTCHA
        $this->loadComponent('Turnstile');

        $this->set(compact('enquiries'));
    }

    /**
     * View method
     *
     * @param string|null $id Enquiry id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $enquiry = $this->enquiries->get($id, contain: []);
        $this->set(compact('enquiry'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $enquiry = $this->enquiries->newEmptyEntity();
        if ($this->request->is('post')) {
            $enquiry = $this->enquiries->patchEntity($enquiry, $this->request->getData());


            // Validate Turnstile response with CloudFlare
            $turnstileToken = $this->request->getData('cf-turnstile-response');
            if ($turnstileToken) {

                $turnstileResponse = $this->Turnstile->validateTurnstile($turnstileToken, $this->request->clientIp());
                // On failed validation, send user back to reset password page and try again
                if (!$turnstileResponse || !$turnstileResponse['success']) {
                    $this->log('Turnstile Response Error: ' . json_encode($turnstileResponse));
                    $this->Flash->error('CAPTCHA challenge failed. Please try again.');

                    $this->set(compact('enquiry')); // Need to return the $user entity so the form is autofilled

                    return $this->render(); // Skip the rest of the controller and render the view
                }
            }

            if ($this->enquiries->save($enquiry)) {
                $this->Flash->success(__('The enquiry has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The enquiry could not be saved. Please, try again.'));
        }
        $this->set(compact('enquiry'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Enquiry id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $enquiry = $this->enquiries->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $enquiry = $this->enquiries->patchEntity($enquiry, $this->request->getData());
            if ($this->enquiries->save($enquiry)) {
                $this->Flash->success(__('The enquiry has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The enquiry could not be saved. Please, try again.'));
        }
        $this->set(compact('enquiry'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Enquiry id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $enquiry = $this->enquiries->get($id);
        if ($this->enquiries->delete($enquiry)) {
            $this->Flash->success(__('The enquiry has been deleted.'));
        } else {
            $this->Flash->error(__('The enquiry could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
