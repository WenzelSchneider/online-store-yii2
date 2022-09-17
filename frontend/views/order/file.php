<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Goods;

$this->title = 'Заказ не оплачен! | Урал Декор';

?>
<div class="container">
	<div class="row">
		<div class="col">
			<div class="breadcrumbs">
				<a class="breadcrumbs__link" href="/frontend/web/"> Главная страница </a> / 
                    <a class="breadcrumbs__link" href="/frontend/web/order/index"> Неуспешная оплата заказа </a>
			</div>
		</div>
		<div class="col-3 backbox top-indent-50">
		    <a href="/frontend/web/" class="backbox__link"></a>
		</div>
	</div>
</div>
<div class="container top-indent-25">
	<div class="row">
			<div class="col-lg-8 col-12">
            <h1>Неуспешная оплата заказа</h1>				
            <p>Более 2 тысяч наименований, где вы найдете все для создания интерьера своей мечты</p>
		</div>
	</div>
</div>

	<!--header-->
	<div class="container">
		<div class="row">
			<div class="col-12 d-flex justify-content-center mt-1 mb-1"><p class="h1">Ошибка, ваш заказ не был оплачен!</p></div>
			<div class="col-12 d-flex justify-content-center mt-1 mb-1"><p>Наши менеджеры уже проверяют ваши данные. Мы перезвоним в ближайшее время для уточнения заказа.</p></div>
			<div class="col-12 d-flex justify-content-center mt-1 mb-1"><p><strong>Просим обратить ваше внимание! Товар находится в резерве не более 5 рабочих дней до момента оплаты.</strong></p></div>
			<div class="col-12 d-flex justify-content-center mt-1 mb-1">
			</div>
			<div class="col-12 d-flex justify-content-center mt-1 mb-1">
                <a href="/frontend/web/user/index" class="light-btn d-flex">Личный кабинет</a>
                <a href="/frontend/web/site/index" class="dark-btn d-flex ml-1">Вернуться на главную</a>
            </div>
		</div>
	</div>