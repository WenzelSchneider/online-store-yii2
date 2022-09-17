<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Контакты - Урал Декор';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid mt-3" id="content-block">
    <div class="row">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p class="breadcrums min-raleway">
                        <a href="/frontend/web/"> Главная страница </a> / 
						<a href="/frontend/web/site/contact"> Контакты </a>
                    </p>
                </div>	

											
                        <?= $this->render('_leftmenu') ?>
                        <div class="col">
                    <table cellpadding="10" cellspacing="0" width="100%" border="0">
                        <tbody><tr>
                            <td style="border-bottom:2px solid #006DE6;" colspan="2" valign="top">
                                <h2 style="margin-left:30px;">КОНТАКТЫ</h2>
                            </td>
                        </tr>
                        <tr>
                            <td width="240" valign="top">
                                <div class="col max-content">
                                    <ul class="nonlist">
                                        <li><a style="color:#006DE6!important;" href="tel:8 (3532) 30-73-33" class="link-mote text-white"><i class="fas fa-phone-alt text-dark mr-2" aria-hidden="true"></i><span class="t-n-b text-white" style="color:#006DE6!important;">8 (3532) 30-73-33</span></a>
                                        </li>
                                        <li><a style="color:Black!important;" class="link-mote text-white"><i class="fas fa-map-marker-alt text-dark mr-2" aria-hidden="true"></i>
                                                г.Оренбург ул.Юркина 9а</a></li>
                                        <li><a style="color:Black!important;" class="link-mote text-white">ул.Туркестанская 68</a></li>
                                        <li><a style="color:Black!important;" href="mailto:uraldekor@bk.ru" class="link-mote text-white"><i class="fas fa-envelope text-dark mr-2" aria-hidden="true"></i>
                                                uraldekor@bk.ru</a>
                                        </li>
                                        <li><i style="color:Black!important;" class="far fa-clock text-dark mr-2" aria-hidden="true"></i>Пн - Пт: 9:00 - 17:00<br>Сб: 10:00 - 14:00 Вс -
                                            выходной
                                        </li>
                                    </ul>

                                    <div class="d-flex mb-4">
                                        <a href="https://vk.com/ural_dekor" target="blank" class="btn-mote btn-ss-yellow m-2"><i class="fab fa-vk" aria-hidden="true"></i></a>
                                        <a href="https://www.instagram.com/ural.decor/" target="blank" class="btn-mote btn-ss-yellow m-2"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                                        <a href="https://ok.ru/uraldekor" class="btn-mote btn-ss-yellow m-2"><i class="fab fa-odnoklassniki" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </td>
                            <td valign="top">
                                Схема проезда:<br><br>
                                <img style="width:90%;" src="/images/roadmap_ud.jpg">
                            </td>
                        </tr>
                    </tbody></table>
                </div>


            </div>
        </div>
    </div>
    </div>

<?= $this->render('_yousee') ?>