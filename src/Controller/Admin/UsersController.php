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

        // Role tab — default 'all' shows every non-admin user; the four
        // managed roles act as filters. An empty/invalid value also falls
        // back to 'all' so the table is never left empty by a bad query
        // string.
        $role = $this->request->getQuery('role', 'all');
        if ($role !== 'all' && !in_array($role, self::MANAGED_ROLES, true)) {
            $role = 'all';
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

        // Base query — never show admins. When a specific role is selected,
        // narrow further; otherwise show all non-admin users.
        $query = $usersTable->find()
            ->where(['Users.role !=' => 'admin']);

        if ($role !== 'all') {
            $query->where(['Users.role' => $role]);
        }

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

        // Counts per role, for the tab badges. Cheap — one COUNT per role,
        // plus an 'all' total for the default tab badge.
        $roleCounts = ['all' => 0];
        foreach (self::MANAGED_ROLES as $r) {
            $roleCounts[$r] = $usersTable->find()
                ->where(['role' => $r])
                ->count();
        }
        $roleCounts['all'] = $usersTable->find()
            ->where(['role !=' => 'admin'])
            ->count();

        $users = $this->paginate($query, ['limit' => 20]);

        // Sidebar list of admin accounts. Not editable from this UI, just
        // visible so the admin can see who else holds admin privileges.
        $adminUsers = $usersTable->find()
            ->where(['role' => 'admin'])
            ->orderBy(['Users.created' => 'DESC'])
            ->all()
            ->toArray();

        $this->set(compact('users', 'role', 'keyword', 'sort', 'direction', 'roleCounts', 'adminUsers'));
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

    /**
     * Toggle a user's is_active flag.
     *
     * Deactivate:
     *   - sets users.is_active = 0
     *   - cascade-unlists every product currently listed by this user:
     *     UPDATE products SET is_listed=0, unlist_reason='deactivation'
     *     WHERE user_id = ? AND is_listed = 1
     *   - already-unlisted products (admin-unlisted, etc.) are not touched
     *
     * Reactivate:
     *   - sets users.is_active = 1
     *   - restores ONLY the products unlisted by the deactivation cascade:
     *     UPDATE products SET is_listed=1, unlist_reason=NULL
     *     WHERE user_id = ? AND unlist_reason = 'deactivation'
     *   - products unlisted for any other reason (admin) stay unlisted
     */
    public function toggleActive(?string $id = null)
    {
        $this->request->allowMethod(['post']);

        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->get($id);

        if ($user->role === 'admin') {
            throw new NotFoundException('User not found.');
        }

        $productsTable = $this->fetchTable('Products');
        $wasActive = (int)$user->is_active === 1;

        $user->is_active = $wasActive ? 0 : 1;

        if (!$usersTable->save($user)) {
            $this->Flash->error('Could not update the user. Please try again.');
            return $this->redirect($this->referer(['action' => 'edit', $id]));
        }

        if ($wasActive) {
            // Deactivate → cascade-unlist currently listed products.
            $affected = $productsTable->updateAll(
                ['is_listed' => 0, 'unlist_reason' => 'deactivation'],
                ['user_id' => $user->id, 'is_listed' => 1],
            );

            $msg = $user->full_name . ' has been deactivated.';
            if ($affected > 0) {
                $msg .= ' (' . $affected . ' ' . ($affected === 1 ? 'product' : 'products') . ' unlisted.)';
            }
            $this->Flash->success($msg);
        } else {
            // Reactivate → restore ONLY products unlisted by the cascade.
            $affected = $productsTable->updateAll(
                ['is_listed' => 1, 'unlist_reason' => null],
                ['user_id' => $user->id, 'unlist_reason' => 'deactivation'],
            );

            $msg = $user->full_name . ' has been reactivated.';
            if ($affected > 0) {
                $msg .= ' (' . $affected . ' ' . ($affected === 1 ? 'product' : 'products') . ' relisted.)';
            }
            $this->Flash->success($msg);
        }

        return $this->redirect($this->referer(['action' => 'edit', $id]));
    }

    /**
     * Hard-delete a user. Wipes their data in a single transaction:
     *   - their products (and the favourites those products had)
     *   - their saved favourites
     *   - their orders (FK cascade)
     *   - their enquiries are detached, NOT deleted (user_id -> NULL) so the
     *     admin still has an audit trail of past communications. Email + name
     *     are preserved on the enquiry row itself.
     *
     * Blocks deleting:
     *   - admin accounts (via this UI)
     *   - the currently logged-in admin (no self-delete from here)
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->get($id);

        if ($user->role === 'admin') {
            throw new NotFoundException('User not found.');
        }

        $identity = $this->Authentication->getIdentity();
        if ($identity && (int)$identity->getIdentifier() === (int)$user->id) {
            $this->Flash->error('You cannot delete your own account.');
            return $this->redirect(['action' => 'edit', $id]);
        }

        $productsTable    = $this->fetchTable('Products');
        $favouritesTable  = $this->fetchTable('Favourites');
        $enquiriesTable   = $this->fetchTable('Enquiries');

        $userName = $user->full_name;
        $userId   = (int)$user->id;

        // How many products will be wiped — used for the success flash.
        $productCount = $productsTable->find()
            ->where(['user_id' => $userId])
            ->count();

        $connection = $usersTable->getConnection();

        try {
            $deleted = $connection->transactional(function () use (
                $usersTable, $productsTable, $favouritesTable, $enquiriesTable,
                $user, $userId
            ) {
                // Pull product IDs first so we can clean their inbound saves
                // explicitly. (Favourites has ON DELETE CASCADE on both FKs
                // but explicit deletes are robust regardless of FK state.)
                $productIds = $productsTable->find()
                    ->where(['user_id' => $userId])
                    ->all()
                    ->extract('id')
                    ->toList();

                if (!empty($productIds)) {
                    $favouritesTable->deleteAll(['product_id IN' => $productIds]);
                }

                $productsTable->deleteAll(['user_id' => $userId]);
                $favouritesTable->deleteAll(['user_id' => $userId]);

                // Preserve enquiry history — detach instead of delete.
                $enquiriesTable->updateAll(['user_id' => null], ['user_id' => $userId]);

                return (bool)$usersTable->delete($user);
            });
        } catch (\Throwable $e) {
            $this->Flash->error('Could not delete the user. Please try again.');
            return $this->redirect(['action' => 'edit', $id]);
        }

        if ($deleted) {
            $msg = $userName . ' has been permanently deleted.';
            if ($productCount > 0) {
                $msg .= ' (' . $productCount . ' ' . ($productCount === 1 ? 'product' : 'products') . ' removed.)';
            }
            $this->Flash->success($msg);
            return $this->redirect(['action' => 'index']);
        }

        $this->Flash->error('Could not delete the user. Please try again.');
        return $this->redirect(['action' => 'edit', $id]);
    }
}
