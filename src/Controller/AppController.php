<?php

namespace App\Controller;

use App\Controller\Component\ToastComponent;
use Cake\Controller\Controller;

/**
 * Class AppController
 * @package App\Controller
 *
 * @property ToastComponent $Toast
 */
class AppController extends Controller {

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize() {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Toast');
    }
}
