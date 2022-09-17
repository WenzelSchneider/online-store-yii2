<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use common\models\GoodCategories;
?>
<div class="container-fluid shadow">
    <div class="row">
        <div class="container">
    		<div class="row position-relative">
    			<div class="col-12 col-xl-10 offset-xl-1 d-lg-block d-none">
    				<div class="mid-menu">
    					<ul class="mid-menu__list" id="mid-menu">
    						<li class="mid-menu__item js-menu js-menu-trigger" data-menu="1"><a href="/frontend/web/category/index?category=36">Мебельная фурнитура</a></li>
    						<li class="mid-menu__item js-menu js-menu-trigger" data-menu="2"><a href="/frontend/web/category/index?category=35">Плитные материалы</a></li>
    						<li class="mid-menu__item js-menu right-border"><a href="/frontend/web/product/index?category=40">Бытовая техника</a></li>
    					    <li class="mid-menu__item js-menu"><a href="/frontend/web/site/finifur">Мебельный зал</a></li>
    						<li class="mid-menu__item js-menu"><a href="/frontend/web/site/customfurn">Мебель на заказ</a></li>
    						<li class="mid-menu__item js-menu"><a href="/frontend/web/site/services">Услуги производства</a></li>
    					</ul>
    					<div class="mid-menu__drop-block js-menu-content" data-menu="1">
    					    <!--Мебельная фурнитура-->
    						<?php 
                                $category = GoodCategories::find()
                                ->where(['is_hide'=>0])
                                ->andWhere(['top_cat_id'=>36])
                                ->all();
                                $counter = 0;
                            ?>
    						<ul class="mid-menu__drop-list">
    						    <?php foreach ($category as $cat): ?>
    						    <?$counter++;?>
    						    <?if($counter == 9):?>
    						    <?$counter=0;?>
    							</ul>
    							<ul class="mid-menu__drop-list">
    						    <?endif?>
    								<li class="mid-menu__drop-item"><a href="<?='/frontend/web/product/index?category='.$cat->cat_id;?>"><?=$cat->cat_title?></a></li>
    							<?php endforeach; ?>
    						</ul>
    						<div class="mid-menu__img-block">
    							<div class="mid-menu__img-block">
    								<img src="/frontend/web/img/_src/menu/furn_menu.jpg" alt="">
    							</div>
    							<div class="mid-menu__img-text">
    								<p>Фурнитура под любой интерьер</p>
    								<a href="/frontend/web/category/index?category=36" class="btn-dark dark-btn">Перейти в каталог &nbsp;&nbsp;&nbsp;	<span class="icon-arrow-right"></span></a>
    							</div>
    						</div>
    						<div class="mid-menu__clear r"></div>
    						<div class="mid-menu__clear b"></div>
    						<div class="mid-menu__clear l"></div>
    						<div class="mid-menu__clear t"></div>
    					</div>
    					<div class="mid-menu__drop-block js-menu-content" data-menu="2">
    					    <!--Плитные материалы-->
    						<?php 
                                $category = GoodCategories::find()
                                ->where(['is_hide'=>0])
                                ->andWhere(['top_cat_id'=>35])
                                ->all();
                                $counter = 0;
                            ?>
    						<ul class="mid-menu__drop-list">
    						    <?php foreach ($category as $cat): ?>
    						    <?$counter++;?>
    						    <?if($counter == 7):?>
    						    <?$counter=0;?>
    							</ul>
    							<ul class="mid-menu__drop-list">
    						    <?endif?>
    								<li class="mid-menu__drop-item"><a href="<?='/frontend/web/product/index?category='.$cat->cat_id;?>"><?=$cat->cat_title?></a></li>
    							<?php endforeach; ?>
    						</ul>
    						<div class="mid-menu__img-block">
    							<div class="mid-menu__img-block">
    								<img src="/frontend/web/img/_src/menu/plit_menu.jpg" alt="">
    							</div>
    							<div class="mid-menu__img-text">
    								<p>Большой выбор столешниц</p>
    								<a href="/frontend/web/category/index?category=35" class="btn-dark dark-btn">Перейти в каталог &nbsp;&nbsp;&nbsp;	<span class="icon-arrow-right"></span></a>
    							</div>
    						</div>
    						<div class="mid-menu__clear r"></div>
    						<div class="mid-menu__clear b"></div>
    						<div class="mid-menu__clear l"></div>
    						<div class="mid-menu__clear t"></div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
	</div>
</div>