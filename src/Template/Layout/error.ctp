<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('foundation.min.css') ?>
    <?= $this->Html->css('app.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    <style>
        body, html {
            margin: 20px;
        }
        .card-divider {
            background: #333;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="grid-container">
        <div class="card">


            <div class="card-divider">
                <h4><?= __('Error') ?></h4>
            </div>
            <div class="card-section">
                <?= $this->Flash->render() ?>

                <?= $this->fetch('content') ?>
            </div>
            <div class="card-divider">
                <?= $this->Html->link(__('Back'), 'javascript:history.back()', ['class' => 'button']) ?>
            </div>
        </div>
    </div>
</body>
</html>
