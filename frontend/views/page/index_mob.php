<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = (isset($pagedata)) ? htmlspecialchars_decode($pagedata->page_title) : '' . ' - Урал Декор';
$this->params['breadcrumbs'][] = $this->title;
$this->registerMetaTag(['name' => 'description', 'content' => (isset($pagedata)) ? $pagedata->page_meta_description : '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => (isset($pagedata)) ? $pagedata->page_meta_keywords : '']);
?>
<div class="container-fluid mt-3" id="content-block">
    <div class="row">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p class="breadcrums min-raleway">
                        <a href="/frontend/web/"> Главная страница </a> / 
						<a href="/frontend/web/site/about"> <?= (isset($pagedata)) ? htmlspecialchars_decode($pagedata->page_title) : ''?> </a>
                    </p>
                </div>

											
                        <?= $this->render('_leftmenu') ?>
                        <div class="col-md-9 col-12">
                            <div style="border-bottom:2px solid #006DE6;" class="mb-3">
                                <h2 style="margin-left:30px;text-transform:uppercase;"><?= (isset($pagedata)) ? htmlspecialchars_decode($pagedata->page_title) : ''?></h2>
                            </div>
                            <?= (isset($pagedata)) ? htmlspecialchars_decode($pagedata->page_html_mob) : ''?>
                </div>


            </div>
        </div>
    </div>
    </div>

<?= '' //$this->render('_yousee') ?>