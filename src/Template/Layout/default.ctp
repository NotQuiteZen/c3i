<?php
/**
 * @var $this HintView
 */
?><!DOCTYPE html>
<html>
<head>
    <?=$this->Html->charset()?>
    <title><?=(empty($pagetitle) ? '' : $pagetitle . ' | ') . 'App'?></title>
    <meta name="viewport" content="initial-scale=1, shrink-to-fit=no, width=device-width">
    <?php

    # Meta
    echo $this->fetch('meta');

    # CSS Core
    echo $this->Html->css('commons');

    # CSS Fetch other styles
    echo $this->fetch('css');

    # JS Fetch other scripts
    echo $this->fetch('script');

    # Set some JsConfigs
    $this->JsConfig->set([
        'snackbars' => $this->Snackbar->render('array'),
        'controller' => $this->request->getParam('controller'),
        'action' => $this->request->getParam('action'),
    ], 'App');

    # Get the JsConfig js object
    echo $this->JsConfig->getObject();
    ?>
</head>
<body class="bg-dark-2">
<?php

# Content
echo $this->Html->tag(
    'div',
    ($this->fetch('content') ?: ''),
    ['id' => 'content-container']
);

# JS Core
echo $this->Html->script('commons');
echo $this->JsLoader->getViewScript();

?>
</body>
</html>
