<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Product extends Entity
{
    // These fields can be filled from a form submission
    protected array $_accessible = [
        'name'        => true,
        'description' => true,
        'price'       => true,
        'category'    => true,
        'seller_id'   => true,
        'image_url'   => true,
    ];
}