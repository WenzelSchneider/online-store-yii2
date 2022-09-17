<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Goods;

use common\models\Price;
use common\models\GoodAssoc;
use common\models\GoodOptionValues;
use common\models\GoodOptions;
use common\models\GoodImages;
use common\models\GoodCategories;
use common\models\Groups;

$this->title = 'Оформление заказа | Урал Декор';

?>

<section class="breadcrumbs">
 <div class="container-small">
    <a href="/frontend/web/" class="breadcrumbs__link">Главная</a> /
    <a href="/frontend/web/order/index" class="breadcrumbs__link">Оформление заказа </a>
 </div>
</section>

<section class="order">
  <div class="container-small">
	    <h1>Оформление заказа</h1>
  </div>
<div class="container-small finish-order">


            <h3>Контактная информация</h3>
            <?= (!empty($order['namecompany'])) ? '<p class="contacts-input"><span>Наименование организации: </span>  ' . $order['namecompany'] . '</p>' : ''; ?>
            <?= (!empty($order['inn'])) ? '<p class="contacts-input"><span>ИНН: </span>  ' . $order['inn'] . '</p>' : ''; ?>
            <?= (!empty($order['contactface'])) ? '<p class="contacts-input"><span>ФИО: </span>  ' . $order['contactface'] . '</p>' : ''; ?>
            <?= (!empty($order['numberphone'])) ? '<p class="contacts-input"><span>Телефон: </span>  ' . $order['numberphone'] . '</p>' : ''; ?>
            <?= (!empty($order['email'])) ? '<p class="contacts-input"><span>E-mail: </span>  ' . $order['email'] . '</p>' : ''; ?>
   
            <h3>Способ получения</h3>
            <?= (!empty($_SESSION['order']['address'])) ? '<p class="contacts-input"><span>Адрес: </span>  ' . $_SESSION['order']['address'] . '</p>' : ''; ?>

       
            <h3>Способ оплаты</h3>
            <form action="#" class="order-form order-payment" id="payment">
                <?php if($order['type'] == 'fiz'):?>
                    <?php if($order['delivery'] == 0):?>
                        <label class="contacts-input flex fa-center">
                            <div class="payment__radio"><input type="radio" name="payment" value="paymentattheoffice" checked  onchange="sendAjaxForm('payment', '/frontend/web/order/payment-method')"></div>
                            <div class="payment__content">
                                <div class="one"><span class="h4 m-0">Оплата в офисах компании</span><span>Возможна оплата наличными или картой любого банка</span></div>
                            </div>
                        </label>
                    <?php endif;?>
                    
                    <?php if($order['delivery'] == 1):?>
                        <label class="contacts-input flex fa-center">
                            <div class="payment__radio"><input type="radio" name="payment" value="uponreceipt" checked  onchange="sendAjaxForm('payment', '/frontend/web/order/payment-method')"></div>
                            <div class="payment__content">
                                <div class="one"><span class="h4 m-0">Оплата при получении</span><span>Возможна оплата наличными или картой любого банка</span></div>
                            </div>
                        </label>
                        <?if($_COOKIE['usere'] == 46 || $_COOKIE['usere'] == 75 || $_COOKIE['usere'] == 89 ):?>
                        <label class="contacts-input flex fa-center">
                            <div class="payment__radio"><input type="radio" name="payment" value="paymentbycreditcard"  onchange="sendAjaxForm('payment', '/frontend/web/order/payment-method')"></div>
                            <div class="payment__content">
                                <div class="one"><span class="h4 m-0">Оплата на сайте</span><span>Возможна оплата картой любого банка</span></div>
                            </div>
                        </label>
                        <?endif?>
                        <label class="contacts-input flex fa-center">
                            <div class="payment__radio"><input type="radio" name="payment" value="fastpayment"  onchange="sendAjaxForm('payment', '/frontend/web/order/payment-method')"></div>
                            <div class="payment__content">
                                <div class="one"><span class="h4 m-0">Оплата через систему быстрых платежей</span><span>Через центральный банк российской федерации</span></div>
                            </div>
                        </label>
                    <?$_SESSION['foot_pay'] = 1;?>
                    <?php endif;?>
                <?php endif;?>
                <?php if($order['type'] == 'jur'):?>
                    <?php if($order['delivery'] == 0):?>
                        <label class="contacts-input flex fa-center">
                            <div class="payment__radio"><input type="radio" name="payment" value="paymentattheoffice" checked  onchange="sendAjaxForm('payment', '/frontend/web/order/payment-method')"></div>
                            <div class="payment__content">
                                <div class="one"><span class="h4 m-0">Оплата в офисах компании</span><span>Возможна оплата наличными или картой любого банка</span></div>
                            </div>
                        </label>
                        <label class="contacts-input flex fa-center">
                            <div class="payment__radio"><input type="radio" name="payment" value="paymentonaccount"  onchange="sendAjaxForm('payment', '/frontend/web/order/payment-method')"></div>
                            <div class="payment__content">
                                <div class="one"><span class="h4 m-0">По счету</span><span>С Вами свяжется менеджер для выставления счета.</span></div>
                            </div>
                        </label>
                    <?php endif;?>
                    
                    <?php if($order['delivery'] == 1):?>
                        <label class="contacts-input flex fa-center">
                            <div class="payment__radio"><input type="radio" name="payment" value="uponreceipt" checked  onchange="sendAjaxForm('payment', '/frontend/web/order/payment-method')"></div>
                            <div class="payment__content">
                                <div class="one"><span class="h4 m-0">Оплата при получении</span><span>Возможна оплата наличными или картой любого банка</span></div>
                            </div>
                        </label>
                        <?if($_COOKIE['usere'] == 46):?>
                        <label class="contacts-input flex fa-center">
                            <div class="payment__radio"><input type="radio" name="payment" value="paymentbycreditcard"  onchange="sendAjaxForm('payment', '/frontend/web/order/payment-method')"></div>
                            <div class="payment__content">
                                <div class="one">
                                    <span class="h4 m-0">Оплата на сайте</span><span>Возможна оплата картой любого банка</span><br>
                                    <a class="open-modal" data-modal="#cardpay">Подробнее...</a><br>
                                    <div class="">
                                        <img src="/frontend/web/img/_src/paysys.png" alt="Visa MasterCard Мир" class="w-50 text-center">
                                    </div>
                                </div>
                            </div>
                        </label>
                        <label class="contacts-input flex fa-center">
                            <div class="payment__radio"><input type="radio" name="payment" value="fastpayment"  onchange="sendAjaxForm('payment', '/frontend/web/order/payment-method')"></div>
                            <div class="payment__content">
                                <div class="one"><span class="h4 m-0">Оплата через систему быстрых платежей</span><span>Через центральный банк российской федерации</span></div>
                            </div>
                        </label>
                        <label class="contacts-input flex fa-center">
                            <div class="payment__radio"><input type="radio" name="payment" value="paymentonaccount"  onchange="sendAjaxForm('payment', '/frontend/web/order/payment-method')"></div>
                            <div class="payment__content">
                                <div class="one"><span class="h4 m-0">По счету</span><span>Сайт сгенерирует и покажет счет для оплаты.</span></div>
                            </div>
                        </label>
                        <?endif?>
                        <label class="contacts-input flex fa-center">
                            <div class="payment__radio"><input type="radio" name="payment" value="paymentonaccount"  onchange="sendAjaxForm('payment', '/frontend/web/order/payment-method')"></div>
                            <div class="payment__content">
                                <div class="one"><span class="h4 m-0">По счету</span><span>С Вами свяжется менеджер для выставления счета.</span></div>
                            </div>
                        </label>
                    <?$_SESSION['foot_pay'] = 1;?>
                    <?php endif;?>
                <?php endif;?>
                <?=
                    Html::hiddenInput(
                        Yii::$app->request->csrfParam,
                        Yii::$app->request->csrfToken
                    );
                ?>
            </form>
        
</div>
</section>

<!--table-->
<div class="container-small">
<h3>Товары заказа</h3>
<?php foreach ($basket['products'] as $id => $item) : ?>
        <?php
                      $image = GoodImages::find()
                      ->where(['good_id'=>$item['good_id']])
                      ->andFilterWhere(['is_main'=>1])
                      ->asArray()
                      ->one();
                      if(!empty($image['image_link'])){
                          $img = $image['image_link'];//frontend/web/images/goods/
                      }else{
                         $img = 'https://le-go.net/images/noimage.gif';
                      }
                    ?>
        <?php
            $good_desc = Goods::find()
            ->where(['good_id' => $item['good_id']])
            ->one();
            $desc = $good_desc['description'];
            $i++;
        ?>

    <div class="basket-item">
      <div class="basket-item-img">
         <img src="<?= $img ?>">
      </div>
        <div class="basket-item-title">
            <a class="name" href="/frontend/web/product/product?id=<?= $item['good_id']; ?>"><?= $item['name']; ?></a>
            <span class="art">Арт: <?= $desc ?></span>
            <span class="price">Цена: <?= $item['price']; ?> Р</span>
        </div>
        <div class="basket-item-couner-wrap flex fw-wrap fa-center fj-between">
        <div class="basket-item-counter flex fa-center "> 
            <span class="count-panel__btn count-panel__sub" onclick="$.get( '/frontend/web/basket/minus', { id: <?=$id;?>, count: 1 }).done(function(){location.reload()});">-</span>
             <input class="count-panel__digit" value="<?= $item['count']; ?>"  onchange="$.get( '/frontend/web/basket/update', { id: <?=$id;?>, count: $(this).val() }).done(function(){location.reload()});">
             <span class="count-panel__btn count-panel__add" onclick="$.get( '/frontend/web/basket/plus', { id: <?=$id;?>, count: 1 }).done(function(){location.reload()});">+</span>
        </div>
        <div class="basket-item-total">
          <?= $item['price'] * $item['count'] ?> Р
        </div>
                    </div>
        <div class="basket-item-del"><a href="<?= Url::to(['basket/remove', 'id' => $id]); ?>"><ion-icon name="close-outline"></ion-icon></a></div>
    </div>
    <?php endforeach; ?>
</div>

<div class="container-small top-indent-25 finish-order-total">
  <p>Всего товаров на сумму:</p> <span> <?= $basket['amount']; ?> ₽</span>
</div>


<div class="container-small top-indent-25 flex fj-between fa-center">
<a onclick="history.back();" class="btn -outline">Назад</a>
  
  <a onclick="completeOrder()" class="btn -black">Оформить заказ</a>
 </div>

 <div class="container-small top-hr helper">
    <div class="row">
        <div class="col-12 top-indent-25">
            <p>Возникли трудности при оформлении заказа? Наши специалисты помогут! Мы работаем ежедневно с 9:00 до 17:00. Ждем вашего звонка по телефону +7 (3532) 307-333.</p>
        </div>
    </div>
</div>

            
<div class='modal' id='returnconditions'>
    <i class="close icon-cross close-modal" data-modal="#returnconditions"></i>
    <div class='content'>
        <span class='h1'>Условия возврата</span>
        <p>
            Срок возврата товара надлежащего качества составляет 30 дней с момента получения товара.
        </p>
        <p>
            Возврат переведённых средств, производится на ваш банковский счёт в течение 5-30 рабочих дней (срок зависит от банка, который выдал вашу банковскую карту).
        </p>
    </div>  
</div> 

<div class='modal' id='cardpay'>
    <i class="close icon-cross close-modal" data-modal="#cardpay"></i>
    <div class='content'>
        <span class='h1'>Оплата картой</span>
        <div class="row mt-1">
            <div class="col-6">
                <div class="text-center">
                    <a class="open-modal" data-modal="#conditionspay">Способы оплаты</a><br>
                    <a class="open-modal" data-modal="#returnconditions">Условия возврата</a><br>
                    <a class="open-modal" data-modal="#conditionsdel">Условия доставки</a><br>
                    <a class="open-modal" data-modal="#ps">Пользовательское соглашение</a><br>
                </div></div>
            <div class="col-6">
                <div class="text-center">
                    <p>Приём оплаты на сайте осуществляет:</p> 
                    <p>
                        Контакты:<br>
                       Фактический адрес: 460048, Оренбург, пр-т Автоматики, д. 12/4, офис 315<br>
                       Электронная почта: starkov@ssoft.ru<br>
                       <!--Телефоны: 8(922)844-92-40<br><br>-->
                       Реквизиты:<br>
                       ИП Старков Артем Владимирович<br>
                       ИНН 561100988596 / ОГРНИП 315565800073453<br>
                       Юридический адрес: 460050 Оренбург, ул. Новая, д. 8, кв.195
                    </p>
                </div>
            </div>
        </div>
    </div>  
</div> 