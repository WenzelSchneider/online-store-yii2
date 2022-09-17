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
<html lang="ru">

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
    <?= $this->render('_header') ?>
    <?= $content ?>
    <?= $this->render('_footer') ?>
    <?php $this->endBody() ?>
    <?= $this->render('_notif') ?>
    <script>
        (function(w,d,u){
                var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/60000|0);
                var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
        })(window,document,'https://cdn-ru.bitrix24.ru/b18889304/crm/site_button/loader_3_7jibku.js');
</script>
</body>

</html>
<?php $this->endPage() ?>