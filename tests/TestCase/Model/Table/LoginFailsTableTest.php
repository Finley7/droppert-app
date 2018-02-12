<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LoginFailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LoginFailsTable Test Case
 */
class LoginFailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\LoginFailsTable
     */
    public $LoginFails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.login_fails'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('LoginFails') ? [] : ['className' => LoginFailsTable::class];
        $this->LoginFails = TableRegistry::get('LoginFails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LoginFails);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
