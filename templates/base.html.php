<?php /** @var $view \Symfony\Component\Templating\PhpEngine */ ?>

<!DOCTYPE html>
<html>
<head>
    <title><?php $view['slots']->output('title', 'Default title'); ?></title>
</head>
<body>
    <?php $view['slots']->output('body', ''); ?>
</body>
</html>
