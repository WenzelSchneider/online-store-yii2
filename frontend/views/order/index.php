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
<div class="container-small"><h1>Оформление заказа</h1></div>
		
<div class="container-small order-contacts">
<div class="tabs_reg">
			<?php 
			if(!empty($order['type'])){
			    if($order['type'] == 'fiz'){
    			    $fiz = " checked";
    			    $jur = " disabled";
			    }else{
    			    $fiz = " disabled";
    			    $jur = " checked";
			    }
			}else{
			    $fiz = " checked";
			    $jur = "";
			}
			?>
			<?php if(empty($order['type'])):?>
    			<!-- <form action="#" class="order-form order-contact" id="ocontact">
    			    <label class="contacts-lebel" class="contact__label order-form__label-radio">
    			        <div class="contact__radio"><input type="radio" name="type" id="otrifiz" <?=$fiz?>></div>
    			        <div class="contact__content">
    			            <div class="one"><span class="h4 m-0">Физическое лицо</span></div>
    			        </div>
    			    </label>
    			    <label class="contacts-lebel" class="contact__label order-form__label-radio">
    			        <div class="contact__radio"><input type="radio" name="type" id="otrijur" <?=$jur?>></div>
    			        <div class="contact__content">
    			            <div class="one"><span class="h4 m-0">Юридическое лицо</span></div>
    			        </div>
    			    </label>
    			</form>  -->   
    			<input type="radio" name="tab-btn" id="qotrifiz" value="" <?=$fiz?>>
                <label for="qotrifiz" class="contact__label order-form__label-radio">
                    <div class="contact__content">
                        <div class="one"><span class="h4 m-0">Физическое лицо</span></div>
                    </div>
                </label>
                <input type="radio" name="tab-btn" id="qotrijur" value="" <?=$jur?>>
                <label for="qotrijur" class="contact__label order-form__label-radio">
                    <div class="contact__content">
                        <div class="one"><span class="h4 m-0">Юридическое лицо</span></div>
                    </div>
                </label>
    		<?php endif;?>
			<?php if(empty($order['type'])):?>
			
        <div id="ocontent-1">
    			<form action="/frontend/web/order/contact-information-private" class="order-form" id="oconfiz">
    				<input class="contacts-input" type="text" placeholder="Ваше имя и фамилия*" <?= (!empty($order['contactface'])) ? 'value="'.$order['contactface'].'"' : ''; ?> name="contactname">
    				<input class="contacts-input" type="text" placeholder="Контактный номер телефона*" <?= (!empty($order['numberphone'])) ? 'value="'.$order['numberphone'].'"' : ''; ?> name="telephone">
    				<input class="contacts-input" type="text" placeholder="Электронная почта*" <?= (!empty($order['email'])) ? 'value="'.$order['email'].'"' : ''; ?> name="email">
    				<input class="contacts-input d-none" type="text" name="type" value="fiz">
    				<?php if(!empty($order['type'])):?>
                                            
                    <?php else:?>
    				    <input class="contacts-input" type="text" placeholder="Пароль" name="password" onchange="sendAjaxForm('oconfiz', '/frontend/web/order/contact-information-private')">
    				<?php endif;?>
    				<?=
    					Html::hiddenInput(
    						Yii::$app->request->csrfParam,
    						Yii::$app->request->csrfToken
    					);
    				?>
    			</form>
    	</div>
    		<?php endif;?>
    		<?php if(empty($order['type'])):?>
    		
            <div id="ocontent-2">
    			<form action="/frontend/web/order/contact-information-legal" class="order-form" id="oconjur">
    				<input class="contacts-input" type="text" placeholder="Название Вашей организации*" <?= (!empty($order['namecompany'])) ? 'value="'.$order['namecompany'].'"' : ''; ?> name="companyname">
    				<input class="contacts-input" type="text" placeholder="ИНН Вашей организации*" <?= (!empty($order['inn'])) ? 'value="'.$order['inn'].'"' : ''; ?> name="inn">
    				<input class="contacts-input" type="text" placeholder="Ваше имя и фамилия*" <?= (!empty($order['contactface'])) ? 'value="'.$order['contactface'].'"' : ''; ?> name="contactname">
    				<input class="contacts-input" type="text" placeholder="Контактный номер телефона*" <?= (!empty($order['numberphone'])) ? 'value="'.$order['numberphone'].'"' : ''; ?> name="telephone">
    				<input class="contacts-input" type="text" placeholder="Электронная почта*" <?= (!empty($order['email'])) ? 'value="'.$order['email'].'"' : ''; ?> name="email">
    				<input class="contacts-input d-none" type="text" name="type" value="jur">
    				<?php if(!empty($order['type'])):?>
                                            
                    <?php else:?>
    				    <input class="contacts-input" type="text" placeholder="Пароль" name="password" onchange="sendAjaxForm('oconjur', '/frontend/web/order/contact-information-legal')">
    				<?php endif;?>
    				<?=
        				Html::hiddenInput(
        					Yii::$app->request->csrfParam,
        					Yii::$app->request->csrfToken
        				);
    				?>
    			</form>
    			</div>
    		<?php endif;?>
    	</div>
    		<?php if($order['type'] == 'jur' || $order['type'] == 'fiz'):?>
    			<?= (!empty($order['namecompany'])) ? '<p class="contacts-input"><span>Наименование организации: </span>  ' . $order['namecompany'] . '</p>' : ''; ?>
    			<?= (!empty($order['inn'])) ? '<p class="contacts-input"><span>ИНН: </span>  ' . $order['inn'] . '</p>' : ''; ?>
    			<?= (!empty($order['contactface'])) ? '<p class="contacts-input"><span>ФИО: </span>  ' . $order['contactface'] . '</p>' : ''; ?>
    			<?= (!empty($order['numberphone'])) ? '<p class="contacts-input"><span>Телефон: </span>  ' . $order['numberphone'] . '</p>' : ''; ?>
    			<?= (!empty($order['email'])) ? '<p class="contacts-input"><span>E-mail: </span>  ' . $order['email'] . '</p>' : ''; ?>
    		<?php endif;?>


</div>


	
<div class="container-small flex fj-between fa-center">
<p class="already-login">Уже регестрировались? <a data-modal="#login" class="open-modal underline">Войдите в аккаунт</a> </p>
  <a <?= (!empty($_COOKIE['usere']))? 'href="/frontend/web/order/delivery"' : 'onclick="completeContactOrder()"' ?> class="btn -black">Продолжить</a>
 </div>

</section>

<div class="container-small top-hr helper">
    <div class="row">
        <div class="col-12 top-indent-25">
            <p>Возникли трудности при оформлении заказа? Наши специалисты помогут! Мы работаем ежедневно с 9:00 до 17:00. Ждем вашего звонка по телефону +7 (3532) 307-333.</p>
        </div>
    </div>
</div>