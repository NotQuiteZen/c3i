<?php

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\NotFoundException;
use App\Form\TestForm;

/**
 * Class SetupsController
 * @package App\Controller
 */
class SetupsController extends AppController {

    /**
     * Index method
     */
    public function index() {

        # Throw a 404 when we're not in debug mode
        if ( ! Configure::read('debug')) {
            throw new NotFoundException();
        }

        $this->Snackbar->add(
            'Welcome to c3i!', [
            'color' => 'success',
            'align' => 'right',
//            'multi-line' => true,
            'button' => [
                'color' => 'white',
                'url' => [
                    'controller' => 'Setups',
                    'action' => 'index',
                ],
                'text' => 'Cool',
            ],
        ]);

    }

    public function forms() {

    }

    public function testform() {

        $testform = new TestForm();
        if ($this->getRequest()->is('post')) {
            if ($testform->execute($this->getRequest()->getData())) {
                debug('Yo');
            } else {
                debug('No');
            }
        }

        $this->set(compact('testform'));

    }

}
