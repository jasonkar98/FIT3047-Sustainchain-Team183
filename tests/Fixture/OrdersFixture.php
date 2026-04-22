<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * OrdersFixture
 */
class OrdersFixture extends TestFixture
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
                'total_amount' => 1.5,
                'status' => 'Lorem ipsum dolor sit amet',
                'created' => '2026-04-19 05:12:23',
                'modified' => '2026-04-19 05:12:23',
            ],
        ];
        parent::init();
    }
}
