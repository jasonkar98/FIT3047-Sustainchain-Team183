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

        $this->set(compact('favourites'));
    }
}
