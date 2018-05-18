<?php
/**
 * @var $this HintView
 *
 * @var $testform
 */

?>
<div class="container">
    <div class="row">
        <div class="col col-lg-12">
            <div class="card mt-5">
                <div class="card-header text-white bg-info text-center">
                    <?php

                    # Welcome
                    echo $this->Html->h2('Test Form', ['class' => 'm-0']);

                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col col-lg-12">
            <div class="card p-4">
                <?php

                echo $this->Form->create($testform);

                echo $this->Form->control('name', [
                    'placeholder' => 'Jane Doe',
                    'help' => 'Yoyo shizzle',
                ]);

                echo $this->Form->control('email', [
                    'placeholder' => 'jane.doe@example.com',
                    'type' => 'text',
                ]);

                echo $this->Form->button('Submit');

                echo $this->Form->end();

                ?>
            </div>
        </div>
    </div>
</div>
