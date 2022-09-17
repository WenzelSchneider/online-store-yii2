<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>
<!--header-->
<div class="container-fluid nav" style="background-color:#edebe9;">
    <div class="row">
    	<div class="container">
    		<div class="row position-relative">
    			<div class="col-12 header">
    				<a href="/frontend/web/" class="header__logo-block"><img src="/frontend/web/img/_src/logo/logo-new.svg" alt="Урал Декор"
    						class="header__logo"></a>
    				<div class="top-menu d-none d-lg-flex">
    					<ul class="top-menu__list">
    						<li class="top-menu__item"><a href="/frontend/web/category/index">Каталог</a></li>
    						<li class="top-menu__item"><a href="/frontend/web/site/aboutus">О компании</a></li>
    						<li class="top-menu__item"><a href="/frontend/web/site/contacts">Контакты</a></li>
    						<li class="top-menu__item"><a href="/frontend/web/site/news">Блог</a></li>
    						<li class="top-menu__item"><a href="/frontend/web/site/projects">Проекты</a></li>
    						<li class="top-menu__item"><a href="/frontend/web/site/delivery">Доставка</a></li>
    					</ul>
    				</div>
    				<div class="dbl-contact">
    					<div class="d-lg-none">
    						<p class="dbl-contact__top">+7 (3532) 306-333</p>
    						<p class="dbl-contact__bot">Пн-Вс | с 10:00 до 17:00</p>
    					</div>
    					<div class="d-none d-lg-block">
    						<p class="dbl-contact__out">г. Оренбург, ул.Туркестанская, 68 <span class="dbl-contact__in"> +7
    								(3532) 306-333</span></p>
    						<p class="dbl-contact__out">г. Оренбург, ул.Юркина, 9а <span class="dbl-contact__in"> +7 (3532)
    								307-333</span></p>
    					</div>
    				</div>
    				<div class="icon-top">
    					<div class="icon-top__group">
    						<a class="icon-top__item open-modal" data-modal="#<?=(!empty($_COOCKIE['usere']))?'accmenu':'login';?>">
    							<i class="icon-login"></i>
    						</a>
    						<a class="icon-top__item open-modal" data-modal="#searchmod" >
    							<i class="icon-search"></i>
    						</a>
    						<a class="icon-top__item icon-top__bag" href="/frontend/web/basket/basket">
    						    <span class="icon-top__bag_count active"><?= count($_SESSION['basket']['products'])?></span>
    							<i class="icon-bag"></i>
    						</a>
    					</div>
    					<div class="icon-top__bars  d-lg-none" onclick='$( ".drop-menu" ).toggleClass( "_active" )'>
    						<a class="icon-top__item" href="#">
    							<i class="icon-burger"></i>
    						</a>
    					</div>
    				</div>
    				<div class="drop-menu d-lg-none" id="t-menu">
    					<ul class="drop-menu__list accordeon">
    						<li class="drop-menu__item"><a href="/frontend/web/category/index" class="drop-menu__link drop">Каталог</a>
    						    <ul>
            						<li class="drop-menu__item_main"><a href="/frontend/web/category/index?category=36" class="drop-menu__link">Мебельная фурнитура</a></li>
            						<li class="drop-menu__item_main"><a href="/frontend/web/category/index?category=35" class="drop-menu__link">Плитные материалы</a></li>
            						<li class="drop-menu__item_main"><a href="/frontend/web/category/index" class="drop-menu__link">Бытовая техника</a></li>
    						    </ul>
    						</li>
            						<li class="drop-menu__item"><a href="/frontend/web/site/finifur" class="drop-menu__link">Готовая Мебель</a></li>
            						<li class="drop-menu__item"><a href="/frontend/web/site/services" class="drop-menu__link">Услуги производства</a></li>
            						<li class="drop-menu__item"><a href="https://doors.ural-dekor.ru" class="drop-menu__link">Сборка дверей-купе под заказ</a></li>
        
    						<li class="drop-menu__item"><a href="/frontend/web/site/aboutus" class="drop-menu__link">О компании</a></li>
    						<li class="drop-menu__item"><a data-modal="#callback" class="drop-menu__link open-modal">Помощь</a></li>
    						<li class="drop-menu__item"><a href="/frontend/web/site/contacts" class="drop-menu__link">Контакты</a></li>
    						<li class="drop-menu__search">
    							<input type="text" class="drop-menu__search-input" placeholder="Поиск по сайту" id="searchlinemob">
    							<a href="#" class="drop-menu__search-btn"  onclick="searchGood(2)">Поиск</a>
    						</li>
    					</ul>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
</div>
