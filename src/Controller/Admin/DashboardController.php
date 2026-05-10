<?php
declare(strict_types=1);

namespace App\Controller\Admin;

class DashboardController extends AppController
{
    public function index()
    {
        $enquiriesTable = $this->fetchTable('Enquiries');

        $recentEnquiries = $enquiriesTable->find()
            ->order(['date' => 'DESC'])
            ->limit(5)
            ->all()
            ->toArray();

        $counts = [
            'total'      => $enquiriesTable->find()->count(),
            'unread'     => $enquiriesTable->find()->where(['is_read' => false])->count(),
            'unresolved' => $enquiriesTable->find()->where(['is_resolved' => false])->count(),
        ];

        // Newest non-admin users for the sidebar widget.
        $newestUsers = $this->fetchTable('Users')->find()
            ->where(['role !=' => 'admin'])
            ->orderBy(['created' => 'DESC'])
            ->limit(5)
            ->all()
            ->toArray();

        $this->set(compact('recentEnquiries', 'counts', 'newestUsers'));
    }
}