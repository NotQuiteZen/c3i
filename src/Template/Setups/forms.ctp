<?php
/**
 * @var $this HintView
 */

?>
<div class="container">
    <div class="row">
        <div class="col col-lg-12">
            <div class="card mt-5">
                <div class="card-header text-white bg-info text-center">
                    <?php

                    # Welcome
                    echo $this->Html->h2('Forms', ['class' => 'm-0']);

                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col col-lg-12">
            <div class="card p-4">
                <?php

                echo $this->Form->create('Form');

                echo $this->Form->control('text', [
                    'placeholder' => 'This is a text input',
                    'help' => 'This is helper text',
                ]);

                echo $this->Form->control('password', [
                    'type' => 'password',
                    'placeholder' => 'This is a password input',
                    'help' => 'This is helper text',
                ]);

                echo $this->Form->control('checkbox', [
                    'type' => 'checkbox',
                    'label' => 'This is a checkbox',
                    'checked',
                    'help' => 'This is helper text',
                ]);

                echo $this->Form->control('radio', [
                    'type' => 'radio',
                    'options' => ['Radio option 1', 'Radio option 2'],
                    'default' => 0,
                    'help' => 'This is helper text',
                ]);

                echo $this->Form->control('select', [
                    'type' => 'select',
                    'options' => ['Select option 1', 'Select option 2'],
                    'empty' => true,
                    'help' => 'This is helper text',
                ]);

                echo $this->Form->control('textarea', [
                    'type' => 'textarea',
                    'placeholder' => 'This is a textarea',
                    'help' => 'This is helper text',
                ]);

                echo $this->Form->control('date', [
                    'type' => 'text',
                    'data-material-datepicker' => json_encode([
                        'cancel' => 'Sluiten jonguh',
                    ]),
                    'placeholder' => 'This is a datepicker',
                    'help' => 'This is helper text',
                ]);

                echo $this->Form->submit('Submit');

                echo $this->Form->end();

                ?>
            </div>
        </div>
    </div>
</div>
