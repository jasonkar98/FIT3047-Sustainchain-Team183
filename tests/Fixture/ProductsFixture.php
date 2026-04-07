<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProductsFixture
 */
class ProductsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'product_id' => 1,
                'seller_id' => 1,
                'product_name' => 'Lorem ipsum dolor sit amet',
                'seller_name' => 'Lorem ipsum dolor sit amet',
                'product_image' => 'Lorem ipsum dolor sit amet',
                'product_description' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
