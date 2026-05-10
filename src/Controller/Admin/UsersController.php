<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Http\Exception\NotFoundException;
use Cake\I18n\DateTime;
use Cake\Mailer\Mailer;
use Cake\Utility\Security;

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

    /**
     * Edit a non-admin user's first name, last name and role.
     *
     * Role is intentionally NOT mass-assignable on the User entity, so we
     * validate the value manually and assign it directly.
     */
    public function edit(?string $id = null)
    {
        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->get($id);

        // Admins are never managed via this UI.
        if ($user->role === 'admin') {
            throw new NotFoundException('User not found.');
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $errors = [];

            // Validate role manually (not mass-assignable on the entity).
            $newRole = (string)($data['role'] ?? '');
            if (!in_array($newRole, self::MANAGED_ROLES, true)) {
                $errors['role'] = ['Please select a valid role.'];
            }

            // Patch first_name / last_name with the dedicated validator.
            $user = $usersTable->patchEntity($user, [
                'first_name' => $data['first_name'] ?? '',
                'last_name'  => $data['last_name'] ?? '',
            ], ['validate' => 'adminEdit']);

            if (empty($errors) && !$user->getErrors()) {
                $user->role = $newRole;
                if ($usersTable->save($user)) {
                    $this->Flash->success('User updated.');
                    return $this->redirect([
                        'action' => 'index',
                        '?' => ['role' => $newRole],
                    ]);
                }
                $this->Flash->error('The user could not be saved. Please try again.');
            } elseif (!empty($errors)) {
                // Surface manual role error onto the entity for view rendering.
                $user->setErrors(array_merge($user->getErrors(), $errors));
                $this->Flash->error('Please fix the errors below.');
            } else {
                $this->Flash->error('Please fix the errors below.');
            }
        }

        $this->set(compact('user'));
        $this->set('managedRoles', self::MANAGED_ROLES);
    }

    /**
     * Send a password-reset link to the user via email. Mirrors
     * AuthController::forgetPassword's nonce flow so the user lands on
     * the same /auth/reset-password/{nonce} screen.
     */
    public function sendResetLink(?string $id = null)
    {
        $this->request->allowMethod(['post']);
        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->get($id);

        if ($user->role === 'admin') {
            throw new NotFoundException('User not found.');
        }

        $user->nonce = Security::randomString(128);
        $user->nonce_expiry = new DateTime('7 days');

        if (!$usersTable->save($user)) {
            $this->Flash->error('Could not generate a reset link. Please try again.');
            return $this->redirect($this->referer(['action' => 'edit', $id]));
        }

        try {
            $mailer = new Mailer('default');
            $mailer
                ->setEmailFormat('both')
                ->setTo($user->email)
                ->setSubject('Reset your account password');
            $mailer->viewBuilder()->setTemplate('reset_password');
            $mailer->setViewVars([
                'first_name' => $user->first_name,
                'last_name'  => $user->last_name,
                'nonce'      => $user->nonce,
                'email'      => $user->email,
            ]);
            $sent = $mailer->deliver();
        } catch (\Throwable $e) {
            $sent = false;
        }

        if ($sent) {
            $this->Flash->success('Password reset link sent to ' . $user->email . '.');
        } else {
            $this->Flash->error('Reset link generated but email could not be sent.');
        }

        return $this->redirect($this->referer(['action' => 'edit', $id]));
    }
}
