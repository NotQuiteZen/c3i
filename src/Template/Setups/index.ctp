<?php
/**
 * @var $this HintView
 */

use Cake\Core\Configure;
use Cake\Validation\Validation;
use Cake\Utility\Security;

# Set JavaScript config
$this->JsConfig->set(['console_message' => 'Hi there, welcome to SetupIndex']);

?>
<div class="container text-center">
    <div class="row">
        <div class="col col-lg-6 offset-lg-3">
            <div class="card mt-5 ">
                <div class="card-header text-white bg-info">
                    <?php

                    # Welcome
                    echo $this->Html->h2('Welcome to c3i', ['class' => 'm-0']);

                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col col-lg-6 offset-lg-3">
            <div class="card">
                <div class="card-header font-weight-bold">CakePHP version</div>

                <div class="card-body ">
                    <p><span class="chip"><?=Configure::version()?></span></p>
                    <?php

                    # Version & changelog
                    echo $this->Html->link('Read the changelog', 'https://cakephp.org/changelogs/' . Configure::version());

                    ?>
                </div>
                <div class="card-header font-weight-bold">Configuration</div>
                <div class="card-body ">
                    <?php

                    $salt = Security::getSalt();
                    # Security
                    if (empty($salt) || strpos($salt, '__') === 0) {
                        echo $this->Html->div('text-danger', 'Please change the value of <em>Security.salt</em> in <em>config/app.php</em> to a value specific to your application.<br>');
                    } else {
                        echo $this->Html->div('text-success', 'The value of <em>Security.salt</em> is set.<br>');
                    }

                    # PHP version
                    if (version_compare(PHP_VERSION, '5.6', '>=')) {
                        echo $this->Html->div('text-success', 'Your version of PHP is 5.6 or higher.<br>');
                    } else {
                        echo $this->Html->div('text-danger', 'Your version of PHP too low. You need PHP 5.6 or higher.<br>');
                    }

                    # Writable app/tmp
                    if (is_writable(TMP)) {
                        echo $this->Html->div('text-success', 'Your tmp directory is writable.<br>');
                    } else {
                        echo $this->Html->div('text-danger', 'Your tmp directory NOT is writable.<br>');
                    }

                    # Unicode support
                    if ( ! Validation::alphaNumeric('cakephp')) {
                        echo $this->Html->div('text-danger', 'PCRE has not been compiled with Unicode support. Recompile PCRE with Unicode support by adding <code>--enable-unicode-properties</code> when configuring.<br>');
                    }

                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
