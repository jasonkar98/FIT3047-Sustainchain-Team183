<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\UsersTable;
use Cake\I18n\DateTime;
use Cake\Mailer\Mailer;
use Cake\Utility\Security;

/**
 * Auth Controller
 *
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 */
class AuthController extends AppController
{
    /**
     * @var \App\Model\Table\UsersTable $Users
     */
    private UsersTable $Users;

    /**
     * Controller initialize override
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        // By default, CakePHP will (sensibly) default to preventing users from accessing any actions on a controller.
        // These actions, however, are typically required for users who have not yet logged in.
        $this->Authentication->allowUnauthenticated(['login', 'register', 'forgetPassword', 'resetPassword']);

        // CakePHP loads the model with the same name as the controller by default.
        // Since we don't have an Auth model, we'll need to load "Users" model when starting the controller manually.
        $this->Users = $this->fetchTable('Users');
    }

    /**
     * Register method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function register()
    {

        $user = $this->Users->newEmptyEntity();
        // $session = $this->request->getSession();
        $this->set('step', 1);

        if ($this->request->is('post')) {

            $data = $this->request->getData();


             if (!empty($data['role_selected'])) {
                $this->set('step', 2);

                $this->set('role_selected', $data['role']);

            } elseif (!empty($data['submit_details'])) {

                // Handle the optional manufacturer profile image. Done before
                // patchEntity so the saved filename (not the UploadedFile
                // object) reaches the entity. Mirrors the pattern in
                // ProductsController::add but writes into /img/profiles/.
                $data['profile'] = $this->processProfileUpload($data['profile'] ?? null);

                $user = $this->Users->patchEntity($user, $data);

                if ($this->Users->save($user)) {
                    $this->Flash->success('You have been registered. Please log in. ');

                    return $this->redirect(['action' => 'login']);
                }
                $this->Flash->error('The user could not be registered. Please, try again.');
                $this->set('step', 2);

                // Re-expose the role to the template so the hidden role
                // field on the step-2 form keeps its value through the
                // failed-validation re-render. Without this, the template
                // hits an undefined-variable warning on $role_selected.
                $this->set('role_selected', $data['role'] ?? null);
            }
        }

        $this->set(compact('user'));
    }

    public function view($id = null)
    {

        $user = $this->Authentication->getIdentity();

        // Top 3 most-sold products in the last 30 days (only meaningful for
        // sellers / manufacturers / farmers — buyers will simply see an
        // empty-state message). Delegated to InnovatorsController so the
        // ranking logic lives in one place.
        $topProducts = [];
        if ($user) {
            $innovators = new \App\Controller\InnovatorsController($this->request);
            $topProducts = $innovators->topProductsFor((int)$user->get('id'));
        }

        $this->set(compact('user', 'topProducts'));
    }

    public function edit($id = null)
    {
        $user = $this->Users->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            // Handle the optional profile-image upload. If the user attached
            // a new image, replace; if they left the field blank, keep the
            // existing profile string by stripping the empty upload from
            // the patch payload entirely.
            $newProfile = $this->processProfileUpload($data['profile'] ?? null);
            if ($newProfile !== null) {
                $data['profile'] = $newProfile;
            } else {
                unset($data['profile']);
            }

            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $this->Authentication->setIdentity($user->toArray());
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'view', $user->get('id')]);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        $user['is_active'] = 0;
        // $user = $this->Users->patchEntity($user, $this->request->getData());
        if ($this->Users->save($user)) {
            $this->Flash->success(__('Your account has been disabled. You will soon be notified of its official deletion.'));
            $this->logout();
        } else {
            $this->Flash->error(__('Your account could not be deleted. Please, try again.'));
        }

        return $this->redirect(['controller' => 'Pages', 'action' => 'landingPage']);
    }

    /**
     * Forget Password method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful email send, renders view otherwise.
     */
    public function forgetPassword()
    {
        if ($this->request->is('post')) {
            // Retrieve the user entity by provided email address
            // Note findBy* is a magic/dynamic finder
            $user = $this->Users->findByEmail($this->request->getData('email'))->first();
            if ($user) {
                // Set nonce and expiry date
                $user->nonce = Security::randomString(128);
                $user->nonce_expiry = new DateTime('7 days');
                if ($this->Users->save($user)) {
                    // Now let's send the password reset email
                    $mailer = new Mailer('default');

                    // email basic config
                    $mailer
                        ->setEmailFormat('both')
                        ->setTo($user->email)
                        ->setSubject('Reset your account password');

                    // select email template
                    $mailer
                        ->viewBuilder()
                        ->setTemplate('reset_password');

                    // transfer required view variables to email template
                    $mailer
                        ->setViewVars([
                            'first_name' => $user->first_name,
                            'last_name' => $user->last_name,
                            'nonce' => $user->nonce,
                            'email' => $user->email,
                        ]);

                    //Send email
                    if (!$mailer->deliver()) {
                        // Just in case something goes wrong when sending emails
                        $this->Flash->error('We have encountered an issue when sending you emails. Please try again. ');

                        return $this->render(); // Skip the rest of the controller and render the view
                    }
                } else {
                    // Just in case something goes wrong when saving nonce and expiry
                    $this->Flash->error('We are having issue to reset your password. Please try again. ');

                    return $this->render(); // Skip the rest of the controller and render the view
                }
            }

            /*
             * **This is a bit of a special design**
             * We don't tell the user if their account exists, or if the email has been sent,
             * because it may be used by someone with malicious intent. We only need to tell
             * the user that they'll get an email.
             */
            $this->Flash->success(
                'Please check your inbox (or spam folder) for ' .
                'an email regarding how to reset your account password. ',
            );

            return $this->redirect(['action' => 'login']);
        }
    }

    /**
     * Reset Password method
     *
     * @param string|null $nonce Reset password nonce
     * @return \Cake\Http\Response|null|void Redirects on successful password reset, renders view otherwise.
     */
    public function resetPassword(?string $nonce = null)
    {
        // Bounce user away if no nonce provided
        if (!$nonce) {
            return $this->redirect(['action' => 'login']);
        }

        // Note findBy* is a magic/dynamic finder
        $user = $this->Users->findByNonce($nonce)->first();

        // If nonce cannot find the user, or nonce is expired, prompt for re-reset password
        if (!$user || $user->nonce_expiry < DateTime::now()) {
            $this->Flash->error('Your link is invalid or expired. Please try again.');

            return $this->redirect(['action' => 'forgetPassword']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            // Used a different validation set in Model/Table file to ensure both fields are filled
            $user = $this->Users->patchEntity($user, $this->request->getData(), ['validate' => 'resetPassword']);

            // Also clear the nonce-related fields on successful password resets.
            // This ensures that the reset link can't be used a second time.
            $user->nonce = null;
            $user->nonce_expiry = null;

            if ($this->Users->save($user)) {
                $mailer = new Mailer('default');
                $mailer
                    ->setEmailFormat('both')
                    ->setTo($user->email)
                    ->setSubject('Your SustainChain password has been changed');
                $mailer->viewBuilder()->setTemplate('password_changed')->setLayout('sustainchain');
                $mailer->setViewVars([
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                ]);
                $mailer->deliver();

                $this->Flash->success('Your password has been successfully reset. Please login with your new password.');

                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error('The password cannot be reset. Please try again.');
        }

        $this->set(compact('user'));
    }

    /**
     * Change Password method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function changePassword(?string $id = null)
    {
        $user = $this->Users->get($id, ['contain' => []]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            // Used a different validation set in Model/Table file to ensure both fields are filled
            $user = $this->Users->patchEntity($user, $this->request->getData(), ['validate' => 'resetPassword']);
            if ($this->Users->save($user)) {
                $this->Flash->success('The user has been saved.');

                return $this->redirect(['controller' => 'Users', 'action' => 'index']);
            }
            $this->Flash->error('The user could not be saved. Please, try again.');
        }
        $this->set(compact('user'));
    }

    /**
     * Login method
     *
     * @return \Cake\Http\Response|null|void Redirect to location before authentication
     */
    public function login()
    {
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();

        if ($result && $result->isValid()) {
            if ($this->request->is('post')) {
                $identity = $this->Authentication->getIdentity();
                $isAdmin = $identity && ($identity->get('role') === 'admin');

                $fallbackLocation = $isAdmin
                    ? ['prefix' => 'Admin', 'controller' => 'Dashboard', 'action' => 'index']
                    : ['controller' => 'Dashboard', 'action' => 'index'];

                return $this->redirect($this->Authentication->getLoginRedirect() ?? $fallbackLocation);
            }
        }

        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error('Email address and/or Password is incorrect. Please try again. ');
        }
    }
    /**
     * Logout method
     *
     * @return \Cake\Http\Response|null|void
     */
    /**
     * Handle an uploaded profile-image file. Returns the stored filename
     * (just the basename, no path) so it can be patched onto the user
     * entity, or null when no upload was provided / the upload was empty.
     *
     * Saves into webroot/img/profiles/ with a uniqid prefix so two users
     * uploading "logo.png" don't collide.
     *
     * @param mixed $file Either a Psr\Http\Message\UploadedFileInterface
     *                    (Cake's form upload) or null when the field was
     *                    left blank.
     * @return string|null Stored filename, or null when no usable file.
     */
    private function processProfileUpload($file): ?string
    {
        if (!$file || !is_object($file) || !method_exists($file, 'getClientFilename')) {
            return null;
        }
        $clientName = $file->getClientFilename();
        if (empty($clientName)) {
            return null;
        }

        // Normalise the basename and prepend a uniqid so collisions are
        // statistically impossible.
        $safeBase = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($clientName));
        $stored = uniqid('', false) . '_' . $safeBase;

        $targetDir = WWW_ROOT . 'img' . DS . 'profiles';
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0775, true);
        }

        try {
            $file->moveTo($targetDir . DS . $stored);
        } catch (\Throwable $e) {
            // Swallow — if the move fails, fall back to "no profile" rather
            // than blowing up the whole registration / edit flow.
            return null;
        }

        return $stored;
    }

    public function logout()
    {
        // We only need to log out a user when they're logged in
        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
            $this->Authentication->logout();

            $this->Flash->success('You have been logged out successfully. ');
        }

        // Otherwise just send them to the login page
        return $this->redirect(['controller' => 'Auth', 'action' => 'login']);
    }
}
