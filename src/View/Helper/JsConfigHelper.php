<?php

namespace App\View\Helper;

use Cake\Core\Configure;
use Cake\Utility\Hash;
use Cake\View\Helper;

/**
 * Class JsConfigHelper
 * @package App\View\Helper
 *
 * @property Helper\HtmlHelper $Html
 */
class JsConfigHelper extends Helper {

    /**
     * @var array
     */
    public $helpers = [
        'Html',
    ];


    private $_jsConfig = [];

    /**
     * @param      $value
     * @param bool $path
     */
    public function set($value, $path = false) {
        $this->_jsConfig = Hash::insert($this->_jsConfig, $this->_getPath($path), $value);

    }

    /**
     * @param $path
     *
     * @return mixed
     */
    public function get($path = false) {
        return Hash::get($this->_jsConfig, $this->_getPath($path));
    }


    /**
     * @param string $obj_name Will be accessible through window.$obj_name
     *
     * @return string
     */
    public function getObject($obj_name = 'JsConfig') {

        $bitmask = JSON_NUMERIC_CHECK|JSON_UNESCAPED_SLASHES;
        if (Configure::read('debug')) {
            $bitmask = $bitmask|JSON_PRETTY_PRINT;
        }

        $config = json_encode($this->_jsConfig, $bitmask);

        return $this->Html->scriptBlock("\nwindow." . $obj_name . " = " . $config . ";");
    }

    /**
     * Normalizes the path to controller.action if its false
     *
     * @param $path
     *
     * @return string
     */
    private function _getPath($path) {
        if ( ! $path) {
            return $this->request->getParam('controller') . '.' . $this->request->getParam('action');
        }

        return $path;
    }
}
