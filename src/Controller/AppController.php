<?php

namespace App\Controller;

use App\Controller\Component\SnackbarComponent;
use Cake\Controller\Controller;

/**
 * Class AppController
 * @package App\Controller
 *
 * @property SnackbarComponent $Snackbar
 */
class AppController extends Controller {

    public function initialize() {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Snackbar');
    }
}
