<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Goods;

$this->title = 'Заказ успешно оформлен! | Урал Декор';

?>
<section class="breadcrumbs">
 <div class="container-small">
    <a href="/frontend/web/" class="breadcrumbs__link">Главная</a> /
    <a href="/frontend/web/order/index" class="breadcrumbs__link">Успешное оформление заказ</a>
 </div>
</section>

	<!--header-->
	<div class="container-small order-complete">

                <?php if($_SESSION['order']['payment'] == 'paymentbycreditcard'): ?>
        			<h2>Заказ создан, пожалуйста, <a href="<?=$_SESSION['order']['paylink']?>"> перейдите к оплате!</a></h2>
        			<p>Наши менеджеры уже проверяют ваши данные. Мы перезвоним в ближайшее время для уточнения заказа.</p>
        			<p style="padding:5px;background-color:green;color:white">Просим обратить ваше внимание! Товар находится в резерве не более 5 рабочих дней до момента оплаты.</p>
                <?php else:?>
        			<h2>Спасибо, ваш заказ получен!</h2>
        			<p>Наши менеджеры уже проверяют ваши данные. Мы перезвоним в ближайшее время для уточнения заказа.</p>
        			<p style="padding:5px;background-color:green;color:white">Просим обратить ваше внимание! Товар находится в резерве не более 5 рабочих дней до момента оплаты.</p>
                <?php endif; ?>


		<div class="order-details">
			<dl class="dl-inline">
                <dt class="dt-dotted"><span>Номер заказа:</span></dt>
                <dd>#<?=(!empty($orid))?$orid:$_SESSION['order']['orderid']?></dd>
            </dl>
			<dl class="dl-inline">
                <dt class="dt-dotted"><span>Оплата:</span></dt>
                 <dd><?php 
								
								if(empty($_SESSION['order']['payment'])){
									$_SESSION['order']['payment'] = office;
								}
								if($_SESSION['order']['payment'] == 'online'){
									echo 'Онлайн';
								}elseif($_SESSION['order']['payment'] == 'paymentonaccount'){
									echo 'Оплата по выставлении счета';
								}elseif($_SESSION['order']['payment'] == 'paymentattheoffice'){
									echo 'Оплата в офисах компании';
								}elseif($_SESSION['order']['payment'] == 'uponreceipt'){
									echo 'Оплата при получении';
								}elseif($_SESSION['order']['payment'] == 'paymentbycreditcard'){
									echo 'Оплата на сайте банковской картой';
								}elseif($_SESSION['order']['payment'] == 'fastpayment'){
									echo 'Оплата на сайте через систему быстрых платежей';
								}else{
									echo 'Оплата не указана';
								}
							?></dd>
            </dl>
			<dl class="dl-inline">
                <dt class="dt-dotted"><span>Телефон:</span></dt>
                <dd><?= $_SESSION['order']['numberphone'];?></dd>
            </dl>
			<dl class="dl-inline">
                <dt class="dt-dotted"><span>Email:</span></dt>
                <dd><?= $_SESSION['order']['email'];?></dd>
            </dl>
			<dl class="dl-inline">
                <dt class="dt-dotted"><span>ФИО:</span></dt>
                <dd><?= $_SESSION['order']['contactface'];?></dd>
            </dl>

			<dl class="dl-inline">
                <dt class="dt-dotted"><span>Адрес:</span></dt>
                <dd><?= $_SESSION['order']['address'];?></dd>
            </dl>
			<!--
			<dl class="dl-inline">
                <dt class="dt-dotted"><span>Сумма заказа:</span></dt>
                <dd><?=$_SESSION['basket']['amount']?></dd>
            </dl>
			-->
		</div>

			<div class="col-12 d-flex justify-content-center mt-1 mb-1">
                <a href="/frontend/web/user/index" class="btn -black d-flex">Личный кабинет</a>
                <?php if($_SESSION['order']['payment'] == 'paymentbycreditcard'): ?>
                    <a href="<?=$_SESSION['order']['paylink']?>" class="btn -black d-flex ml-1">Оплатить банковской картой</a>
                <?php else:?>
                    <a href="/frontend/web/site/index" class="btn -black d-flex ml-1">Вернуться на главную</a>
                <?php endif; ?>
            </div>

				</div>