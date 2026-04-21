<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FiltertagsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FiltertagsTable Test Case
 */
class FiltertagsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FiltertagsTable
     */
    protected $Filtertags;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Filtertags',
        'app.Products',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Filtertags') ? [] : ['className' => FiltertagsTable::class];
        $this->Filtertags = $this->getTableLocator()->get('Filtertags', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Filtertags);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\FiltertagsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
