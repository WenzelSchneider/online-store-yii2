<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use common\models\GoodImages;
use common\models\NewsImages;
use common\models\ContentMainSlider;
use common\models\GoodCategories;

/* @var $this yii\web\View */

$this->title = 'Урал Декор | Каталог мебельной фурнитуры и плитной продукции для производства мебели';
?>


<!-- SECTION main -->
<section class="slider">
<div class="splide" data-splide='{"type":"slide","autoplay":true,"interval":6000,"perMove":1,"speed":1400,"resetProgress":false,"pauseOnHover":false,"rewind":true}'>
	<div class="splide__track">
		<ul class="splide__list">
		   <li class="splide__slide">
				<div class="splide__content">
					<span>Акция</span>
					<h2>Скидка на МДФ / ЛМДФ</h2>
					<p>МДФ шлиф. 10 мм KASTAMONU- 3400руб, ЛМДФ KASTAMONU- 6500руб</p>
					<a href="/frontend/web/product/index?category=4" class="btn -black">В каталог</a>
				</div>
				<img src="/frontend/web/media/main-slider/slide-mdf.jpg" alt="акция" />
			</li>
			<li class="splide__slide">
				<div class="splide__content">
					<span>Вакансии</span>
					<h2>Мы в поисках заведующего складом</h2>
					<p>В МАГАЗИН требуется заведующий складом.</p>
					<a href="/frontend/web/site/new?id=29" class="btn -black">Подробнее</a>
				</div>
				<img src="/frontend/web/media/main-slider/011.jpg" alt="вакансия" />
			</li>
			<li class="splide__slide">
				<div class="splide__content">
					<span>Распродажа</span>
					<h2>Распроадажа мебельных ручек, скидки до 70%</h2>
					<p>Большая распродажа МЕБЕЛЬНЫХ РУЧЕК BOYARD! Ищите товар с пометкой SALE в каталоге на сайте и офисах продаж.</p>
					<a href="/frontend/web/product/index?category=13&min_price=21.00&max_price=5566.00&brand%5B%5D=Распродажа" class="btn -black">В каталог</a>
				</div>
				<img src="/frontend/web/media/main-slider/slide01.jpg" alt="акция" />
			</li>
			<li class="splide__slide">				
				<div class="splide__content">
					<span>Услуги</span>
					<h2>Изготовление фасадов по вашим размерам</h2>
					<p>Компания «Урал Декор» предлагает мебельные фасады самых разных ценовых категорий и размеров под заказ.</p>
					<a href="/frontend/web/site/services" class="btn -black">Подробнее</a>
				</div>
				<img src="/frontend/web/media/main-slider/slide02.jpg" alt="акция" />
			</li>
			<li class="splide__slide">
				<div class="splide__content">
					<span>Услуги</span>
					<h2>Мебель на заказ по вашим эскизам</h2>
					<p>Компания «Урал Декор» оказывает услуги проектировки, изготовления и сборки корпусной мебели.</p>
					<a href="/frontend/web/site/services" class="btn -black">Подробнее</a>
				</div>
				<img src="/frontend/web/media/main-slider/slide03.jpg" alt="акция" />
			</li>
		</ul>
	</div>
</div>
</section>

<section class="services">
	<div class="container">
		<div class="service-box">

		</div>
	</div>
</section>



<div class="container"><h2 style="margin:140px 0 30px 0;text-align:center">Акции и спецпредложения</h2></div>
<section>
    <div class="container" style="padding: 4rem 20px 4rem 20px">
        <div class="row">
	
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
                                    <div class="product__articl" <?=$good['type_id']?>>Арт. <?= $good['description']?></div>
                                    <div class="product__title"><a href="/frontend/web/product/product?id=<?= $good['good_id'] ?>"><span class="product__title_biggest"><?= $good['title'] ?></span><br><span class="product__title_smalllest"></span></a></div>
                                    <div class="product__cost"><?= $good['value'] ?> <span class="cost-desc">&#8381;/<?=$good['termin']?></span></div>
                                </div>
                                <?= ($good['sale'] == 1) ? '<div class="product__sale"><span>SALE</span></div>' : ''; ?>
                                <a href="/frontend/web/product/product?id=<?= $good['good_id'] ?>" class="stretched-link"></a>
                                <div class="product__btn-block">
    							    <?php
    							        if($good['in_stock'] <= 0){
    							        	echo '<a class="btn -black add-to-basket" data-cnt="1">Нет в наличии</a>' ;
    							        }else{
    							        	echo '<a class="btn -black add-to-basket" data-cnt="1" data-prod="' . $good['good_id'] . '">В корзину</a>';
    							        }
    							    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
        </div>
    </div>
</section>
<section>
	
    <div class="container" style="padding: 4rem 20px 4rem 20px">
        <div class="row">
                            <?php foreach ($hottery as $good): ?>
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
                                    <div class="product__articl" <?=$good['type_id']?>>Арт. <?= $good['description']?></div>
                                    <div class="product__title"><a href="/frontend/web/product/product?id=<?= $good['good_id'] ?>"><span class="product__title_biggest"><?= $good['title'] ?></span><br><span class="product__title_smalllest"></span></a></div>
									<div class="product__cost"><?= $good['value'] ?> <span class="cost-desc">&#8381;/<?=$good['termin']?></span></div>
                                </div>
                                <?= ($good['sale'] == 1) ? '<div class="product__sale"><span>SALE</span></div>' : ''; ?>
                                <a href="/frontend/web/product/product?id=<?= $good['good_id'] ?>" class="stretched-link"></a>
                                <div class="product__btn-block">
    							    <?php
    							        if($good['in_stock'] <= 0){
    							        	echo '<a class="btn -black add-to-basket" data-cnt="1">Нет в наличии</a>' ;
    							        }else{
    							        	echo '<a class="btn -black add-to-basket" data-cnt="1" data-prod="' . $good['good_id'] . '">В корзину</a>';
    							        }
    							    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
        </div>
    </div>
</section>

<!--
<section class="services">
	<div class="container" style="padding:100px 0 20px 0; text-align:center"><h2>Наши услуги</h2></div>
	<div class="flex fw-wrap fj-between">
		<div class="services-item">
			<img src="img/34641.png" alt="Производство мебели" />
			<h3>Производство мебели</h3>
			<a href="#">Подробнее</a>
		</div>
		<div class="services-item">
			<img src="/frontend/web/images/doors.jpg" alt="Производство мебели" />
			<h3>Изготовление дверей-купе</h3>
			<a href="#">Подробнее</a>
		</div>
		<div class="services-item">
			<img src="img/34641.png" alt="Производство мебели" />
			<h3>Фасады под заказ</h3>
			<a href="#">Подробнее</a>
		</div>
		<div class="services-item">
			<img src="img/34641.png" alt="Производство мебели" />
			<h3>Услуги распила</h3>
			<p>В ТД "Урал Декор" можно заказать услугу распила ЛДСП, ДСП, МДФ и другого листового материала по индивидуальным размерам.</p>
			<a href="#">Подробнее</a>
		</div>
	</div>
</section>
									-->



<!-- SECTION news -->
<section class="news">
	<div class="container">
		<h5>Новости</h5>
	</div>
	<div class="container flex fw-wrap">
	    <?php foreach ($news as $new): ?>					
        <?php
                    	  $imagenew = NewsImages::find()
                    	  ->where(['new_id'=>$new->new_id])
                    	  ->andFilterWhere(['is_main'=>1])
                    	  ->asArray()
                    	  ->one();
                    	  if(!empty($imagenew['image_link'])){
                    	     $img = $imagenew['image_link'];//frontend/web/images/goods/
                    	  }else{
                    	     $img = 'https://le-go.net/images/noimage.gif';
                    	  }
                    	  Yii::$app->formatter->asDate('now', 'php:y-m-d')
                    	?>
    					<div class="new-wrap">
    						
    							<img src="<?=$img?>" alt="Урал Декор">
    							<div class="new-wrap-bg">
    								<p class="img-block-fh__title"><?= $new->new_title ?></p>
    								<p class="img-block-fh__content"><?= mb_strimwidth(HtmlSpecialChars_Decode($new->new_short_description), 0, 50, "..."); ?></p>
    								<a href="/frontend/web/site/new?id=<?= $new->new_id ?>" class="img-block-fh__link just-link">Читать далее..</a>
    							</div>
    					   
    					</div>
        <?php endforeach; ?>
	</div>
	<div class="container flex fj-center">
	    <a href="/frontend/web/site/projects" class="btn -black">Читать еще</a>
	</div>
</section>


<section class="partners">
<div class="container"><h2 style="padding:140px 0 30px 0;text-align:center">Наши партнеры</h2></div>
<div id="splide-partners" class="splide" data-splide='{"breakpoints": {"640": {"perPage":2}},"type":"slide","rewind":true,"perPage":6,"arrows":false,"autoplay":true,"interval":2000,"pagination":false,"perMove":1,"speed":1000,"resetProgress":false,"pauseOnHover":false}'>
	<div class="splide__track">
		<ul class="splide__list">
			<li class="splide__slide">
				<img src="/frontend/web/media/partners/logo1.png" alt="распродажа ручек" />
			</li>
			<li class="splide__slide">				
				<img src="/frontend/web/media/partners/logo2.jpg" alt="распродажа ручек" />
			</li>
			<li class="splide__slide">
				<img src="/frontend/web/media/partners/logo3.png" alt="распродажа ручек" />
			</li>
			<li class="splide__slide">				
				<img src="/frontend/web/media/partners/logo4.jpg" alt="распродажа ручек" />
			</li>
			<li class="splide__slide">
				<img src="/frontend/web/media/partners/logo5.png" alt="распродажа ручек" />
			</li>
			<li class="splide__slide">
				<img src="/frontend/web/media/partners/logo6.jpg" alt="распродажа ручек" />
			</li>
			<li class="splide__slide">				
				<img src="/frontend/web/media/partners/logo7.jpg" alt="распродажа ручек" />
			</li>
			<li class="splide__slide">
				<img src="/frontend/web/media/partners/logo8.png" alt="распродажа ручек" />
			</li>
		</ul>
	</div>
</div>

</section>


<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/js/splide.min.js"></script>

<script>
	document.addEventListener( 'DOMContentLoaded', function () {
		new Splide( '.splide' ).mount();
	
	} );
	document.addEventListener( 'DOMContentLoaded', function () {
		new Splide( '#splide-partners' ).mount();
	
	} );
	
</script>

