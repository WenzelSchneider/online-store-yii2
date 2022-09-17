<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use common\models\GoodCategories;
?>
	<!--list-->
	<div class="container top-indent-100">
		<div class="row">
			<div class="col-12 col-lg-4 top-indent-25">
				<div class="row">
					<div class="col-12">
						<h3>Новая коллекция мебели!</h3>
						<p>Приглашаем посетить наш первый в городе мебельный шоу-рум на Туркестанской 68 (2 этаж)!</p>
						<div class="img-w-m">
							<a href="https://ural-dekor.ru/frontend/web/site/finifur"><img src="/frontend/web/img/showroom-uraldecor.png" alt="UralDecor"></a>
						</div>
					</div>
					<div class="col-12 d-none d-md-block">
						<h3>Бытовая техника</h3>
						<div class="row">
							<div class="col-6">
								<ul class="list-main">
									<li class="list-main__item"><a href="/frontend/web/product/index?category=40&min_price=3455.00&max_price=61490.00&property%5B1162%5D%5B%5D=10665">Кухонные вытяжки</a></li>
									<li class="list-main__item"><a href="/frontend/web/product/index?category=40&min_price=3455.00&max_price=100000&property%5B1162%5D%5B%5D=10661">Варочные поверхности</a></li>
									<li class="list-main__item"><a href="/frontend/web/product/index?category=40&min_price=3455.00&max_price=100000&property%5B1162%5D%5B%5D=10662">Духовые шкафы</a></li>
									<li class="list-main__item"><a href="/frontend/web/product/index?category=40&min_price=3455.00&max_price=100000&property%5B1162%5D%5B%5D=10663">Микроволновые печи</a></li>
									<li class="list-main__item"><a href="/frontend/web/product/index?category=40&min_price=3455.00&max_price=100000&property%5B1162%5D%5B%5D=10902">Посудомоечные машины</a></li>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-lg-8 top-indent-25">
				<div class="row">
					<div class="col-10 col-md-6">
						<h3>Плитные материалы</h3>
						<?php 
                            $category = GoodCategories::find()
                            ->where(['is_hide'=>0])
                            ->andWhere(['top_cat_id'=>35])
                            ->all();
                            $counter = 0;
                        ?>
						<div class="row">
							<div class="col-6">
								<ul class="list-main">
    						    <?php foreach ($category as $cat): ?>
    						    <?$counter++;?>
    						    <?if($counter == 5):?>
								</ul>
							</div>
							<div class="col-6">
								<ul class="list-main">
    						    <?endif?>
    								<li class="list-main__item"><a href="<?='/frontend/web/product/index?category='.$cat->cat_id;?>"><?=$cat->cat_title?></a></li>
    							<?php endforeach; ?>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-10 col-sm-12 order-md-last">
						<h3>Мебельная фурнитура</h3>
						<?php 
                            $category = GoodCategories::find()
                            ->where(['is_hide'=>0])
                            ->andWhere(['top_cat_id'=>36])
                            ->all();
                            $counter = 0;
                        ?>
						<div class="row">
							<div class="col-6 col-sm-3">
								<ul class="list-main">
    						    <?php foreach ($category as $cat): ?>
    						    <?$counter++;?>
    						    <?if($counter == 8):?>
    						    <?$counter=0;?>
								</ul>
							</div>
							<div class="col-6 col-sm-3">
								<ul class="list-main">
    						    <?endif?>
    								<li class="list-main__item"><a href="<?='/frontend/web/product/index?category='.$cat->cat_id;?>"><?=$cat->cat_title?></a></li>
    							<?php endforeach; ?>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-10 col-md-6">
						<h3>Изготовление мебели</h3>
						<div class="row">
							<div class="col-6">
								<ul class="list-main">
								    	<li class="drop-menu__item"><a href="https://doors.ural-dekor.ru" class="drop-menu__link">Дверей-купе под заказ</a></li>
									<li class="list-main__item"><a href="/frontend/web/site/finifur">Мебель для кухни</a></li>
									<li class="list-main__item"><a href="/frontend/web/site/customfurn">Мебель под заказ</a></li>
									<li class="list-main__item"><a href="/frontend/web/site/services">Коммерческий распил</a></li>
								</ul>
							</div>
							<div class="col-6">
								<ul class="list-main">

								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-10 col-md-6 col-lg-4 d-md-none">
				<h3>Бытовая техника</h3>
				<div class="row">
					<div class="col-6">
						<ul class="list-main">
							<li class="list-main__item">Кухонные вытяжки</li>
							<li class="list-main__item">Варочные поверхности</li>
							<li class="list-main__item">Духовые шкафы</li>
							<li class="list-main__item">Микроволновые печи</li>
							<li class="list-main__item">Посудомоечные машины</li>
					</div>
				</div>
			</div>
		</div>
	</div>