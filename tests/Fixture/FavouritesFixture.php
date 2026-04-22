<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FavouritesFixture
 */
class FavouritesFixture extends TestFixture
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
                'id' => 1,
                'user_id' => 1,
                'product_id' => 1,
                'created' => '2026-04-10 07:54:40',
                'modified' => '2026-04-10 07:54:40',
            ],
        ];
        parent::init();
    }
}
