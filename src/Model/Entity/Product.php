<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Product Entity
 *
 * @property int $product_id
 * @property int $seller_id
 * @property string $product_name
 * @property string $seller_name
 * @property string $product_image
 * @property string $product_description
 */
class Product extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'seller_id' => true,
        'product_name' => true,
        'seller_name' => true,
        'product_image' => true,
        'product_description' => true,
    ];
}
