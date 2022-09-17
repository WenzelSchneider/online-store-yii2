<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use common\models\GoodFavourites;
use common\models\GoodImages;
use common\models\OrderContents;
use common\models\Goods;

/* @var $this yii\web\View */

$this->title = 'Личный кабинет | Урал Декор';

$good_favor = GoodFavourites::find()
->where(['good_id'=>$_GET['id']])
->andWhere(['client_id' => $_COOCKIE['usere']])
->one();
$ishide = $good_favor['is_hide'];

function num2word($num, $words)
{
    $num = $num % 100;
    if ($num > 19) {
        $num = $num % 10;
    }
    switch ($num) {
        case 1: {
            return($words[0]);
        }
        case 2: case 3: case 4: {
            return($words[1]);
        }
        default: {
            return($words[2]);
        }
    }
}

            $state = array(
                "0" => "Ожидается согласование",
                "1" => "Ожидается аванс (до обеспечения)",
                "2" => "Готов к обеспечению",
                "3" => "Ожидается предоплата (до отгрузки)",
                "4" => "Ожидается обеспечение",
                "5" => "Готов к отгрузке",
                "6" => "В процессе отгрузки",
                "7" => "Ожидается оплата (после отгрузки)",
                "8" => "Готов к закрытию",
                "9" => "Закрыт",
            );
?>

    <!--breadcrumbs-->
	<section class="breadcrumbs">
 <div class="container">
    <a href="/frontend/web/" class="breadcrumbs__link">Главная</a> /
    <a href="/frontend/web/user/index" class="breadcrumbs__link">Личный кабинет</a>
 </div>
</section>

<section class="personal-panel">
	<div class="container">
	   <h1>Личный кабинет</h1>
	</div>
	<div class="container flex fw-wrap">
	<div class="col-left">
	<h3>Мои учетные данные</h3>
	<table>
		<tbody>
			<tr>
				<td class="bold">Учетная запись: </td>
				<td><?= ($clientdat['type'] == 'jur') ? 'Юридическое лицо' : 'Физическое лицо'; ?></td>
			</tr>

			<?=(!empty($clientdat['namecompany'])) ? 
			
			'<tr>
				<td class="bold">Компания: </td>
				<td>' . $clientdat['namecompany'] . '</td>
			</tr>'
			
			: ''?>

			<tr>
				<td class="bold">Телефон: </td>
				<td><?=(!empty($clientdat['numberphone'])) ? $clientdat['numberphone'] : ''?></td>
			</tr>

			<tr>
				<td class="bold">Email: </td>
				<td><?=(!empty($clientdat['email'])) ? $clientdat['email'] : ''?></td>
			</tr>

			<?=(!empty($clientdat['address_fact'])) ? 
			
			'<tr>
				<td class="bold">Адрес: </td>
				<td>' . $clientdat['address_fact'] . '</td>
			</tr>'
			
			: ''?>
			
			<tr>
				<td class="bold">ФИО: </td>
				<td><?=(!empty($clientdat['name'])) ? $clientdat['name'] : ''?></td>
			</tr>
		</tbody>
	</table>
	<div class="lk__info-edit">
		<a data-modal="#edition" class="just-link open-modal mt-1 mb-1 pl-1 pr-1">Изменить данные</a>
		
		<a data-modal="#changepass" class="just-link open-modal mt-1 mb-1 pl-1 pr-1">Изменить пароль</a>

		<a href="/frontend/web/site/logaut" class="just-link mt-1 mb-1 pl-1 pr-1">Выйти из учетной записи</a>
	</div>
		</div>
		<?php if($_SESSION['clientdat']['price_type_ldsp'] != 3 OR $_SESSION['clientdat']['price_type'] != 11):?>
		<div class="col-right">
		<h3>Полезные ссылки</h3>
			<div class="panel-info">
				<a target="_blank" href="https://cloud.bazissoft.ru/cutting/ru/#/client/auth/login?user=85"><img style="width:100px; margin-right:20px" src="/frontend/web/media/bazis.png" alt="Базис Мебельщик" /> Сделать заказ в программе Базис Мебельщик <ion-icon name="arrow-redo-outline"></ion-icon></a>
				
			</div>
		</div>
		<?php endif;?>
	</div>

	<!-- Заказы -->
	<div class="container">
		<h3>Мои заказы</h3>
	    <?php foreach ($orders as $item): ?>
            <?php
				$products = OrderContents::find()
				->where(['order_id'=>$item['order_id']])
				->all();
				$i=1;
				$balance = $item['balance_plit']+$item['balance_furn'];
				if($balance==0){ $coin = 'Ожидает оплаты';}
				elseif($item['order_summ'] > $balance){$coin = 'Частично оплачено';}
				elseif($item['order_summ'] <= $balance){$coin = 'Оплачено';}
			?>
		    <div class="personal-order-box">

			<div class="pob-top">
			    <div class="pob-col pob-id">
				   <span class="pob-title">№ </span>
				   <span><?=$item['order_id']?><span>
			    </div>

				<div class="pob-col pob-status">
				   <span class="pob-title">Статус </span>
				   <span><?=(($item['sync_plit_return'] < $item['sync_furn_return'])) ? $state[$item["sync_plit_return"]] : $state[$item["sync_furn_return"]] ?><span>
			    </div>

				<div class="pob-col pob-price">
				   <span class="pob-title">Сумма </span>
				   <span><?= $item['order_summ']?> ₽<span>
			    </div>

				<div class="pob-col pob-adr">
				   <span class="pob-title">Адрес получения</span>
				   <span><?=$item['delivery_address']?><span>
			    </div>

				<div class="pob-col pob-date">
				   <span class="pob-title">Дата</span>
				   <span><?= date('d.m.Y', strtotime($item['created_datetime'])); ?><span>
			    </div>

				<div class="pob-col pob-buy-again">
				    <a class="pob-toggle"><ion-icon name="chevron-forward-outline"></ion-icon></a>
			    </div>

		</div>
		<div class="pob-bottom">
				<div class="pob-products">
				    <?php foreach ($products as $product): ?>

						<?php
                      $image = GoodImages::find()
                      ->where(['good_id'=>$product['good_id']])
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
                            $prod = Goods::find()
                            ->where(['good_id'=>$product['good_id']])
                            ->one();
                            ?>
    	             
        					<div class="pob-product" data-order="<?=$item['order_id']?>">

							    <div class="pob-product-img">
                                   <img src="<?= $img ?>">
                                </div>

								<div class="pob-product-title">
                                   <a class="name"><?=mb_strimwidth($prod['title'], 0, 50, "...")?></a>
                                   <span class="art">Арт: <?=$prod['description']?></span>
                                   <span class="price">Цена: <?=$product['sum_per_one']?> ₽</span>
                                </div>

								<span class="pob-product-counter">Кол-во: <?=$product['amount']?></span>

        						<div class="pob-product-total"><span><?=$product['sum_total']?> ₽</span></div>
        					</div>
                    <?php endforeach; ?>
				</div>
               <div class="attention"><span>Внимание! Уважаемые покупатели, обращаем ваше внимание, что оформляя заказ через "Повтор заказа" вы соглашаетесь с правилами и условиями использования сайта. Цены указанные на сайте могут отличаться от фактических, после оформления заказа наш менеджер позвонит для уточниня и обязательно предупредит об изменениях в стоимости.</span> <a href="/frontend/web/user/replay-order?id=<?=$item->order_id;?>" class="btn -black -repeat-order">Повторить заказ</a></div>
				
					</div>

		    </div>
		<?php endforeach; ?>
	</div>
</section>


	<div class="container">

<!--
			<div class="col-12 col-md-7 col-lg-8 d-flex justify-content-between flex-dir-col">
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
-->
			
	</div>
