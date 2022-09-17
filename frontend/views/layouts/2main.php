<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;
use yii\widgets\Pjax;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <?php $this->registerCsrfMetaTags() ?>
    <title>
        <?= Html::encode($this->title) ?>
    </title>
    <?php $this->head() ?>
    <?= $this->render('_head') ?>
    
</head>

<body style="transition: none;">
    <?php $this->beginBody() ?>
    <?= $this->render('_modals') ?>
    <?= $this->render('_top-header') ?>
    <?= $this->render('_bot-header') ?>
    <?php //print_r($_SESSION);?>
    <div class="container">
        <div class="row">
            <div class="col-12"><span class="mt-1 mb-1 h1">ВЕДУТСЯ ТЕХНИЧЕСКИЕ РАБОТЫ</span></div>
        </div>
    </div>
    <?= $this->render('_list') ?>
    <?= $this->render('_footer') ?>
    <?php $this->endBody() ?>
    <?= $this->render('_notif') ?>
</body>

</html>
<?php $this->endPage() ?>