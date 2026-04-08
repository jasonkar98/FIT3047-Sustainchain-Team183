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

    public function index(): void
    {
        // Get the currently logged-in user's data from the session
        $currentUser = $this->Authentication->getIdentity();

        // Pass it to the view template so the HTML can display it
        $this->set('currentUser', $currentUser);
    }
}
