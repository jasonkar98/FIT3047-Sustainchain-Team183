<?php
declare(strict_types=1);

namespace App\Controller\Admin;

class UsersController extends AppController
{
    /**
     * Roles managed via this admin UI. Admins are intentionally never listed
     * here — the admin role is not assignable from this screen.
     */
    private const MANAGED_ROLES = ['buyer', 'seller', 'manufacturer', 'farmer'];

    /**
     * Allowed sort keys mapped to the column / expression they sort by.
     */
    private const SORTS = [
        'signup_date' => 'Users.created',
        'listings'    => 'product_count',
        'name'        => 'Users.first_name',
    ];

    /**
     * Listing of non-admin users. Supports filtering by role, keyword search
     * across name + email, and sorting by signup date / listings / name.
     */
    public function index()
    {
        $usersTable = $this->fetchTable('Users');

        // Role tab — default to buyer if missing/invalid.
        $role = $this->request->getQuery('role', 'buyer');
        if (!in_array($role, self::MANAGED_ROLES, true)) {
            $role = 'buyer';
        }

        $keyword = trim((string)$this->request->getQuery('keyword', ''));

        $sort = $this->request->getQuery('sort', 'signup_date');
        if (!array_key_exists($sort, self::SORTS)) {
            $sort = 'signup_date';
        }

        $direction = strtolower((string)$this->request->getQuery('direction', 'desc'));
        if (!in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        // Base query — only non-admin users in the selected role.
        $query = $usersTable->find()
            ->where(['Users.role' => $role])
            ->where(['Users.role !=' => 'admin']);

        if ($keyword !== '') {
            $like = '%' . $keyword . '%';
            $query->where(['OR' => [
                'Users.first_name LIKE' => $like,
                'Users.last_name LIKE'  => $like,
                'Users.email LIKE'      => $like,
            ]]);
        }

        // Per-row product count via a correlated subquery — avoids GROUP BY
        // (which complicates pagination counts) and lets us sort on the
        // result via the alias `product_count`.
        $query->select($usersTable)
            ->select([
                'product_count' => $query->newExpr(
                    '(SELECT COUNT(*) FROM products WHERE products.user_id = Users.id)'
                ),
            ])
            ->enableAutoFields(false);

        // Apply sort.
        if ($sort === 'name') {
            $query->orderBy([
                'Users.first_name' => $direction,
                'Users.last_name'  => $direction,
            ]);
        } else {
            $query->orderBy([self::SORTS[$sort] => $direction, 'Users.id' => 'ASC']);
        }

        // Counts per role, for the tab badges. Cheap — one COUNT per role.
        $roleCounts = [];
        foreach (self::MANAGED_ROLES as $r) {
            $roleCounts[$r] = $usersTable->find()
                ->where(['role' => $r])
                ->count();
        }

        $users = $this->paginate($query, ['limit' => 20]);

        $this->set(compact('users', 'role', 'keyword', 'sort', 'direction', 'roleCounts'));
        $this->set('managedRoles', self::MANAGED_ROLES);
    }
}
