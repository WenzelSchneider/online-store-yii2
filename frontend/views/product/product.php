<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use common\models\GoodImages;
use common\models\ClientJurData;
use common\models\ClientFizData;
use common\models\GoodCategories;
use common\models\Groups;
use common\models\CatGroupRels;
use common\models\Goods;
use common\models\GoodFavourites;
use common\models\Manufacturers;

$this->title = mb_strtoupper($good['title']).' - Урал Декор, каталог продукции
';
$this->params['breadcrumbs'][] = $this->title;

$groups = Groups::findOne($good['cat_id']);
$group_id = $good['cat_id'];
$group_name = $groups->name;

$catgroups = CatGroupRels::find()
    ->select('cat_id')
    ->where(['group_id' => $group_id])
    ->one();
$category_id = $catgroups['cat_id'];

$category = GoodCategories::findOne($catgroups['cat_id']);
$category_name = $category->cat_title;
$cat_top_id = $category->top_cat_id;

$cat_top = GoodCategories::findOne($cat_top_id);
$cat_top_name = $cat_top->cat_title;


if(!empty($image)){$imgd = $image["0"]["image_link"];}else{$imgd ='https://le-go.net/images/noimage.gif';}
    
    \Yii::$app->view->registerMetaTag([
    'property' => 'og:image',
    'content' => $imgd,
]);
    \Yii::$app->view->registerMetaTag([
    'property' => 'og:title',
    'content' => $good['title'],
]);
    \Yii::$app->view->registerMetaTag([
    'property' => 'og:description',
    'content' => HtmlSpecialChars_Decode($good['fuldesc']),
]);
    \Yii::$app->view->registerMetaTag([
    'property' => 'og:site_name',
    'content' => 'УралДекор',
]);

?>

<?php
  $image = GoodImages::find()
  ->where(['good_id'=>$good['good_id']])
  ->asArray()
  ->all();
?>





<!-- PRODUCT -->

<section class="breadcrumbs">
 <div class="container">
    <a href="/frontend/web/" class="breadcrumbs__link">Главная</a> /
    <a href="/frontend/web/product/index" class="breadcrumbs__link">Каталог</a> /
    <a href="/frontend/web/product/index?catid=<?= $group_id?>" class="breadcrumbs__link"><?= $group_name?></a> /
    <a href="/frontend/web/product/product?id=<?= $good['good_id']?>" class="breadcrumbs__link"><span><?= $good['title']?></span></a>
 </div>
</section>


<section class="single-product">
      <div class="container flex fw-wrap">
        <div class="box-50">
          <!-- ИЗОБРАЖЕНИЯ ТОВАРА -->
          <div class="product-img flex fj-between">
            <div class="box-left">
                <?php
                    foreach ($image as $key => $value) {
                        echo '
                        <div class="box">
                                <img src="'.$value["image_link"].'" alt="UD">
                        </div>';
                    }
                ?>
            </div>
            <div class="box-right">
              <div class="box">
                <img src="<?php if(!empty($image)){echo $image["0"]["image_link"];}else{echo 'https://le-go.net/images/noimage.gif';}?>" alt="опиание" />
              </div>
            </div>
          </div>
        </div>
        <div class="box-50 product-content">
          <!-- НАЛИЧИЕ -->
          <div class="product-top-group flex fa-center">
            <span class="art">Арт. <?= $good['description']?></span>
            <span class="available"><?=($good['in_stock'] >= 1)?'В наличии':'Нет в наличии';?></span>
			<?=($good['sale'] == 1)?'<span class="available" style="background:red!important;color:#fff!important">SALE</span>':' ';?>
          </div>

          <!-- ЗАГОЛОВОК -->
          <h1 class="top-indent-25"><?= $good['title'] ?></h1>
          <!-- ОПИСАНИЕ -->
          <p class="top-indent-25">
            <?= HtmlSpecialChars_Decode($good['fuldesc']) ?>
          </p>
          <!-- ХАРАКТЕРИСТИКИ -->
          <div class="features">
            <h5 class="top-indent-25">Характеристики:</h5>
            <?php
                foreach ($opti as $option):
                    echo '  <dl class="dl-inline">
                                <dt class="dt-dotted">
                                    <span> '.$option['option'].' </span>
                                </dt>
                                <dd>'.$option['value'].'</dd>
                            </dl>';
				endforeach; 
			?>
          </div>
          <!-- КОЛИЧЕСТВО + КНОПКА -->
          <div class="btn-group flex fa-center fj-between ">
            <div class="flex fa-center fj-between btn-group-wrap">
              <?php
		        if($good['in_stock'] <= 0){
		        	echo '<a class=".btn-outline add-to-basket" data-cnt="1">Нет в наличии</a>' ;
		        }else{
		        	echo '<a class="btn -black add-to-basket" data-cnt="1" data-prod="' . $good['good_id'] . '">В корзину</a>';
		        }
		       ?>

              
              <div class="qty product-info__count">
                <div class="count-panel flex fa-center count-panel">
                  <div class="count-panel__btn count-panel__sub count-panel__sub" data-id="<?=$good['good_id']?>">
                    -
                  </div>
                  <input
                    class="count-panel__digit add-count add-count count-panel__digit" data-id="<?=$good['good_id']?>" value="1"/>
                  <div class="count-panel__btn count-panel__add count-panel__add" data-id="<?=$good['good_id']?>">
                    +
                  </div>
                </div>
              </div>
            </div>
			<div class="price"><?= $good['value'] ?> <span class="cost-desc">&#8381;/<?=$good['termin']?></span></div>
          </div>
          <!-- ДОП. ССЫЛКИ -->
          <div class="product-footer flex fa-center">
            <div class="box-product">
              <img src="/frontend/web/media/icons/2.svg" />Доставка товара
            </div>
            <div class="box-product">
              <img src="/frontend/web/media/icons/3.svg" />Где посмотреть?
            </div>
          </div>
        </div>
      </div>
</section>
    
	<!--desc-block-product
                        <?php foreach($reviews as $review):?>
    						<div class="product-content__review">
    							<p><?=$review->review_text?></p>
    							<div class="product-content__review-footer">
    								<span class="stars">
    								    <?=($review->review_rate>0)? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>'?>
    								</span>
    								<?php
                                    $cj = ClientJurData::find()
                                    ->where(['client_id'=>$review->client_id])
                                    ->one();
                                    $cf = ClientFizData::find()
                                    ->where(['client_id'=>$review->client_id])
                                    ->one();
                                    ?>
    								<span class="name"><?= (isset($cj)) ? $cj->title_full : $cf->firstname ?>, Оренбург</span>
    							</div>
    						</div>
                        <?php endforeach;?>
                        -->
                        
	<!--related_product-->
	<div class="container top-indent-50 <?=(empty($related_goods))? 'd-none' : '' ;?>">
	    <div class="row">
			<div class="">
				<span class="h2">Сопутствующие товары</span>
			</div>
		</div>
		<div class="row top-indent-25">
			<div class="col d-flex justify-content-around position-relative">
			    
			    <?php foreach ($related_goods as $good): ?>
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
    				<div class="product">
    					<div class="product__block">
    						<div class="product__container">
    						    <?= ($good['is_new'] == 1) ? '<div class="product__sale"><span>Новинка сезона</span></div>' : ''; ?>
    							<div class="product__img-block"><img src="<?= $img ?>"
    									alt="<?= $good['title'] ?>"></div>
    							<div class="product__content">
    								<div class="product__title"><a href="/frontend/web/product/product?id=<?= $good['good_id'] ?>"><span class="product__title_biggest"><?= $good['title'] ?></span><br><span class="product__title_smalllest"></span></a></div>
    								<div class="product__articl"><?= $good['description']?></div>
    							    <div class="product__cost"><?= $good['value'] ?> <span class="cost-desc">&#8381;/<?=$good['termin']?></span></div>
    				
    							</div>
    							<a href="/frontend/web/product/product?id=<?= $good['good_id'] ?>" class="stretched-link"></a>
    							<div class="product__btn-block">
    							    <?php
    							        if($good['in_stock'] <= 0){
    							        	echo '<a class="product__btn add-to-basket" data-cnt="1">Нет в наличии</a>' ;
    							        }else{
    							        	echo '<a class="product__btn add-to-basket" data-cnt="1" data-prod="' . $good['good_id'] . '">Добавить в корзину</a>';
    							        }
    							    ?>
    							</div>
    						</div>
    					</div>
    				</div>
                <?php endforeach; ?>
				
			</div>
		</div>
	</div>
	<!--product-->
	<div class="container top-indent-50  <?=(empty($goods))? 'd-none' : '' ;?>">
	    
				<span class="h2">Похожие товары</span>
		
	
		<div class="row top-indent-25">
			<div class="col d-flex justify-content-around position-relative">
			    
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
    				<div class="product">
    					<div class="product__block">
    						<div class="product__container">
    						    <?= ($good['is_new'] == 1) ? '<div class="product__sale"><span>Новинка сезона</span></div>' : ''; ?>
    							<div class="product__img-block"><img src="<?= $img ?>"
    									alt="<?= $good['title'] ?>"></div>
    							<div class="product__content">
    								<div class="product__title"><a href="/frontend/web/product/product?id=<?= $good['good_id'] ?>"><span class="product__title_biggest"><?= $good['title'] ?></span><br><span class="product__title_smalllest"></span></a></div>
    								<div class="product__articl"><?= $good['description']?></div>
    						        <div class="product__cost"><?= $good['value'] ?> <span class="cost-desc">&#8381;/<?=$good['termin']?></span></div>
    	
    							</div>
    							<a href="/frontend/web/product/product?id=<?= $good['good_id'] ?>" class="stretched-link"></a>
    							<div class="product__btn-block">
    							    <?php
    							        if($good['in_stock'] <= 0){
    							        	echo '<a class="product__btn add-to-basket" data-cnt="1">Нет в наличии</a>' ;
    							        }else{
    							        	echo '<a class="product__btn add-to-basket" data-cnt="1" data-prod="' . $good['good_id'] . '">Добавить в корзину</a>';
    							        }
    							    ?>
    							</div>
    						</div>
    					</div>
    				</div>
                <?php endforeach; ?>
				
			</div>
		</div>
	</div>
	

