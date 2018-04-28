<?php
namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\ToastComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\ToastComponent Test Case
 */
class ToastComponentTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Controller\Component\ToastComponent
     */
    public $Toast;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Toast = new ToastComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Toast);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
