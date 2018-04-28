<?php
namespace App\Test\TestCase\View\Helper;

use App\View\Helper\ToastHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * App\View\Helper\ToastHelper Test Case
 */
class ToastHelperTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\View\Helper\ToastHelper
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
        $view = new View();
        $this->Toast = new ToastHelper($view);
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
