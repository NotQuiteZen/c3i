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
                echo $this->Form->create();

                echo $this->Form->control('test', [
                    'label' => 'Email address',
                    'placeholder' => 'Enter email',
                    'help' => 'We\'ll never share your email with anyone else.',
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
