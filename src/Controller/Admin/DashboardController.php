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

        // Pending farmer / manufacturer signups for the approvals panel.
        $pendingApprovals = $this->fetchTable('Users')->find()
            ->where([
                'role IN' => ['farmer', 'manufacturer'],
                'is_active' => 0,
            ])
            ->orderBy(['created' => 'DESC'])
            ->limit(5)
            ->all()
            ->toArray();

        $pendingApprovalCount = $this->fetchTable('Users')->find()
            ->where([
                'role IN' => ['farmer', 'manufacturer'],
                'is_active' => 0,
            ])
            ->count();

        $this->set(compact(
            'recentEnquiries', 'counts', 'newestUsers',
            'pendingApprovals', 'pendingApprovalCount'
        ));
    }
}