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
            ->where(['user_id' => $identity->id])
            ->all()
            ->toArray();

        $orders = $this->fetchTable('Orders')
            ->find()
            ->where(['user_id' => $identity->id])
            ->all()
            ->toArray();

        $enquiries = $this->fetchTable('Enquiries')
            ->find()
            ->where(['user_id' => $identity->id])
            ->order(['date' => 'DESC'])
            ->all()
            ->toArray();

        // $listings = $this->fetchTable('Listings')
        //     ->find()
        //     ->where(['seller_id' => $identity->id])
        //     ->all()
        //     ->toArray();

        $this->set(compact('favourites', 'orders', 'enquiries'));
    }
}
