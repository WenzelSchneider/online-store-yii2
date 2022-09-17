<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\ContentPages;

?>
    <!-- <pre><?php print_r($_COOKIE);?></pre> -->
    <!-- <pre><?php print_r($_SESSION);?></pre> -->
<div class='modal' id='login'>
    <i class="close_all icon-cross close-modal" data-modal="#login"></i>
    <form action="/frontend/web/site/logine" method="post" class='content modal-form'>
        <span class="modal-title">Вход</span>
        <p>
            <input type="text" name="username" placeholder="Ваш телефон или Email" required>
            <input type="password" name="password" placeholder="Ваш пароль" required>
			<?=
    			Html::hiddenInput(
    				Yii::$app->request->csrfParam,
    				Yii::$app->request->csrfToken
    			);
			?>
        </p>
        <button type="submit" class='btn -black mb-1' data-modal="#login">Войти</button>
        <a class="just-link mt-1 open-modal" data-modal="#registration">Зарегистрироваться</a>
        <a class="just-link mt-1 open-modal" data-modal="#changepass">Сбросить пароль</a>
    </form>  
</div>  
<div class='modal' id='changepass'>
    <i class="close_all icon-cross close-modal" data-modal="#changepass"></i>
    <form action="/frontend/web/user/resetpass" method="post" class='content modal-form'>
        <span class="modal-title">Сброс пароля</span>
        <p>
            <input type="text" name="email" placeholder="Ваш E-mail" required>
			<?=
    			Html::hiddenInput(
    				Yii::$app->request->csrfParam,
    				Yii::$app->request->csrfToken
    			);
			?>
        </p>
        <button type="submit" class='btn -black' data-modal="#changepass">Сбросить</button>
        <a class="just-link mt-1 open-modal d-none" data-modal="#registration">Зарегистрироваться</a>
    </form>  
</div>  
<div class='modal' id='subscribe'>
    <i class="close_all icon-cross close-modal" data-modal="#subscribe"></i>
    <form action="/frontend/web/user/mailing" method="post" class='content modal-form'>
        <span class="modal-title">Подписаться</span>
        <p>
            <input type="text" name="email" placeholder="Ваш E-mail" required>
			<?=
    			Html::hiddenInput(
    				Yii::$app->request->csrfParam,
    				Yii::$app->request->csrfToken
    			);
			?>
        </p>
        <button type="submit" class='btn -black' data-modal="#changepass">Подписаться</button>
    </form>  
</div>  
<div class='modal' id='callback'>
    <i class="close_all icon-cross close-modal" data-modal="#callback"></i>
    <form action="/frontend/web/site/callback" method="post" class='content modal-form'>
        <span class="modal-title">Обратный звонок</span>
        <p>
                    <input type="text" name="name" placeholder="Ваше имя" required="">
                    <input type="text" name="phone" placeholder="Ваш номер телефона" required>
                    <label class="mt-1 mb-1"><input type="checkbox" name="coose" id="moose" required> Нажимая на кнопку "Отправить", я соглашаюсь с условиями <a class="just-link mt-1 open-modal" data-modal="#ps">Пользовательского соглашения</a></label>
			<?=
    			Html::hiddenInput(
    				Yii::$app->request->csrfParam,
    				Yii::$app->request->csrfToken
    			);
			?>
        </p>
        <button type="submit" class='btn -black' data-modal="#changepass">Заказать обратный звонок</button>
    </form>  
</div>  
<div class='modal' id='accmenu'>
    <i class="close_all icon-cross close-modal" data-modal="#accmenu"></i>
    <div class='content'>
        <span class="modal-title">Личный кабинет</span>
        <a href="/frontend/web/user/index" class="btn -outline" data-modal="#accmenu">Перейти в личный кабинет</a>
         <a href="/frontend/web/site/logaut" class="btn -black" data-modal="#accmenu">Выйти из аккаунта</a>
    </div>  
</div>  
<div class='modal' id='enduseragreement'>
    <i class="close_all icon-cross close-modal" data-modal="#enduseragreement"></i>
    <div class='content'>
        <span class="modal-title">Пользовательское соглашение</span>
        <p>
                <?php
                    $text = ContentPages::find()
                    ->where(['page_id'=>2])
                    ->one();
                ?>
                <?= htmlspecialchars_decode($text->page_html);?>
        </p>
    </div>  
</div>  
<div class='modal' id='dropbag'>
    <i class="close_all icon-cross close-modal" data-modal="#dropbag"></i>
    <div class='content'>
        <span class="modal-title">Аккаунт</span>
        <p>
            Будут удалены все товары из корзины
        </p>
            <a href="<?= Url::to(['basket/clear']);?>"class="btn -black mt-1" data-modal="#accmenu">Очистить</a>
    </div>  
</div> 

<!-- Модальное окно РЕГИСТРАЦИИ -->
<div class="modal" id="registration">
    <i class="close_all icon-cross close-modal" data-modal="#registration"></i>
    <div class="content">
        <span class="modal-title">Регистрация</span>
        <div class="modalreg">
                <div class="tabs_reg">
                    <input type="radio" name="tab-btn" id="tab-btn-1" value="" checked>
                    <label for="tab-btn-1" class="contact__label order-form__label-radio">
                        <div class="contact__content">
                            <div class="one"><span class="h4 m-0">Физическое лицо</span></div>
                        </div>
                    </label>
                    <input type="radio" name="tab-btn" id="tab-btn-2" value="">
                    <label for="tab-btn-2" class="contact__label order-form__label-radio">
                        <div class="contact__content">
                            <div class="one"><span class="h4 m-0">Юридическое лицо</span></div>
                        </div>
                    </label>
                        <div id="content-1">
                            <form action="/frontend/web/user/reg" method="post" class="order-form" id="confiz">
                                <input type="text" placeholder="Ваше имя и фамилия*" name="contactname" required>
                                <input type="text" placeholder="Контактный номер телефона*" name="telephone" required>
                                <input type="text" placeholder="Электронная почта*" name="email" required>
                                <input type="text" name="type" value="fiz" class="d-none" required>
                                <input type="text" placeholder="Пароль" name="password" onchange="sendAjaxForm('confiz', '/frontend/web/order/contact-information-private')" required>
                        <?=
                            Html::hiddenInput(
                                Yii::$app->request->csrfParam,
                                Yii::$app->request->csrfToken
                            );
                        ?>		
                                <input type="submit" class="btn -black" value="Зарегистрироваться">
                            </form>
                        </div>
                        <div id="content-2">
                            <form action="/frontend/web/user/reg" method="post" class="order-form" id="conjur" required>
                                <div class="w-50">
                                    <input class="w-100" type="text" placeholder="Название Вашей организации*" name="companyname" required>
                                    <input class="w-100" type="text" placeholder="ИНН Вашей организации*" name="inn" required>
                                    <input class="w-100" type="text" placeholder="Ваше имя и фамилия*" name="contactname" required>
                                </div>
                                <div class="w-50">
                                    <input class="w-100" type="text" placeholder="Контактный номер телефона*" name="telephone" required>
                                    <input class="w-100" type="text" placeholder="Электронная почта*" name="email" required>
                                    <input type="text" name="type" value="jur" class="d-none" required>
                                    <input class="w-100" type="text" placeholder="Пароль" name="password" onchange="sendAjaxForm('conjur', '/frontend/web/order/contact-information-legal')" required>
                                </div>
                        <?=
                            Html::hiddenInput(
                                Yii::$app->request->csrfParam,
                                Yii::$app->request->csrfToken
                            );
                        ?> 			
                                <input type="submit" class="btn -black" value="Зарегистрироваться">
                            </form>
                        </div>
                    </div>
		</div>
		
    </div>
</div>

<!-- Модальное окно РЕДАКТИРОВАНИЯ ПРОФИЛЯ -->
<div class="modal" id="edition">
    <i class="close_all icon-cross close-modal" data-modal="#edition"></i>
    <div class="content">
        <span class="modal-title">Редактирование профиля</span>
        <div class="modaledit">
			<form action="#" class="order-form order-contact" id="lkcontact">
				<label class="contact__label order-form__label-radio">
					<div class="contact__radio"><input type="radio" name="contact" id="lktrifiz" <?=($_SESSION['clientdat']['type'] == 'jur')? 'disabled' : 'checked';?>></div>
					<div class="contact__content">
						<div class="one"><span class="h4 m-0">Физическое лицо</span></div>
					</div>
				</label>
				<label class="contact__label order-form__label-radio">
					<div class="contact__radio"><input type="radio" name="contact" id="lktrijur"  <?=($_SESSION['clientdat']['type'] == 'fiz')? 'disabled' : 'checked';?>></div>
					<div class="contact__content">
						<div class="one"><span class="h4 m-0">Юридическое лицо</span></div>
					</div>
				</label>
			</form>
			<?php if($_SESSION['clientdat']['type'] == 'fiz' || empty($_SESSION['clientdat']['type'])):?>
    			<form action="/frontend/web/user/reg" method="POST" class="order-form" id="lkconfiz" <?=($_SESSION['clientdat'] == 'jur')? ' style="display: none;" ' : '';?>>
    				<input type="text" placeholder="Ваше имя и фамилия*" name="contactname" <?=(!empty($_SESSION['clientdat']))? 'value="'.$_SESSION["clientdat"]["name"].'"' : '';?>>
    				<input type="text" placeholder="Контактный номер телефона*" name="telephone" <?=(!empty($_SESSION['clientdat']))? 'value="'.$_SESSION["clientdat"]["numberphone"].'"' : '';?>>
    				<input type="text" placeholder="Электронная почта*" name="email" <?=(!empty($_SESSION['clientdat']))? 'value="'.$_SESSION["clientdat"]["email"].'"' : '';?>>
    				<input type="text" name="type" value="fiz" class="d-none">
    				<?php if(!empty($_SESSION['clientdat']['type'])):?>
                                            
                    <?php else:?>
    				    <input type="text" placeholder="Пароль" name="password" onchange="sendAjaxForm('oconfiz', '/frontend/web/order/contact-information-private')">
    				<?php endif;?>
    				<?=
    					Html::hiddenInput(
    						Yii::$app->request->csrfParam,
    						Yii::$app->request->csrfToken
    					);
    				?>
    				<button class="btn -black" type="submit">Изменить</button>
    			</form>
    		<?php endif;?>
    		<?php if($_SESSION['clientdat']['type'] == 'jur' || empty($_SESSION['clientdat']['type'])):?>
    			<form action="/frontend/web/user/reg" method="POST" class="order-form" id="lkconjur"  <?=($_SESSION['clientdat'] == 'fiz')? ' style="display: none;" ' : '';?>>
    				<input type="text" placeholder="Название Вашей организации*" name="companyname" <?=(!empty($_SESSION['clientdat']))? 'value="'.$_SESSION["clientdat"]["namecompany"].'"' : '';?>>
    				<input type="text" placeholder="ИНН Вашей организации*" name="inn" <?=(!empty($_SESSION['clientdat']))? 'value="'.$_SESSION["clientdat"]["inn"].'"' : '';?>>
    				<input type="text" placeholder="Ваше имя и фамилия*" name="contactname" <?=(!empty($_SESSION['clientdat']))? 'value="'.$_SESSION["clientdat"]["name"].'"' : '';?>>
    				<input type="text" placeholder="Контактный номер телефона*" name="telephone" <?=(!empty($_SESSION['clientdat']))? 'value="'.$_SESSION["clientdat"]["numberphone"].'"' : '';?>>
    				<input type="text" placeholder="Электронная почта*" name="email" <?=(!empty($_SESSION['clientdat']))? 'value="'.$_SESSION["clientdat"]["email"].'"' : '';?>>
    				<input type="text" name="type" value="jur" class="d-none">
    				<?php if(!empty($_SESSION['clientdat']['type'])):?>
                                            
                    <?php else:?>
    				    <input type="text" placeholder="Пароль" name="password" onchange="sendAjaxForm('oconjur', '/frontend/web/order/contact-information-legal')">
    				<?php endif;?>
    				<?=
        				Html::hiddenInput(
        					Yii::$app->request->csrfParam,
        					Yii::$app->request->csrfToken
        				);
    				?>
    				<button class="btn -black" type="submit">Изменить</button>
    			</form>
    		<?php endif;?>
        </div>
    </div>
</div>
<div class='modal' id='mapturk'>
    <i class="close_all icon-cross close-modal" data-modal="#mapturk"></i>
    <div class='content'>
        <span class="modal-title">Туркестанская 68</span>
        <div class="img"><img src="/frontend/web/img/_src/map/map_turk.png" alt=""></div>
    </div>  
</div>  
<div class='modal' id='mapurk'>
<i class="close_all icon-cross close-modal" data-modal="#mapturk"></i>
    <div class='content'>
        <span class="modal-title">Юркина 9а</span>
        <div class="img"><img src="/frontend/web/img/_src/map/map_urk.png" alt=""></div>
    </div>  
</div>  
<div class='modal' id='conditionsdel'>
    <i class="close_all icon-cross close-modal" data-modal="#conditionsdel"></i>
    <div class='content'>
        <span class="modal-title">Условия доставки</span>
        <p>
        Доставка по городу Оренбург осуществляется ежедневно с 09:00 до 18:00. 
        Предварительно с Вами свяжется наш менеджер и обсудит все детали доставки. 
        Доставка товара производится до места при наличии подъездных путей, предназначенных для проезда грузовых автомобилей. 
        Выгрузка товара производиться около машины. Подъем на этаж оплачиваются дополнительно.
        </p>
        <a target="_blank" style="text-decoration:underline" href="/frontend/web/site/delivery">Узнать цены на доставку</a>
    </div>  
</div> 

<div class='modal' id='conditionspay'>
    <i class="close_all icon-cross close-modal" data-modal="#conditionspay"></i>
    <div class='content'>
        <span class="modal-title">Условия оплаты</span>
        <p>
            Наличный расчёт
Если товар доставляется курьером, то оплата осуществляется наличными курьеру в руки. При получении товара обязательно проверьте комплектацию товара, наличие гарантийного талона и чека.

Банковской картой
Для выбора оплаты товара с помощью банковской карты на соответствующей странице необходимо нажать кнопку Оплата заказа банковской картой. Оплата происходит через ПАО СБЕРБАНК с использованием банковских карт следующих платёжных систем:
<ul>
    <li>МИР<img src="/frontend/web/img/_src/pay/mir.png" style="height:20px;"></li>
    <li>VISA International<img src="/frontend/web/img/_src/pay/visa.png" style="height:20px;"></li>
    <li>Mastercard Worldwide<img src="/frontend/web/img/_src/pay/mastercard.png" style="height:20px;"></li>
    <li>JCB<img src="/frontend/web/img/_src/pay/jcb.png" style="height:20px;"></li>
</ul>
Для оплаты (ввода реквизитов Вашей карты) Вы будете перенаправлены на платёжный шлюз ПАО СБЕРБАНК. Соединение с платёжным шлюзом и передача информации осуществляется в защищённом режиме с использованием протокола шифрования SSL. В случае если Ваш банк поддерживает технологию безопасного проведения интернет-платежей Verified By Visa, MasterCard SecureCode, MIR Accept, J-Secure, для проведения платежа также может потребоваться ввод специального пароля.

Настоящий сайт поддерживает 256-битное шифрование. Конфиденциальность сообщаемой персональной информации обеспечивается ПАО СБЕРБАНК. Введённая информация не будет предоставлена третьим лицам за исключением случаев, предусмотренных законодательством РФ. Проведение платежей по банковским картам осуществляется в строгом соответствии с требованиями платёжных систем МИР, Visa Int., MasterCard Europe Sprl, JCB.
        </p>
    </div>  
</div> 
<div class='modal' id='restore'>
    <i class="close_all icon-cross close-modal" data-modal="#restore"></i>
    <div class='content'>
        <span class="modal-title">Условия возврата товара</span>
        <p>
            Срок возврата товара надлежащего качества составляет 30 дней с момента получения товара.Возврат переведённых средств, производится на ваш банковский счёт в течение 5-30 рабочих дней (срок зависит от банка, который выдал вашу банковскую карту).
        </p>
    </div>  
</div> 
<div class='modal' id='ipcontakt'>
    <i class="close_all icon-cross close-modal" data-modal="#ipcontakt"></i>
    <div class='content'>
        <span class="modal-title">Контакты оплаты</span>
        <p>
           Фактический адрес: 460001, Оренбург, пр-т Автоматики, д. 12/4<br>
           Электронная почта: starkov@ssoft.ru<br>
           Телефоны: 8-800-500-9876<br>
           Реквизиты:ИП Старков Артем Владимирович<br>
           ИНН 561100988596 / ОГРНИП 315565800073453<br>
           Юридический адрес: 460001 Оренбург, ул. Новая, д. 12, п.4
        </p>
    </div>  
</div> 
            
<div class='modal' id='returnconditions'>
    <i class="close_all icon-cross close-modal" data-modal="#returnconditions"></i>
    <div class='content'>
        <span class="modal-title">Условия возврата</span>
        <p>
            Срок возврата товара надлежащего качества составляет 30 дней с момента получения товара.
        </p>
        <p>
            Возврат переведённых средств, производится на ваш банковский счёт в течение 5-30 рабочих дней (срок зависит от банка, который выдал вашу банковскую карту).
        </p>
    </div>  
</div> 

<div class='modal' id='ps'>
    <i class="close_all icon-cross close-modal" data-modal="#ps"></i>
    <div class='content'>
        <span class="modal-title">Пользовательское Соглашение</span>
        <p>
              Предоставляя свои персональные данные Пользователь даёт согласие
              на обработку, хранение и использование своих персональных данных
              на основании ФЗ № 152-ФЗ «О персональных данных» от 27.07.2006 г.
              в следующих целях:
              <br />
              - Осуществление клиентской поддержки
              <br />
              - Получения Пользователем информации о маркетинговых событиях
              <br />
              - Проведения аудита и прочих внутренних исследований с целью
              повышения качества предоставляемых услуг.
              <br />
              <br />
              Под персональными данными подразумевается любая информация личного
              характера, позволяющая установить личность Пользователя/Покупателя
              такая как:
              <br />
              - Фамилия, Имя, Отчество
              <br />
              - Дата рождения
              <br />
              - Контактный телефон
              <br />
              - Адрес электронной почты
              <br />
              - Почтовый адрес
              <br />
              <br />
              Персональные данные Пользователей хранятся исключительно на
              электронных носителях и обрабатываются с использованием
              автоматизированных систем, за исключением случаев, когда
              неавтоматизированная обработка персональных данных необходима в
              связи с исполнением требований законодательства.
              <br />
              <br />
              Компания обязуется не передавать полученные персональные данные
              третьим лицам, за исключением следующих случаев:
              <br />
              - По запросам уполномоченных органов государственной власти РФ
              только по основаниям и в порядке, установленным законодательством
              РФ
              <br />
              - Стратегическим партнерам, которые работают с Компанией для
              предоставления продуктов и услуг, или тем из них, которые помогают
              Компании реализовывать продукты и услуги потребителям. Мы
              предоставляем третьим лицам минимальный объем персональных данных,
              необходимый только для оказания требуемой услуги или проведения
              необходимой транзакции.
              <br />
              <br />
              Компания оставляет за собой право вносить изменения в
              одностороннем порядке в настоящие правила, при условии, что
              изменения не противоречат действующему законодательству РФ.
              Изменения условий настоящих правил вступают в силу после их
              публикации на Сайте.
              <br />
            </p>
    </div>  
</div> 

<!-- SEARCH -->

<div class='modal' id='searchmod' style="text-align: left; padding:1em; overflow: hidden;">
    <i class="close_search icon-cross close-modal" data-modal="#searchmod"></i>
    <a href="/frontend/web/" class="search-logo"><img src="/frontend/web/img/_src/logo/logo-black.svg" alt="Урал Декор"></a>
    <div class='content'>
        <div class="container">
            <div class="search-wrapper">
            	<input autofocus
 type="text" placeholder="Введите запрос для поиска" id="searchline" <?=(!empty($_GET['q']))?$_GET['q']:'';?>>
            	
            	<button href="#" class="search-button" onclick="searchGood(1)"><ion-icon name="search-outline"></ion-icon></button>
            </div>
         </div>
    </div>
</div>  




<?php if( Yii::$app->session->hasFlash('loginlog') ): ?>
<div class='modal' id='loginlog'>
    <i class="close_all icon-cross close-modal" data-modal="#loginlog"></i>
    <div class='content'>
            <span>Личный кабинет</span>
            <?php echo Yii::$app->session->getFlash('loginlog'); ?>
            <a href="/frontend/web/user/index" class="btn -outline" data-modal="#loginlog">Перейти в кабинет</a>
            <a href="/frontend/web/site/logaut"class="btn -black" data-modal="#loginlog">Выйти из аккаунта</a>
    </div>  
</div>  
<?php endif;?>
<?php if( Yii::$app->session->hasFlash('success') ): ?>
<div class='modal' id='success'>
    <i class="close_all icon-cross close-modal" data-modal="#success"></i>
    <div class='content'>
        <span class="modal-title">Успех!</span>
        <p>
            <?php echo Yii::$app->session->getFlash('success'); ?>
        </p>
    </div>  
</div>  
<?php endif;?>
<?php if( Yii::$app->session->hasFlash('danger') ): ?>
<div class='modal' id='danger'>
    <i class="close_all icon-cross close-modal" data-modal="#danger"></i>
    <div class='content'>
        <span class="modal-title">Проблема!</span>
        <p>
            <?php echo Yii::$app->session->getFlash('danger'); ?>
        </p>
    </div>  
</div>  
<?php endif;?>
