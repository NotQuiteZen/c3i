<?php

namespace App\View\Helper;

use Cake\Core\Configure;
use Cake\Utility\Hash;
use Cake\View\Helper;

/**
 * Class ToastHelper
 * @package App\View\Helper
 *
 * @property Helper\HtmlHelper $Html
 */
class ToastHelper extends Helper {

    /**
     * @var array
     */
    public $helpers = ['Html'];

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'session_key' => 'Toast',
    ];

    /**
     * @param string $return_type
     * Can be:
     * 'array' => Will return the toasts messages as an array
     * 'window_variable' => Will output a <script> block with window.toast_messages contain the toast array
     * 'auto_init' => Will output a <script> block and init Materialize toasts with M.toast() for all toast messages;
     *
     * @return mixed|string
     */
    public function render($return_type = 'auto_init') {

        # Read and delete the toast session
        $toasts = $this->request->getSession()->consume($this->getConfig('session_key'));

        foreach ($toasts as &$toast) {
            $toast['html'] = $this->_button($toast);
        }

        # Return the array
        if ($return_type === 'array') {
            return $toasts;
        }

        # Setup the json bitmask
        $bitmask = JSON_NUMERIC_CHECK|JSON_UNESCAPED_SLASHES;
        if (Configure::read('debug')) {
            $bitmask = $bitmask|JSON_PRETTY_PRINT;
        }

        # If we want it as a window variable in a script tag
        if ($return_type === 'window_variable') {
            # Build the json object
            $toasts_object = json_encode($toasts, $bitmask);

            return $this->Html->tag('script', "\nwindow.toast_messages" . " = " . $toasts_object . ";\n");
        }

        $script_content = [];
        foreach ($toasts as $toast) {
            # Build the json object
            $toasts_object = json_encode($toast, $bitmask);
            # Add M.toast() to the script content
            $script_content[] = 'M.toast(' . $toasts_object . ');';
        }

        # Return the script
        return $this->Html->tag('script', "\n" . implode(null, $script_content));
    }

    private function _button($toast) {
        $buttonOptions = Hash::get($toast, 'button');
        # No button? Return the html
        if ( ! $buttonOptions) {
            return $toast['html'];
        }
        # Get text and url
        $text = Hash::get($buttonOptions, 'text');
        $url = Hash::get($buttonOptions, 'url');
        # Button color
        $color = Hash::get($buttonOptions, 'color');
        if ($color) {
            $buttonOptions = $this->Html->addClass($buttonOptions, $color);
        }
        # Text color
        $textColor = Hash::get($buttonOptions, 'text-color');
        if ($textColor) {
            $buttonOptions = $this->Html->addClass($buttonOptions, $textColor . '-text');
        }
        # Add materialize button classes
        $buttonOptions = $this->Html->addClass($buttonOptions, 'btn-flat toast-action');

        # If we have an url, make the button a link
        if ($url) {
            return $toast['html'] . ' ' . $this->Html->link($text, $url, $buttonOptions);
        }

        # Return button
        return $toast['html'] . ' ' . $this->Html->tag('button', $text, $buttonOptions);
    }
}
