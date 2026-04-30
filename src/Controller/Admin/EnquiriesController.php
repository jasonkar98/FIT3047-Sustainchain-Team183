<?php
declare(strict_types=1);

namespace App\Controller\Admin;

class EnquiriesController extends AppController
{
    public function index()
    {
        $query = $this->fetchTable('Enquiries')
            ->find()
            ->contain(['Users'])
            ->order(['date' => 'DESC']);

        // Optional filters via ?status=unread|unresolved|all
        $status = $this->request->getQuery('status', 'all');
        if ($status === 'unread') {
            $query->where(['is_read' => false]);
        } elseif ($status === 'unresolved') {
            $query->where(['is_resolved' => false]);
        }

        $enquiries = $this->paginate($query, ['limit' => 20]);
        $this->set(compact('enquiries', 'status'));
    }

    public function view($id = null)
    {
        $enquiry = $this->Enquiries->get($id, contain: ['Users']);

        $prev = $this->Enquiries->find()
            ->where(['id <' => $id])
            ->orderBy(['id' => 'DESC'])
            ->first();

        $next = $this->Enquiries->find()
            ->where(['id >' => $id])
            ->orderBy(['id' => 'ASC'])
            ->first();

        $this->set(compact('enquiry', 'prev', 'next'));
    }


    public function toggleRead($id = null)
    {
        $this->request->allowMethod(['post']);
        $enquiriesTable = $this->fetchTable('Enquiries');
        $enquiry = $enquiriesTable->get($id);
        $enquiry->is_read = !$enquiry->is_read;
        $enquiriesTable->save($enquiry);

        $this->Flash->success($enquiry->is_read ? 'Marked as read.' : 'Marked as unread.');
        return $this->redirect($this->referer(['action' => 'index']));
    }

    public function toggleResolved($id = null)
    {
        $this->request->allowMethod(['post']);
        $enquiriesTable = $this->fetchTable('Enquiries');
        $enquiry = $enquiriesTable->get($id);
        $enquiry->is_resolved = !$enquiry->is_resolved;
        $enquiriesTable->save($enquiry);

        $this->Flash->success($enquiry->is_resolved ? 'Marked as resolved.' : 'Reopened.');
        return $this->redirect($this->referer(['action' => 'index']));
    }
}