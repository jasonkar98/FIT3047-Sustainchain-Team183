<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentsController extends AppController
{
    public function checkout(): void
    {
        $session = $this->request->getSession();
        $cartQtys = $session->read('Cart.items') ?? [];

        $cartQtys = array_filter(
            $cartQtys,
            fn($qty, $id) => is_int($id) && is_int($qty) && $qty > 0,
            ARRAY_FILTER_USE_BOTH
        );

        if (empty($cartQtys)) {
            $this->Flash->error(__('Your cart is empty.'));
            $this->redirect(['controller' => 'Cart', 'action' => 'index']);
            return;
        }

        $products = $this->fetchTable('Products')
            ->find()
            ->where(['Products.id IN' => array_keys($cartQtys)])
            ->toArray();

        $grandTotal = 0.0;
        $totalItems = 0;
        foreach ($products as $p) {
            $qty = $cartQtys[$p->id] ?? 1;
            $grandTotal += (float)$p->price * $qty;
            $totalItems += $qty;
        }

        $stripePublishableKey = Configure::read('Stripe.publishableKey');
        $this->set(compact('products', 'cartQtys', 'grandTotal', 'totalItems', 'stripePublishableKey'));
    }

    public function createPaymentIntent(): \Cake\Http\Response
    {
        $this->request->allowMethod(['post']);
        $this->autoRender = false;

        $session = $this->request->getSession();
        $cartQtys = $session->read('Cart.items') ?? [];
        $cartQtys = array_filter(
            $cartQtys,
            fn($qty, $id) => is_int($id) && is_int($qty) && $qty > 0,
            ARRAY_FILTER_USE_BOTH
        );

        if (empty($cartQtys)) {
            return $this->response
                ->withStatus(400)
                ->withType('application/json')
                ->withStringBody(json_encode(['error' => 'Cart is empty']));
        }

        $products = $this->fetchTable('Products')
            ->find()
            ->where(['Products.id IN' => array_keys($cartQtys)])
            ->toArray();

        $grandTotal = 0.0;
        foreach ($products as $p) {
            $qty = $cartQtys[$p->id] ?? 1;
            $grandTotal += (float)$p->price * $qty;
        }

        $amountInCents = (int)round($grandTotal * 100);

        Stripe::setApiKey(Configure::read('Stripe.secretKey'));

        try {
            $paymentIntent = PaymentIntent::create([
                'amount'                    => $amountInCents,
                'currency'                  => 'aud',
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode(['clientSecret' => $paymentIntent->client_secret]));
        } catch (\Exception $e) {
            return $this->response
                ->withStatus(500)
                ->withType('application/json')
                ->withStringBody(json_encode(['error' => $e->getMessage()]));
        }
    }

    public function success(): void
    {
        $paymentIntentId = $this->request->getQuery('payment_intent');
        $redirectStatus  = $this->request->getQuery('redirect_status');

        if (empty($paymentIntentId) || $redirectStatus !== 'succeeded') {
            $this->Flash->error(__('Payment was not completed. Please try again.'));
            $this->redirect(['controller' => 'Payments', 'action' => 'checkout']);
            return;
        }

        Stripe::setApiKey(Configure::read('Stripe.secretKey'));

        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            if ($paymentIntent->status !== 'succeeded') {
                $this->Flash->error(__('Payment was not successful. Please try again.'));
                $this->redirect(['controller' => 'Payments', 'action' => 'checkout']);
                return;
            }

            $session = $this->request->getSession();

            // Idempotency: prevent duplicate orders if user refreshes
            $sessionKey = 'Order.intent_' . md5($paymentIntentId);
            $existingOrderId = $session->read($sessionKey);
            if ($existingOrderId) {
                $order = $this->fetchTable('Orders')->get($existingOrderId);
                $this->set(compact('order'));
                return;
            }

            $identity = $this->Authentication->getIdentity();
            $userId   = $identity->get('id');

            $cartQtys = $session->read('Cart.items') ?? [];
            $products = !empty($cartQtys)
                ? $this->fetchTable('Products')
                    ->find()
                    ->where(['Products.id IN' => array_keys($cartQtys)])
                    ->toArray()
                : [];

            $grandTotal = 0.0;
            foreach ($products as $p) {
                $qty = $cartQtys[$p->id] ?? 1;
                $grandTotal += (float)$p->price * $qty;
            }

            $ordersTable     = $this->fetchTable('Orders');
            $orderItemsTable = $this->fetchTable('OrderItems');

            // Wrap order header + line items in a transaction. If any line
            // item fails to write we don't want a phantom order with no
            // contents (and no per-product sales data).
            $order = $ordersTable->getConnection()->transactional(function () use (
                $ordersTable, $orderItemsTable, $userId, $grandTotal, $products, $cartQtys
            ) {
                $order = $ordersTable->newEntity([
                    'user_id'      => $userId,
                    'order_number' => 'SC-' . strtoupper(bin2hex(random_bytes(4))),
                    'total_amount' => $grandTotal,
                    'status'       => 'paid',
                ]);
                $ordersTable->saveOrFail($order);

                foreach ($products as $p) {
                    $qty = (int)($cartQtys[$p->id] ?? 1);
                    if ($qty < 1) {
                        continue;
                    }

                    // Snapshot the unit price at time of purchase so future
                    // price/discount changes don't rewrite history.
                    $unitPrice = (float)$p->price;
                    if (!empty($p->discount) && $p->discount > 0) {
                        $unitPrice = $unitPrice * (1 - (float)$p->discount / 100);
                    }

                    $item = $orderItemsTable->newEntity([
                        'order_id'   => $order->id,
                        'product_id' => $p->id,
                        'quantity'   => $qty,
                        'unit_price' => round($unitPrice, 2),
                    ]);
                    $orderItemsTable->saveOrFail($item);
                }

                return $order;
            });

            $session->write($sessionKey, $order->id);
            $session->delete('Cart.items');

            $this->set(compact('order', 'grandTotal', 'products', 'cartQtys'));
        } catch (\Exception $e) {
            $this->Flash->error(__('There was an error processing your order. Please contact support.'));
            $this->redirect(['controller' => 'Cart', 'action' => 'index']);
        }
    }
}
