<?php /** @var $view \Symfony\Component\Templating\PhpEngine */ ?>

<?php $view->extend('base.html.php'); ?>

<?php $view['slots']->start('body'); ?>
    <?php echo 'Hello, '.$name.'!'; ?>
<?php $view['slots']->stop(); ?>
