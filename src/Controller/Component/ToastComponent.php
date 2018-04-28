<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

/**
 * Toast component
 */
class ToastComponent extends Component {

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'session_key' => 'Toast',
    ];


    public function initialize(array $config) {
        parent::initialize($config);
        $this->controller = $this->_registry->getController();
        $this->session = $this->controller->getRequest()->getSession();

    }

    /**
     * Set a toast message and redirect
     *
     * @param       $message
     * @param       $url
     * @param array $options
     *
     * @return CakeResponse|bool
     */
    public function redirect($message, $url, $options = []) {
        $options['redirect'] = $url;

        return $this->add($message, $options);
    }

    /**
     * Set a toast message
     *
     * @param       $message
     * @param array $options
     *
     * @return CakeResponse|bool
     */
    public function add($message, $options = []) {

        $toast = [
            'html' => $message,
            'displayLength' => $this->_getOption($options, 'displayLength') ?: 4000,
            'inDuration' => $this->_getOption($options, 'inDuration') ?: 300,
            'outDuration' => $this->_getOption($options, 'outDuration') ?: 375,
            'completeCallback' => $this->_getOption($options, 'completeCallback') ?: null,
            'activationPercent' => $this->_getOption($options, 'activationPercent') ?: 0.8,
            'button' => $this->_getOption($options, 'button') ?: null,
        ];

        $class = explode(' ', $this->_getOption($options, 'class', ''));

        # Check if we dont want the toast to timeout
        $noTimeout = $this->_getOption($options, 'no-timeout', false);
        if ($noTimeout) {
            $toast['displayLength'] = 'Infinity';
        }

        # Toast color
        $color = $this->_getOption($options, 'color');
        if ($color) {
            $class[] = $color;
        }

        # Toast text color
        $textColor = $this->_getOption($options, 'text-color');
        if ($textColor) {
            $class[] = $textColor . '-text';
        }

        $toast['classes'] = trim(implode(' ', $class));

        # Get redirect option
        $redirect = $this->_getOption($options, 'redirect');

        # Get the session key
        $session_key = $this->getConfig('session_key');
        
        # Fetch previously set toasts
        $toasts = $this->session->read($session_key);

        # If we don't have any, make the toasts an empty array
        if ( ! $toasts) {
            $toasts = [];
        }

        # Append the new message
        $toasts[] = $toast;

        # Write back to the session
        $write_success = $this->session->write($session_key, $toasts);

        # Redirect
        if ($redirect) {
            return $this->Controller->redirect($redirect);
        }

        return $write_success;
    }

    private function _getOption(&$options, $name, $default = null, $unset = true) {
        if ( ! is_array($options)) {
            debug('$options should be an array');

            return $default;
        }
        # Option is array key
        if (array_key_exists($name, $options)) {
            $value = $options[$name];
            if ($unset) {
                unset($options[$name]);
            }

            return $value;
        }
        # Option is array value, with a numeric key
        $key = array_search($name, $options, true);
        if (is_numeric($key)) {
            if ($unset) {
                unset($options[$key]);
            }

            return true;
        }

        # Option not found
        return $default;
    }

}
