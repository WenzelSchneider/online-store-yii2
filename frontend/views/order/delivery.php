<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Goods;

$this->title = 'Оформление заказа | Урал Декор';

?>


<section class="breadcrumbs">
 <div class="container-small">
    <a href="/frontend/web/" class="breadcrumbs__link">Главная</a> /
    <a href="/frontend/web/order/index" class="breadcrumbs__link">Оформление заказа</a>
 </div>
</section>

<section class="order">
  <div class="container-small">
	    <h1>Оформление заказа</h1>
  </div>
  <div class="container-small order-delivery">
        <form action="#" class="order-form order-delivery" id="delivery">

			<!-- Самовывоз Юркина, 9Ак3 -->
				<label class="delivery-lebel">
					<div class="delivery-lebel-col">
						<input type="radio" name="delivery" value="selfgetturk" onchange="sendAjaxForm('delivery', '/frontend/web/order/product-method')" checked>
						<div>
							<span>Самовывоз - г. Оренбург, ул. Туркестанская, 68</span>
						</div>
					</div>
					<!--
					<div class="delivery-lebel-col">
						<a data-modal="#mapturk" class="open-modal grey-btn">Показать на карте</a>
					</div>	-->
				</label>

			<!-- Самовывоз Туркестанская, 68 -->
				<label class="delivery-lebel">
			        <div class="delivery-lebel-col">
						<input type="radio" name="delivery" value="selfgeturk" onchange="sendAjaxForm('delivery', '/frontend/web/order/product-method')">
						<div>
							<span>Самовывоз - г. Оренбург, ул. Юркина, 9Ак3</span>
						</div>
					</div>
					<!--
					<div class="delivery-lebel-col">
							<a data-modal="#mapurk" class="open-modal grey-btn">Показать на карте</a>
						</div> -->
				</label>

				<!-- Доставка груза -->
				<label class="delivery-lebel">
				    <div class="delivery-lebel-col">
						<input type="radio" name="delivery" value="courier" id="deliveri-to" onchange="sendAjaxForm('delivery', '/frontend/web/order/product-method')" >
						<span>Доставка</span>
					</div>

				    <div class="delivery-lebel-col">
						<a data-modal="#conditionsdel" class="open-modal grey-btn">Условия доставки</a>
					</div>

					<div class="delivery-lebel-footer">
						<input type="text" class="deliveri-to city" name="city" placeholder="Город" value="Оренбург">
						<input type="text" class="deliveri-to street" name="street" placeholder="Улица">
						<input type="text" class="deliveri-to numh" name="numh" placeholder="Дом" onchange="sendAjaxForm('delivery', '/frontend/web/order/product-method')">
        				<?=
            				Html::hiddenInput(
            					Yii::$app->request->csrfParam,
            					Yii::$app->request->csrfToken
            				);
        				?>
						<div class="delivery-lebel-description">
							<p>*После оформления заказа, наш оператор свяжется с вами для уточнения деталей по доставке</p>
						</div>
					</div>
				</label>
			</form>
    </div>
</section>




<div class="container-small flex fj-between fa-center">
<a onclick="history.back();" class="btn -outline">Назад</a>
  <a onclick="completeDeliveryOrder()" class="btn -black">Продолжить</a>
 </div>

 <div class="container-small top-hr helper">
    <div class="row">
        <div class="col-12 top-indent-25">
            <p>Возникли трудности при оформлении заказа? Наши специалисты помогут! Мы работаем ежедневно с 9:00 до 17:00. Ждем вашего звонка по телефону +7 (3532) 307-333.</p>
        </div>
    </div>
</div>