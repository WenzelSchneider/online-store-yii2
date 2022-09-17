<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use common\models\GoodFavourites;
use common\models\GoodImages;

/* @var $this yii\web\View */

$this->title = 'Личный кабинет - Урал Декор';

$good_favor = GoodFavourites::find()
->where(['good_id'=>$_GET['id']])
->andWhere(['client_id' => $_COOCKIE['usere']])
->one();
$ishide = $good_favor['is_hide'];
?>

    <!--breadcrumbs-->
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="breadcrumbs">
					<a class="breadcrumbs__link" href="/frontend/web/"> Главная страница </a> / 
						<a class="breadcrumbs__link" href="/frontend/web/user/index"> Личный кабинет </a>
				</div>
			</div>
		<div class="col-3 backbox top-indent-50">
			    <a href="/frontend/web/" class="backbox__link"></a>
			</div>
		</div>
	</div>

	<!--desc-block-product-->
	<div class="container top-indent-25">
		<div class="row">
			<div class="col-8">
				<h1>Личный кабинет</h1>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-6 col-md-4 col-lg-3">
			    <h3>Ваши данные:</h3>
				<div class="lk__info-table top-indent-50">
					<table>
						<tbody>
							<tr>
								<td class="bold">Тип акк: </td>
								<td><?= ($clientdat['type'] == 'jur') ? 'Юридическое лицо' : 'Физическое лицо'; ?></td>
							</tr>
							<tr>
								<td class="bold">Компания: </td>
								<td><?=$clientdat['namecompany'] ?></td>
							</tr>
							<tr>
								<td class="bold">Телефон: </td>
								<td><?=(!empty($clientdat['numberphone'])) ? $clientdat['numberphone'] : ''?></td>
							</tr>
							<tr>
								<td class="bold">Email: </td>
								<td><?=(!empty($clientdat['email'])) ? $clientdat['email'] : ''?></td>
							</tr>
							<tr>
								<td class="bold">Адрес: </td>
								<td><?=(!empty($clientdat['address_fact'])) ? $clientdat['address_fact'] : ''?></td>
							</tr>
							<tr>
								<td class="bold">Имя: </td>
								<td><?=(!empty($clientdat['name'])) ? $clientdat['name'] : ''?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="lk__info-edit top-indent-25">
				    <a data-modal="#edition" class="just-link open-modal mt-1 mb-1 pl-1 pr-1">Изменить данные</a>
				    <br>
				    <a data-modal="#changepass" class="just-link open-modal mt-1 mb-1 pl-1 pr-1">Изменить пароль</a>
				</div>
			</div>
			<div class="col-12 col-md-5 col-lg-7 d-flex justify-content-between flex-dir-col">
			    <h3>Ваш список избранного:</h3>
			    <?php $c=0; ?>
			    <div class="d-flex">
			    <?php foreach ($goods as $good): ?>
            		<?php
            		  $image = GoodImages::find()
            		  ->where(['good_id'=>$good['good_id']])
            		  ->andFilterWhere(['is_main'=>1])
            		  ->asArray()
            		  ->one();
            		  if(!empty($image['image_link'])){
            		      $img = $image['image_link'];//frontend/web/images/goods/
            		  }else{
            		     $img = 'https://le-go.net/images/noimage.gif';
            		  }
            		?>
    				<div class="lk-product top-indent-25">
    					<div class="lk-product__img"><img src="<?= $img ?>" alt="<?= $good['title'] ?>"></div>
    					<div class="lk-product__content">
    						<div class="lk-product__title"><?= mb_strimwidth($good['title'], 0, 45, "...") ?></div>
    						<div class="lk-product__footer">
    							<div class="lk-product__code"><?= $good['description']?></div>
    							<div class="lk-product__cost">
    							    <?php if($good['discount'] == 0): ?>
    								    <div class="product__cost"><?= $good['value'] ?> &#8381;</div>
    								<? else: ?>
    								    <div class="product__cost"><?= round($good['value']  / 100 * (100 - $good['discount']), 2) ?> &#8381;</div>
    								<?php endif;?>
    							</div>
    						</div>
    						<a href="/frontend/web/product/product?id=<?= $good['good_id'] ?>" class="box-link"></a>
    					</div>
    				</div>
                <?php endforeach; ?>
                </div>
			</div>
			<div class="col-6 col-md-3 col-lg-2">
			    <h3>Ваши заказы:</h3>
				<ul class="lk-list-main top-indent-50">
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $item):?>
                            <li class="lk-list-main__item"><a href="/frontend/web/user/replay-order?id=<?=$item->order_id;?>">Заказ #<?=$item['order_id']?></a></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
				</ul>
			</div>
		</div>
	</div>
