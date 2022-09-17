<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;

/*
use common\models\Goods;
use common\models\GoodImages;
*/

use common\models\Goods;
use common\models\Price;
use common\models\GoodAssoc;
use common\models\GoodOptionValues;
use common\models\GoodOptions;
use common\models\GoodImages;
use common\models\GoodCategories;
use common\models\Groups;

$this->title = 'Корзина | Урал Декор';
$this->params['breadcrumbs'][] = $this->title;
$i = 0;


?>


<section class="breadcrumbs">
 <div class="container-small">
    <a href="/frontend/web/" class="breadcrumbs__link">Главная</a> /
    <a href="/frontend/web/product/index" class="breadcrumbs__link">Корзина товаров</a>
 </div>
</section>

    <?php if (!empty($basket)) : ?>



<section class="basket-table top-indent-25">
    <div class="container-small">
    <h1>Корзина товаров</h1>
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

  	
<div class="container-small flex fj-between fa-center last-check">
    <a class="just-link open-modal" data-modal="#dropbag">Очистить</a>
    <p class="text-bold m-0">Итого: <?= $basket['amount']; ?> Р</p>
    <a href="/frontend/web/order/index" class="btn -black">Перейти к оформлению</a>
</div>

<div class="container-small top-hr helper">
    <div class="row">
        <div class="col-12 top-indent-25">
            <p>Возникли трудности при оформлении заказа? Наши специалисты помогут! Мы работаем ежедневно с 9:00 до 17:00. Ждем вашего звонка по телефону +7 (3532) 307-333.</p>
        </div>
    </div>
</div>
    
</section>




<?php else : ?>
<section class="basket">
  <div class="container">
    <h2>Ваша корзина пуста</h2>
    <a href="/frontend/web/category" class="btn -black" data-pjax="0">Вернуться в каталог</a>
  </div>
</section>
<?php endif; ?>