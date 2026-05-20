<?php
declare(strict_types=1);

namespace App\Controller;

class CartController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->Authentication->allowUnauthenticated(['index', 'add', 'remove', 'updateQty']);
    }

    public function index(): void
    {
        $session = $this->request->getSession();
        $cartQtys = $session->read('Cart.items') ?? [];

        // Discard any stale/malformed entries — keys must be ints, values must be positive ints
        $cartQtys = array_filter(
            $cartQtys,
            fn($qty, $id) => is_int($id) && is_int($qty) && $qty > 0,
            ARRAY_FILTER_USE_BOTH
        );

        $products = [];
        if (!empty($cartQtys)) {
            $products = $this->fetchTable('Products')
                ->find()
                ->where(['Products.id IN' => array_keys($cartQtys)])
                ->toArray();

            // Keep only quantities for products that actually exist
            $foundIds = array_map(fn($p) => $p->id, $products);
            $cartQtys = array_intersect_key($cartQtys, array_flip($foundIds));
            $session->write('Cart.items', $cartQtys);
        }

        $this->set(compact('products', 'cartQtys'));
    }

    public function add($id = null): \Cake\Http\Response
    {
        $this->request->allowMethod(['post']);

        if (empty($id) || !$this->fetchTable('Products')->exists(['id' => $id])) {
            $this->Flash->error(__('Product not found.'));
            return $this->redirect($this->referer(['controller' => 'Products', 'action' => 'index']));
        }

        $session = $this->request->getSession();
        $cartQtys = $session->read('Cart.items') ?? [];

        $cartQtys[(int)$id] = ($cartQtys[(int)$id] ?? 0) + 1;
        $session->write('Cart.items', $cartQtys);
        $this->Flash->success(__('Product added to your cart.'));

        return $this->redirect($this->referer(['controller' => 'Products', 'action' => 'index']));
    }

    public function updateQty($id = null): \Cake\Http\Response
    {
        $this->request->allowMethod(['post']);

        $session = $this->request->getSession();
        $cartQtys = $session->read('Cart.items') ?? [];
        $delta = (int)($this->request->getData('delta') ?? 0);

        $current = $cartQtys[(int)$id] ?? 0;
        $new = max(0, $current + $delta);

        if ($new === 0) {
            unset($cartQtys[(int)$id]);
        } else {
            $cartQtys[(int)$id] = $new;
        }

        $session->write('Cart.items', $cartQtys);
        return $this->redirect(['controller' => 'Cart', 'action' => 'index']);
    }

    public function remove($id = null): \Cake\Http\Response
    {
        $this->request->allowMethod(['post']);

        $session = $this->request->getSession();
        $cartQtys = $session->read('Cart.items') ?? [];
        unset($cartQtys[(int)$id]);
        $session->write('Cart.items', $cartQtys);

        $this->Flash->success(__('Product removed from your cart.'));
        return $this->redirect(['controller' => 'Cart', 'action' => 'index']);
    }

    public function setQty($productId = null)
    {
        $this->request->allowMethod(['post']);
        $qty = max(1, (int)$this->request->getData('qty'));
        $cart = $this->request->getSession()->read('Cart') ?? [];
        $cart[$productId] = $qty;
        $this->request->getSession()->write('Cart', $cart);
        return $this->response->withType('application/json')->withStringBody(json_encode(['ok' => true]));
    }
}
