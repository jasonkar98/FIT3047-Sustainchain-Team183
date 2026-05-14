<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;

/**
 * Innovators Controller
 *
 * Powers the public Discover Innovators landing page (top 10 manufacturers by
 * 30-day sales) and an individual innovator detail page that mirrors the
 * "My account" view for the selected manufacturer / farmer.
 */
class InnovatorsController extends AppController
{
    /**
     * Roles eligible to be featured on the Discover Innovators landing page.
     */
    private const FEATURED_ROLE = 'manufacturer';

    /**
     * Roles that have a public detail page accessible via /innovators/{id}.
     * Manufacturers are the headline use case; farmers are also "innovators"
     * upstream in the supply chain and get the same treatment.
     */
    private const DETAIL_ROLES = ['manufacturer', 'farmer'];

    public function initialize(): void
    {
        parent::initialize();
        // Discover Innovators is public-facing — no login required.
        $this->Authentication->allowUnauthenticated(['index', 'view']);
    }

    /**
     * Landing page — top 10 manufacturers ranked by units sold in the last
     * 30 days. Each card carries its top 3 most-sold products in the same
     * window so we can preview them on the card or share the section with
     * the detail page.
     */
    public function index(): void
    {
        $usersTable      = $this->fetchTable('Users');
        $orderItemsTable = $this->fetchTable('OrderItems');

        $since = (new \DateTimeImmutable('-30 days'))->format('Y-m-d H:i:s');

        // Per-manufacturer total units sold in the last 30 days.
        // Joins: order_items -> products (.user_id is the manufacturer) -> users
        // Filters: items created within window, owning user is a manufacturer
        // and active (if the activation flag exists in this DB).
        $rankingQuery = $orderItemsTable->find()
            ->select([
                'manufacturer_id' => 'Products.user_id',
                'units_sold'      => $orderItemsTable->find()->func()->sum('OrderItems.quantity'),
            ])
            ->innerJoinWith('Products', function ($q) {
                return $q->innerJoinWith('Users', function ($qq) {
                    return $qq->where(['Users.role' => self::FEATURED_ROLE]);
                });
            })
            ->where(['OrderItems.created >=' => $since])
            ->groupBy(['Products.user_id'])
            ->orderBy(['units_sold' => 'DESC'])
            ->limit(10)
            ->disableHydration();

        $rankingRows = $rankingQuery->toArray();

        $manufacturerIds = array_filter(array_map(
            static fn ($row) => (int)($row['manufacturer_id'] ?? 0),
            $rankingRows
        ));

        // Map manufacturer_id => units_sold for the ranking metric on each card.
        $unitsByManufacturer = [];
        foreach ($rankingRows as $row) {
            $unitsByManufacturer[(int)$row['manufacturer_id']] = (int)$row['units_sold'];
        }

        // Hydrate the manufacturer user entities in the same ranking order.
        $manufacturers = [];
        if (!empty($manufacturerIds)) {
            $userRows = $usersTable->find()
                ->where(['Users.id IN' => $manufacturerIds, 'Users.role' => self::FEATURED_ROLE])
                ->all()
                ->indexBy('id')
                ->toArray();

            foreach ($manufacturerIds as $mid) {
                if (isset($userRows[$mid])) {
                    $u = $userRows[$mid];
                    $u->units_sold_30d = $unitsByManufacturer[$mid] ?? 0;
                    $manufacturers[] = $u;
                }
            }
        }

        $this->set(compact('manufacturers'));
    }

    /**
     * Public detail page for a single manufacturer / farmer. Reuses the
     * "My account" template shape but with edit/delete actions stripped.
     */
    public function view(?string $id = null): void
    {
        $usersTable = $this->fetchTable('Users');

        $user = $usersTable->find()
            ->where(['Users.id' => $id])
            ->first();

        if (!$user || !in_array($user->role, self::DETAIL_ROLES, true)) {
            throw new NotFoundException('Innovator not found.');
        }

        // Block detail page for deactivated accounts so suspended innovators
        // don't get free real estate. Field is optional (the column might not
        // exist on every dev DB yet).
        if (isset($user->is_active) && (int)$user->is_active === 0) {
            throw new NotFoundException('Innovator not found.');
        }

        $topProducts = $this->topProductsFor((int)$user->id);

        $this->set(compact('user', 'topProducts'));
    }

    /**
     * Top 3 most-sold products for a given manufacturer over the last 30 days.
     * Public so the my-account view can reuse the same logic.
     *
     * @return array<\App\Model\Entity\Product>
     */
    public function topProductsFor(int $manufacturerId): array
    {
        $orderItemsTable = $this->fetchTable('OrderItems');
        $productsTable   = $this->fetchTable('Products');

        $since = (new \DateTimeImmutable('-30 days'))->format('Y-m-d H:i:s');

        $rankingRows = $orderItemsTable->find()
            ->select([
                'product_id'  => 'OrderItems.product_id',
                'units_sold'  => $orderItemsTable->find()->func()->sum('OrderItems.quantity'),
            ])
            ->innerJoinWith('Products', function ($q) use ($manufacturerId) {
                return $q->where(['Products.user_id' => $manufacturerId]);
            })
            ->where(['OrderItems.created >=' => $since])
            ->groupBy(['OrderItems.product_id'])
            ->orderBy(['units_sold' => 'DESC'])
            ->limit(3)
            ->disableHydration()
            ->toArray();

        $productIds = array_filter(array_map(
            static fn ($r) => (int)($r['product_id'] ?? 0),
            $rankingRows
        ));

        if (empty($productIds)) {
            return [];
        }

        $units = [];
        foreach ($rankingRows as $r) {
            $units[(int)$r['product_id']] = (int)$r['units_sold'];
        }

        $productRows = $productsTable->find()
            ->where(['Products.id IN' => $productIds])
            ->all()
            ->indexBy('id')
            ->toArray();

        $ordered = [];
        foreach ($productIds as $pid) {
            if (isset($productRows[$pid])) {
                $p = $productRows[$pid];
                $p->units_sold_30d = $units[$pid] ?? 0;
                $ordered[] = $p;
            }
        }

        return $ordered;
    }
}
