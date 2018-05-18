<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

/**
 * Class SnackbarComponent
 *
 * @package App\Controller\Component
 */
class SnackbarComponent extends Component {

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'session_key' => 'Snackbars',
    ];

    /**
     * @var \Cake\Controller\Controller
     */
    public $controller;

    /**
     * @var \Cake\Http\Session
     */
    public $session;

    public function initialize(array $config) {
        parent::initialize($config);
        $this->controller = $this->_registry->getController();
        $this->session = $this->controller->getRequest()->getSession();
    }

    /**
     * @param       $message
     * @param       $url
     * @param array $options
     */
    public function redirect($message, $url, $options = []) {
        $options['redirect'] = $url;

        $this->add($message, $options);
    }

    /**
     * @param       $message
     * @param array $options
     */
    public function add($message, $options = []) {

        $snackbar = [
            'html' => $message,
            'timeout' => $this->_getOption($options, 'timeout') ?: 4000,
            'button' => $this->_getOption($options, 'button') ?: null,
        ];

        $class = explode(' ', $this->_getOption($options, 'class', ''));

        # Check if we dont want the snackbar to timeout
        $noTimeout = $this->_getOption($options, 'no-timeout', false);
        if ($noTimeout) {
            $snackbar['timeout'] = 'Infinity';
        }

        # Snackbar color
        $color = $this->_getOption($options, 'color');
        if ($color) {
            $class[] = 'bg-' . $color;
        }

        # Snackbar text color
        $textColor = $this->_getOption($options, 'text-color');
        if ($textColor) {
            $class[] = $textColor . '-text';
        }

        # Alignment
        $align = $this->_getOption($options, 'align');
        if ($align) {
            $class[] = 'snackbar-' . $align;
        }

        # Multiline
        $multiLine = $this->_getOption($options, 'multi-line');

        # No multiline in options? Check the message length
        if (is_null($multiLine)) {
            $multiLine = (bool) (strlen(strip_tags($message)) > 50);
        }

        if ($multiLine) {
            $class[] = 'snackbar-multi-line';
        }

        # Get classes
        $snackbar['classes'] = trim(implode(' ', $class));

        # Get redirect option
        $redirect = $this->_getOption($options, 'redirect');

        # Get the session key
        $session_key = $this->getConfig('session_key');

        # Fetch previously set snackbars
        $snackbars = $this->session->read($session_key);

        # If we don't have any, make the snackbars an empty array
        if ( ! $snackbars) {
            $snackbars = [];
        }

        # Append the new message
        $snackbars[] = $snackbar;

        # Write back to the session
        $this->session->write($session_key, $snackbars);

        # Redirect
        if ($redirect) {
            $this->controller->redirect($redirect);
        }
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
