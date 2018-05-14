<?php

namespace App\View\Helper;

use Cake\Utility\Hash;
use Cake\View\Helper;

/**
 * Class FormHelper
 * @package App\View\Helper
 */
class FormHelper extends Helper\FormHelper {

    protected $_defaultConfig = [
        'idPrefix' => null,
        'errorClass' => 'has-error',
        'typeMap' => [
            'string' => 'text', 'datetime' => 'datetime', 'boolean' => 'checkbox',
            'timestamp' => 'datetime', 'text' => 'textarea', 'time' => 'time',
            'date' => 'date', 'float' => 'number', 'integer' => 'number',
            'decimal' => 'number', 'binary' => 'file', 'uuid' => 'string',
        ],
        'templates' => [
            'button' => '<button{{attrs}}>{{text}}</button>',
            'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}" class="custom-control-input{{attrs.class}}"{{attrs}}>',
            'checkboxFormGroup' => '{{input}}{{label}}',
            'checkboxWrapper' => '<div class="custom-control custom-checkbox">{{label}}</div>',
            'checkboxContainer' => '<div class="form-group custom-control custom-checkbox {{required}}">{{content}}</div>',
            'dateWidget' => '<div class="row">{{year}}{{month}}{{day}}{{hour}}{{minute}}{{second}}{{meridian}}</div>',
            'error' => '<span class="help-block error-message">{{content}}</span>',
            'errorHorizontal' => '<span class="help-block error-message {{errorColumnClass}}">{{content}}</span>',
            'errorList' => '<ul>{{content}}</ul>',
            'errorItem' => '<li>{{text}}</li>',
            'file' => '<input type="file" name="{{name}}" {{attrs}}>',
            'fieldset' => '<fieldset{{attrs}}>{{content}}</fieldset>',
            'formStart' => '<form{{attrs}}>',
            'formEnd' => '</form>',
            'formGroup' => '{{label}}{{prepend}}{{input}}{{append}}',
            'formGroupHorizontal' => '{{label}}<div class="{{inputColumnClass}}">{{prepend}}{{input}}{{append}}</div>',
            'help' => '<small id="{{id}}" class="form-text text-muted">{{content}}</small>',
            'hiddenBlock' => '<div style="display:none;">{{content}}</div>',
            'input' => '<input type="{{type}}" name="{{name}}" class="form-control{{attrs.class}}" {{attrs}}>',
            'inputSubmit' => '<input type="{{type}}" class="btn btn-primary{{attrs.class}}"{{attrs}}>',
            'inputContainer' => '<div class="form-group {{type}}{{required}}">{{content}}{{help}}</div>',
            'inputContainerError' => '<div class="form-group has-error {{type}}{{required}}">{{content}}{{help}}{{error}}</div>',
            'label' => '<label {{attrs}}>{{text}}</label>',
            'labelHorizontal' => '<label class="control-label {{labelColumnClass}}{{attrs.class}}"{{attrs}}>{{text}}</label>',
            'labelInline' => '<label class="sr-only{{attrs.class}}"{{attrs}}>{{text}}</label>',
            'nestingLabel' => '{{input}}<label class="custom-control-label{{attrs.class}}"{{attrs}}>{{text}}</label>',
            'legend' => '<legend>{{text}}</legend>',
            'option' => '<option value="{{value}}"{{attrs}}>{{text}}</option>',
            'optgroup' => '<optgroup label="{{label}}"{{attrs}}>{{content}}</optgroup>',
            'select' => '<select name="{{name}}" class="form-control{{attrs.class}}" {{attrs}}>{{content}}</select>',
            'selectColumn' => '<div class="col-md-{{columnSize}}"><select name="{{name}}" class="form-control{{attrs.class}}" {{attrs}}>{{content}}</select></div>',
            'selectMultiple' => '<select name="{{name}}[]" multiple="multiple" class="form-control{{attrs.class}}" {{attrs}}>{{content}}</select>',
            'radio' => '<input type="radio" name="{{name}}" value="{{value}}" class="custom-control-input{{attrs.class}}"{{attrs}}>',
            'radioWrapper' => '<div class="custom-control custom-radio">{{label}}</div>',
            'radioContainer' => '<div class="form-group">{{content}}</div>',
            'textarea' => '<textarea name="{{name}}" class="form-control{{attrs.class}}" {{attrs}}>{{value}}</textarea>',
            'submitContainer' => '<div class="form-group">{{submitContainerHorizontalStart}}{{content}}{{submitContainerHorizontalEnd}}</div>',
            'submitContainerHorizontal' => '<div class="form-group"><div class="{{inputColumnOffsetClass}} {{inputColumnClass}}">{{content}}</div></div>',
            'inputGroup' => '{{inputGroupStart}}{{input}}{{inputGroupEnd}}',
            'inputGroupStart' => '<div class="input-group">{{prepend}}',
            'inputGroupEnd' => '{{append}}</div>',
            'inputGroupAddons' => '<span class="input-group-addon">{{content}}</span>',
            'inputGroupButtons' => '<span class="input-group-btn">{{content}}</span>',
            'helpBlock' => '<p class="help-block">{{content}}</p>',
            'buttonGroup' => '<div class="btn-group{{attrs.class}}"{{attrs}}>{{content}}</div>',
            'buttonToolbar' => '<div class="btn-toolbar{{attrs.class}}"{{attrs}}>{{content}}</div>',
            'fancyFileInput' => '{{fileInput}}<div class="input-group"><div class="input-group-btn">{{button}}</div>{{input}}</div>',
        ],
    ];

    public function create($context = null, array $options = []) {

        if ( ! array_key_exists('novalidate', $options)) {
            $options['novalidate'] = true;
        }

        return parent::create($context, $options);
    }

    /**
     * Generates a form control element complete with label and wrapper div.
     *
     * ### Options
     *
     * See each field type method for more information. Any options that are part of
     * $attributes or $options for the different **type** methods can be included in `$options` for input().
     * Additionally, any unknown keys that are not in the list below, or part of the selected type's options
     * will be treated as a regular HTML attribute for the generated input.
     *
     * - `type` - Force the type of widget you want. e.g. `type => 'select'`
     * - `label` - Either a string label, or an array of options for the label. See FormHelper::label().
     * - `options` - For widgets that take options e.g. radio, select.
     * - `error` - Control the error message that is produced. Set to `false` to disable any kind of error reporting (field
     *    error and error messages).
     * - `help` - Control the help message that is produced.
     * - `empty` - String or boolean to enable empty select box options.
     * - `nestedInput` - Used with checkbox and radio inputs. Set to false to render inputs outside of label
     *   elements. Can be set to true on any input to force the input inside the label. If you
     *   enable this option for radio buttons you will also need to modify the default `radioWrapper` template.
     * - `templates` - The templates you want to use for this input. Any templates will be merged on top of
     *   the already loaded templates. This option can either be a filename in /config that contains
     *   the templates you want to load, or an array of templates to use.
     * - `labelOptions` - Either `false` to disable label around nestedWidgets e.g. radio, multicheckbox or an array
     *   of attributes for the label tag. `selected` will be added to any classes e.g. `class => 'myclass'` where
     *   widget is checked
     *
     * @param string $fieldName This should be "modelname.fieldname"
     * @param array  $options   Each type of input takes different options.
     *
     * @return string Completed form widget.
     * @link https://book.cakephp.org/3.0/en/views/helpers/form.html#creating-form-inputs
     */
    public function control($fieldName, array $options = []) {
        $options += [
            'type' => null,
            'label' => null,
            'error' => null,
            'help' => null,
            'required' => null,
            'options' => null,
            'templates' => [],
            'templateVars' => [],
            'labelOptions' => true,
        ];
        $options = $this->_parseOptions($fieldName, $options);
        $options += ['id' => $this->_domId($fieldName)];

        $templater = $this->templater();
        $newTemplates = $options['templates'];

        if ($newTemplates) {
            $templater->push();
            $templateMethod = is_string($options['templates']) ? 'load' : 'add';
            $templater->{$templateMethod}($options['templates']);
        }
        unset($options['templates']);

        $error = null;
        $errorSuffix = '';
        if ($options['type'] !== 'hidden' && $options['error'] !== false) {
            if (is_array($options['error'])) {
                $error = $this->error($fieldName, $options['error'], $options['error']);
            } else {
                $error = $this->error($fieldName, $options['error']);
            }
            $errorSuffix = empty($error) ? '' : 'Error';
            unset($options['error']);
        }

        $help = null;
        if ($options['help']) {
            if (is_array($options['help'])) {
                $help = $this->help($fieldName, $options['help'], $options['help']);
            } else {
                $help = $this->help($fieldName, $options['help']);
            }
            if ($help) {
                $options['aria-describedby'] = $this->_domId($fieldName . '-help');
            }
            unset($options['help']);
        }

        $label = $options['label'];
        if ( ! is_array($label)) {
            $labelText = $label;
            $label = [];
            if ($labelText) {
                $label['text'] = $labelText;
            }

        }
        unset($options['label']);

        if ($options['type'] === 'checkbox') {
            $label = $this->addClass($label, 'custom-control-label');
        }
        if ($options['type'] === 'radio') {
            $label = false;
        }

        $labelOptions = $options['labelOptions'];
        unset($options['labelOptions']);

        $nestedInput = isset($options['nestedInput']) ? $options['nestedInput'] : false;
        unset($options['nestedInput']);

        $input = $this->_getInput($fieldName, $options + ['labelOptions' => $labelOptions]);
        if ($options['type'] === 'hidden' || $options['type'] === 'submit') {
            if ($newTemplates) {
                $templater->pop();
            }

            return $input;
        }

        $label = $this->_getLabel($fieldName, compact('input', 'label', 'error', 'nestedInput') + $options);
        if ($nestedInput) {
            $result = $this->_groupTemplate(compact('label', 'error', 'options'));
        } else {
            $result = $this->_groupTemplate(compact('input', 'label', 'error', 'options'));
        }
        $result = $this->_inputContainerTemplate([
            'content' => $result,
            'error' => $error,
            'help' => $help,
            'errorSuffix' => $errorSuffix,
            'options' => $options,
        ]);

        if ($newTemplates) {
            $templater->pop();
        }

        return $result;
    }

    public function help($field, $text = null, array $options = []) {

        if (is_array($text)) {
            $text = $options['text'];
            unset($options['text']);
        }

        $options += ['id' => null, 'escape' => true];

        if ($options['id'] === null) {
            $options['id'] = $this->_domId($field . '-help');
        }

        if ($options['escape']) {
            $text = h($text);
        }

        return $this->formatTemplate('help', [
            'id' => $options['id'],
            'content' => $text,
        ]);

    }

    /**
     * Generates an input container template
     *
     * @param array $options The options for input container template
     *
     * @return string The generated input container template
     */
    protected function _inputContainerTemplate($options) {
        $inputContainerTemplate = $options['options']['type'] . 'Container' . $options['errorSuffix'];
        if ( ! $this->templater()->get($inputContainerTemplate)) {
            $inputContainerTemplate = 'inputContainer' . $options['errorSuffix'];
        }

        return $this->formatTemplate($inputContainerTemplate, [
            'content' => $options['content'],
            'error' => $options['error'],
            'help' => $options['help'],
            'required' => $options['options']['required'] ? ' required' : '',
            'type' => $options['options']['type'],
            'templateVars' => isset($options['options']['templateVars']) ? $options['options']['templateVars'] : [],
        ]);
    }

}
