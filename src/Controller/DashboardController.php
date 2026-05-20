<?php
declare(strict_types=1);

namespace App\Controller;

class DashboardController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        // No unauthenticated actions — the entire dashboard requires login
    }

    public function index()
    {
        $identity = $this->request->getAttribute('identity');

        $favourites = $this->fetchTable('Favourites')
            ->find()
            ->contain(['Products'])
            ->where(['Favourites.user_id' => $identity->getIdentifier()])
            ->all()
            ->toArray();

        $orders = $this->fetchTable('Orders')
            ->find()
            ->where(['Orders.user_id' => $identity->get('id')])
            ->contain(['OrderItems' => ['Products']])
            ->orderBy(['Orders.created' => 'DESC'])
            ->all();

        $enquiries = $this->fetchTable('Enquiries')
        ->find()
        ->where(['user_id' => $identity->id])
        ->orderBy(['date' => 'DESC'])
        ->all()
        ->toArray();

        $listings = $this->fetchTable('Products')
            ->find()
            ->where(['Products.user_id' => $identity->getIdentifier()])
            ->orderBy(['Products.created' => 'DESC'])
            ->all()
            ->toArray();

        $this->set(compact('favourites', 'orders', 'enquiries', 'listings', 'identity'));
    }
}
