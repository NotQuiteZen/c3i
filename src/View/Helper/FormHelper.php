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
            'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}}>',
            'checkboxFormGroup' => '{{label}}',
            'checkboxWrapper' => '<div class="checkbox">{{label}}</div>',
            'checkboxContainer' => '<div class="checkbox {{required}}">{{content}}</div>',
            'checkboxContainerHorizontal' => '<div class="form-group"><div class="{{inputColumnOffsetClass}} {{inputColumnClass}}"><div class="checkbox {{required}}">{{content}}</div></div></div>',
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
            'hiddenBlock' => '<div style="display:none;">{{content}}</div>',
            'input' => '<input type="{{type}}" name="{{name}}" class="form-control{{attrs.class}}" {{attrs}} />',
            'inputSubmit' => '<input type="{{type}}"{{attrs}}>',
            'inputContainer' => '<div class="form-group {{type}}{{required}}">{{content}}{{help}}</div>',
            'inputContainerError' => '<div class="form-group has-error {{type}}{{required}}">{{content}}{{error}}</div>',
            'label' => '<label class="control-label{{attrs.class}}"{{attrs}}>{{text}}</label>',
            'labelHorizontal' => '<label class="control-label {{labelColumnClass}}{{attrs.class}}"{{attrs}}>{{text}}</label>',
            'labelInline' => '<label class="sr-only{{attrs.class}}"{{attrs}}>{{text}}</label>',
            'nestingLabel' => '{{hidden}}<label{{attrs}}>{{input}}{{text}}</label>',
            'legend' => '<legend>{{text}}</legend>',
            'option' => '<option value="{{value}}"{{attrs}}>{{text}}</option>',
            'optgroup' => '<optgroup label="{{label}}"{{attrs}}>{{content}}</optgroup>',
            'select' => '<select name="{{name}}" class="form-control{{attrs.class}}" {{attrs}}>{{content}}</select>',
            'selectColumn' => '<div class="col-md-{{columnSize}}"><select name="{{name}}" class="form-control{{attrs.class}}" {{attrs}}>{{content}}</select></div>',
            'selectMultiple' => '<select name="{{name}}[]" multiple="multiple" class="form-control{{attrs.class}}" {{attrs}}>{{content}}</select>',
            'radio' => '<input type="radio" name="{{name}}" value="{{value}}"{{attrs}}>',
            'radioWrapper' => '<div class="radio">{{label}}</div>',
            'radioContainer' => '<div class="form-group">{{content}}</div>',
            'inlineRadio' => '<input type="radio" name="{{name}}" value="{{value}}"{{attrs}}>',
            'inlineRadioWrapper' => '{{label}}',
            'inlineRadioNestingLabel' => '{{hidden}}<label{{attrs}} class="radio-inline">{{input}}{{text}}</label>',
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

}
