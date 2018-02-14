<?php
/**
* @var \Cake\View\View $this
 * @var \App\Model\Entity\User $user
 */
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Droppert ~
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>
        <?= $this->Html->css([
            'foundation.min.css',
            'fontawesome-all.min.css',
            'app.css'
    ]) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <?= $this->Flash->render() ?>
    <div class="container">
        <?= $this->fetch('content') ?>
    </div>
    <footer>
    </footer>

    <?= $this->Html->script([
            'vendor/jquery.js',
            'vendor/what-input.js',
            'vendor/foundation.js',
            'app.js'
    ]); ?>
</body>
</html>
