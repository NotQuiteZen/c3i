<?php

namespace App\View\Helper;

use Cake\Utility\Inflector;
use Cake\View\Helper;

/**
 * Class JsLoaderHelper
 * @package App\View\Helper
 *
 * @property HtmlHelper $Html
 */
class JsLoaderHelper extends Helper {

    /**
     * @var array
     */
    public $helpers = [
        'Html',
    ];

    /**
     * @return null|string
     */
    public function getViewScript() {

        # Build the Controller name
        $controller = Inflector::pluralize(Inflector::camelize($this->request->getParam('controller')));

        # Build the script file
        $script_file = DS . 'dist' . DS . $controller . DS . $this->request->getParam('action') . '.js';

        # The abs path to the script file
        $abs_script_file = WWW_ROOT . $script_file;

        # If it exists, load that
        if (file_exists($abs_script_file)) {
            return $this->Html->script($script_file);
        }

        # The Controller.action.js file does not exist? Load the default
        $default_script_file = DS . 'dist' . DS . 'default.js';

        # Return
        return $this->Html->script($default_script_file);
    }

}
