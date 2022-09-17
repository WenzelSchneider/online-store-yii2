<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>
	<!--footer-->
	<div class="container-fluid footer top-indent-50">
	    <div class="row">
	        <div class="container">
        		<div class="row">
        			<div class="col">
        				<div class="logo-mid logo-footer">
        					<img src="/frontend/web/img/_src/logo/logo-new.svg" alt="Ural-Dekor" class="logo-mid__logo">
        				</div>
        			</div>
        		</div>
        		<div class="row">
        			<div class="col-12 col-lg-3">
        				<div class="row">
        					<div class="col-12 col-sm-6 col-lg-12">
        					    <h3 class="title-main">Контакты</h3>
        						<ul class="list-main">
        							<li class="list-main__item">г.Оренбург ул.Юркина 9а</li>
        							<li class="list-main__item text-bold">+7 (3532) 307-333</li>
        							<li class="list-main__item">Пн - Пт: 9:00 - 17:00 Сб: 10:00 - 16:00 Вс - вых.</li>
        						</ul>
        					</div>
        					<div class="col-12 col-sm-6 col-lg-12">
        						<ul class="list-main">
        							<li class="list-main__item">г.Оренбург ул.Туркестанская, 68</li>
        							<li class="list-main__item text-bold">+7 (3532) 306-333</li>
        							<li class="list-main__item">Пн - Пт: 10:00 - 19:00, Сб - Вс: 10:00 - 17:00</li>
        						</ul>
        					</div>
        				</div>
        			</div>
        			<div class="col-12 col-lg-9">
        				<div class="row mt-1">
        					<div class="col-4 col-sm-3 col-lg-2">
        						<h3>Урал Декор</h3>
        						<ul class="list-main">
        							<li class="li list-main__item"><a href="/frontend/web/category/index">Каталог</a></li>
        							<li class="li list-main__item"><a href="/frontend/web/site/aboutus">О компании</a></li>
        							<li class="li list-main__item"><a href="/frontend/web/site/contacts">Контакты</a></li>
        							<li class="li list-main__item"><a href="/frontend/web/site/news">Блог</a></li>
        							<li class="li list-main__item"><a href="/frontend/web/site/delivery">Доставка</a></li>
        						</ul>
        					</div>
        					<div class="col-4 col-sm-3 col-lg-2">
        						<h3>Помощь</h3>
        						<ul class="list-main">
        							<li class="li list-main__item">Процесс оплаты</li>
        							<li class="li list-main__item">Возврат</li>
        							<li class="li list-main__item">Оптовикам</li>
        							<li class="li list-main__item">Реквизиты</li>
        						</ul>
        					</div>
        					<div class="col-4 col-sm-3 col-lg-2">
        						<h3>Соцсети</h3>
        						<ul class="list-main">
        							<li class="li list-main__item"><a href="https://www.instagram.com/uraldekor/">Инстаграм</a></li>
        							<li class="li list-main__item"><a href="https://ok.ru/uraldekor">Одноклассники</a></li>
        							<li class="li list-main__item"><a href="https://www.youtube.com/channel/UCp_c94rXYAgkbictPpXJkIw">Ютуб</a></li>
        							<li class="li list-main__item"><a href="https://vk.com/ural_dekor">Вконтакте</a></li>
        						</ul>
        					</div>
        					<div class="col-12 col-lg-6">
        						<h3>Подписка</h3>
        						<div class="text">Чтобы быть в курсе всех акций и новинок, подпишитесь на нашу рассылку</div>
        						<form class="subscribe" action="/frontend/web/user/mailing" method="post">
                                    <input class="subscribe__input" type="text" name="email" placeholder="Ваш E-mail" required>
                        			<?=
                            			Html::hiddenInput(
                            				Yii::$app->request->csrfParam,
                            				Yii::$app->request->csrfToken
                            			);
                        			?>
        							<a data-modal="#subscribe" class="subscribe__btn open-modal d-none">Подписаться</a>
        							<button type="submit" class="subscribe__btn">Подписаться</button>
        						</form>
        					</div>
        				</div>
        				<div class="row mt-1">
        				    <div class="col-6">
        						<p class="text-center">Мы принимаем к оплате:</p>
        						<div class="text-center">
        						    <img src="/frontend/web/img/_src/paysys.png" alt="Visa MasterCard Мир" class="w-50 text-center">
        						</div>
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
        						</div></div>
        				</div>
        			</div>
        		</div>
        		<div class="row mt-1 mb-1">
        			<div class="col-12">
        				<p class="text _text-semibold">© 2016-2020 Урал Декор</p>
        			</div>
        		</div>
        	</div>
    	</div>
    </div>