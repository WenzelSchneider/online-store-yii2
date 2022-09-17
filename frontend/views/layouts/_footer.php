<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>
	
    <!-- FOOTER -->
    <footer>
      <div class="flex fw-wrap fj-between fa-start">
        <div class="footer-box">
          <img
            class="logo"
            src="/frontend/web/media/mo-logo.svg"
            alt="Логотип Урал Декор"
          />
          <p>
            Компания "Урал Декор" это розничные магазины и крупнейший склад
            плитных материалов и мебельной фурнитуры в Оренбурге. Мы оказываем
            полный комплекс услуг по производству корпусной мебели на заказ по индивидуальным размерам.
          </p>
          <div class="mo_social">
            <ion-icon name="logo-instagram"></ion-icon>
            <ion-icon name="logo-vk"></ion-icon>
            <ion-icon name="logo-youtube"></ion-icon>
          </div>
        </div>
        <div
          class="footer-box inner-wrap flex fw-wrap fa-start fj-between"
        >
          <div class="footer-menu-col">
            <h5>Навигация</h5>
            <ul>
              <li><a href="/">Главная</a></li>
              <li><a href="/frontend/web/category/index">Каталог</a></li>
              <li><a href="/frontend/web/site/customfurn">Мебель</a></li>
              <li><a href="/frontend/web/site/services">Услуги</a></li>
              <li><a href="/frontend/web/site/news">Блог</a></li>
              <li><a href="/frontend/web/site/contacts">Контакты</a></li>
            </ul>
          </div>
          <div class="footer-menu-col">
            <h5>Доп. ссылки</h5>
            <ul>
              <li><a href="/">Оптовым покупателям</a></li>
              <li><a href="/">Сотрудничество</a></li>
              <li><a href="/frontend/web/site/delivery">Доставка</a></li>
              <li><a href="/frontend/web/site/job">Работа в Урал Декор</a></li>
              <li><a href="/">Акции и скидки</a></li>
              <li><a href="/">Юридическая информация</a></li>
            </ul>
          </div>
          <div class="footer-menu-col">
            <h5>Личный кабинет</h5>
            <ul>
              <li><a style="cursor:pointer" class="open-modal" <?=(!empty($_COOCKIE['usere']))?'href="/frontend/web/user/index"':'data-modal="#login"';?>><?=(!empty($_COOCKIE['usere']))?$_SESSION['name']:'Войти в личный кабинет';?></a></li>
              <li><a style="cursor:pointer" class="open-modal" <?=(!empty($_COOCKIE['usere']))?'href="/frontend/web/user/index"':'data-modal="#login"';?>>Избранные товары</a></li>
              <li><a style="cursor:pointer" class="open-modal" <?=(!empty($_COOCKIE['usere']))?'href="/frontend/web/user/index"':'data-modal="#login"';?>>Мои заказы</a></li>
              <li><a style="cursor:pointer" href="/">Помощь</a></li>
              <li><a style="cursor:pointer" class="open-modal" <?=(!empty($_COOCKIE['usere']))?'href="/frontend/web/user/index"':'data-modal="#changepass"';?>>Забыли пароль?</a></li>
            </ul>
          </div>
        </div>
        <div class="footer-box">
         <!-- <a href="/frontend/web/site/new?id=26">
          <img src="/frontend/web/media/sale1.jpg" alt="Акция" />
          </a> -->
          <a href="/frontend/web/category/index?category=36">
          <img  style="margin-top:20px" src="/frontend/web/media/sale2.png" alt="Акция" />
          </a>
        </div>
      </div>
      <div class="down-footer flex fw-wrap fj-between">
        <div class="footer-box">
          <a href="#"
            >Информация для потребителей о продукции</a
          >
        </div>
        <div class="footer-box flex fj-center">
          <a href="#">Политика конфиденциальности</a>
          <a href="#">Обработка персональных данных</a>
        </div>
        <div class="footer-box flex fj-flexend">
          <a href="#">Все права сохранены ТД "УРАЛ ДЕКОР" 2021</a>
        </div>
      </div>
    </footer>

