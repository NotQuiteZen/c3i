<?php

namespace App\View\Helper;

use Cake\Core\Configure;
use Cake\Utility\Hash;
use Cake\View\Helper;

/**
 * Class SnackbarHelper
 *
 * @package App\View\Helper
 *
 * @property Helper\HtmlHelper $Html
 */
class SnackbarHelper extends Helper {

    /**
     * @var array
     */
    public $helpers = [
        'Html',
    ];

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'session_key' => 'Snackbars',
    ];

    /**
     * @param string $return_type
     * Can be:
     * 'array' => (default) Will return the snackbars messages as an array
     * 'window_variable' => Will output a <script> block with window.snackbar_messages contain the snackbar array
     *
     * @return array|string
     */
    public function render($return_type = 'array') {

        # Read and delete the snackbar session
        $snackbars = $this->request->getSession()->consume($this->getConfig('session_key'));

        if ( ! $snackbars) {
            $snackbars = [];
        }

        foreach ($snackbars as &$snackbar) {
            $snackbar['button'] = $this->_button($snackbar);
        }

        # Setup the json bitmask
        $bitmask = JSON_NUMERIC_CHECK|JSON_UNESCAPED_SLASHES;
        if (Configure::read('debug')) {
            $bitmask = $bitmask|JSON_PRETTY_PRINT;
        }

        # If we want it as a window variable in a script tag
        if ($return_type === 'window_variable') {
            # Build the json object
            $snackbars_object = json_encode($snackbars, $bitmask);

            return $this->Html->tag('script', "\nwindow.snackbar_messages" . " = " . $snackbars_object . ";\n");
        }

        return $snackbars;
    }

    private function _button($snackbar) {
        $buttonOptions = Hash::get($snackbar, 'button');

        # No button? Return the html
        if ( ! $buttonOptions) {
            return null;
        }

        # Get text and url
        $text = Hash::get($buttonOptions, 'text');
        unset($buttonOptions['text']);

        $url = Hash::get($buttonOptions, 'url');
        unset($buttonOptions['url']);

        # Text color
        $textColor = Hash::get($buttonOptions, 'color');
        unset($buttonOptions['color']);
        if ($textColor) {
            $buttonOptions = $this->Html->addClass($buttonOptions, 'text-' . $textColor);
        }

        # Add materialize button classes
        $buttonOptions = $this->Html->addClass($buttonOptions, 'snackbar-btn');

        # If we have an url, make the button a link
        if ($url) {
            return $this->Html->link($text, $url, $buttonOptions);
        }

        # Return button
        return $this->Html->tag('button', $text, $buttonOptions);
    }
}
