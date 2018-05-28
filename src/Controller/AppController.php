<?php

namespace App\Controller;

use Cake\Controller\Controller;

/**
 * Class AppController
 * @package App\Controller
 */
class AppController extends Controller {

    public function initialize() {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Material.Snackbar');
    }
}
