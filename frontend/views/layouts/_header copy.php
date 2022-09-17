<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>
    <!-- HEADER -->
    <header class="mo__header">
      <div class="mo__top-header mo__flex mo__fj-between mo__fa-center">
        <div class="mo__top-header-contacts mo__flex mo__fa-center">
          <a class="mo__flex mo__fa-center" href="/frontend/web/site/contacts"
            ><ion-icon name="location-outline"></ion-icon>г.Оренбург, ул.Юркина,
            9а </a
          ><a class="mo__flex mo__fa-center" href="tel:83532307333"
            ><ion-icon name="call-outline"></ion-icon>+7 (3532) 307-333</a
          >
        </div>
        <div>
          <ul class="mo__top-header-menu mo__flex mo__fj-flexend">
            <li><a href="/">Дли дизайнеров</a></li>
            <li><a href="/">Для производителей мебели</a></li>
            <li><a href="/">Помощь</a></li>
            <li><a href="/frontend/web/site/delivery">Доставка</a></li>
            <li><a class="open-modal" <?=(!empty($_SESSION['usere']))?'href="/frontend/web/user/index"':'data-modal="#login"';?>>Личный кабинет</a></li>
          </ul>
        </div>
      </div>
      <div class="mo__bot-header">
        <div class="mo__box-100 mo__flex mo__fj-between mo__fa-center">
          <img
            class="mo__logo"
            src="/frontend/web/media/mo-logo.svg"
            alt="Логотип Урал Декор"
          />

          <nav class="mo__main-menu">
            <ul class="mo__flex">
              <li class="mo__ac">
                <a
                  >Каталог <ion-icon name="caret-down-outline"></ion-icon
                ></a>
                <ul class="mo__sub-menu mo__flex mo__fd-row">
                  <div class="mo__box-20">
                    <div class="mo__p-25">
                      <h6>Крепежная и соедининения</h6>
                      <li><a href="/frontend/web/product/index?category=20">Уголки</a></li>
                      <li><a href="/frontend/web/product/index?category=25">Саморезы</a></li>
                      <li><a href="/frontend/web/product/index?category=25">Винты</a></li>
                      <li><a href="/frontend/web/product/index?category=26">Эксцентрики</a></li>
                      <li><a href="/frontend/web/product/index?category=26">Стяжки</a></li>
                      <li><a href="/frontend/web/product/index?category=21">Полкодержатели</a></li>
                      <li><a href="/frontend/web/product/index?category=21">Подвески</a></li>
                      <li><a href="/frontend/web/product/index?category=16">Опоры</a></li>
                    </div>
                    <div class="mo__p-25">
                      <h6>Системы открывания фасадов</h6>
                      <li><a href="/frontend/web/product/index?category=15">Петли</a></li>
                      <li><a href="/frontend/web/product/index?category=24">Замки</a></li>
                      <li><a href="/frontend/web/product/index?category=24">Магниты</a></li>
                      <li><a href="/frontend/web/product/index?category=24">Демпферы</a></li>
                      <li><a href="/frontend/web/product/index?category=17">Механизмы</a></li>
                    </div>
                  </div>
                  <div class="mo__box-20">
                    <div class="mo__p-25">
                      <h6>Системы выдвижения</h6>
                      <li><a href="/frontend/web/product/index?category=11">Направляющие</a></li>
                      <li><a href="/frontend/web/product/index?category=11">Метабоксы</a></li>
                      <li><a href="/frontend/web/product/index?category=11">Аксессуары</a></li>
                    </div>
                    <div class="mo__p-25">
                      <h6>Лицевая фурнитура</h6>
                      <li><a href="/frontend/web/product/index?category=13">Ручки</a></li>
                      <li><a href="/frontend/web/product/index?category=14">Крючки мебельные</a></li>
                      <li><a href="/frontend/web/product/index?category=29">Мебельная косметика</a></li>
                    </div>
                    <div class="mo__p-25">
                      <h6>Освещение, блоки питания</h6>
                      <li><a href="/frontend/web/product/index?category=0">Мебельные светильники</a></li>
                      <li><a href="/frontend/web/product/index?category=0">Блоки питания</a></li>
                    </div>
                    <div class="mo__p-25">
                      <h6>Кромочный материал</h6>
                      <li><a href="/frontend/web/product/index?category=7">Кромка</a></li>
                      <li><a href="/frontend/web/product/index?category=7">Кромки для столешниц</a></li>
                    </div>
                  </div>
                  <div class="mo__box-20">
                    <div class="mo__p-25">
                      <h6>Аксессуары для кухонь</h6>
                      <li><a href="/frontend/web/product/index?category=41">Барная стойка</a></li>
                      <li><a href="/frontend/web/product/index?category=47">Рейлинговая система</a></li>
                      <li><a href="/frontend/web/product/index?category=45">Лоток для приборов и поддоны</a></li>
                      <li><a href="/frontend/web/product/index?category=48">Сушки для посуды</a></li>
                      <li><a href="/frontend/web/product/index?category=49">Цоколь кухонный</a></li>
                      <li><a href="/frontend/web/product/index?category=43">Кухонный плинтус</a></li>
                      <li><a href="/frontend/web/product/index?category=43">Планки</a></li>
                      <li><a href="/frontend/web/product/index?category=46">Мусорные системы</a></li>
                      <li><a href="/frontend/web/product/index?category=42">Корзины выдвижные</a></li>
                      <li><a href="/frontend/web/product/index?category=44">Мойки, смесители</a></li>
                    </div>
                    <div class="mo__p-25">
                      <h6>Все для сборки мебели</h6>
                      <li><a href="/frontend/web/product/index?category=27">Инструменты</a></li>
                      <li><a href="/frontend/web/product/index?category=27">Шаблоны</a></li>
                      <li><a href="/frontend/web/product/index?category=18">Клей, герметики, клеящая лента</a></li>
                      <li><a href="/frontend/web/product/index?category=28">Заглушки</a></li>
                    </div>
                  </div>
                  <div class="mo__box-20">
                    <div class="mo__p-25">
                      <h6>Комплектующие для шкафов</h6>
                      <li><a href="/frontend/web/product/index?category=12">Джокерная система</a></li>
                      <li><a href="/frontend/web/product/index?category=31">Наполнение для шкафов</a></li>
                      <li><a href="/frontend/web/product/index?category=50">Сетчатые изделия</a></li>
                      <li><a href="/frontend/web/product/index?category=37">Система Cadro</a></li>
                      <li><a href="/frontend/web/product/index?category=0">Профиль Аристо</a></li>
                      <li><a href="/frontend/web/product/index?category=0">Профиль Фурнитекс</a></li>
                      <li><a href="/frontend/web/product/index?category=0">Система SKM</a></li>
                    </div>
                    <div class="mo__p-25">
                      <h6>Зеркало и комплектующие</h6>
                      <li><a href="/frontend/web/product/index?category=22">Зеркало</a></li>
                      <li><a href="/frontend/web/product/index?category=22">Стекло</a></li>
                      <li><a href="/frontend/web/product/index?category=0">Лато-держатели</a></li>
                      <li><a href="/frontend/web/product/index?category=22">Зеркало и кожа</a></li>
                    </div>
                  </div>
                  <div class="mo__box-20">
                    <div class="mo__p-25">
                      <h6>Плитные материалы</h6>
                      <li><a href="/frontend/web/product/index?category=1">ЛДСП</a></li>
                      <li><a href="/frontend/web/product/index?category=2">Столешницы</a></li>
                      <li><a href="/frontend/web/product/index?category=2">Cтеновые панели</a></li>
                      <li><a href="/frontend/web/product/index?category=3">ДВП и ХДФ</a></li>
                      <li><a href="/frontend/web/product/index?category=4">ЛМДФ и МДФ</a></li>
                      <li><a href="/frontend/web/product/index?category=5">ДСП</a></li>
                      <li><a href="/frontend/web/product/index?category=6">Фанера</a></li>
                      <li><a href="/frontend/web/product/index?category=38">АГТ-панели</a></li>
                    </div>
                  </div>
                </ul>
              </li>
              <li class="mo__ac">
                <a
                  >Мебель <ion-icon name="caret-down-outline"></ion-icon
                ></a>
                <ul class="mo__sub-menu mo__flex">
                  <div class="mo__box-33">
                    <div class="mo__p-25">
                      <h6>Готовая мебель</h6>
                      <li><a href="/frontend/web/product/index?category=51">Столы</a></li>
                      <li><a href="/frontend/web/product/index?category=51">Стулья</a></li>
                      <li><a href="/frontend/web/product/index?category=51">Кухни</a></li>
                      <li><a href="/frontend/web/product/index?category=51">Диваны</a></li>
                      <li><a href="/frontend/web/product/index?category=51">Шкафы</a></li>
                    </div>
                  </div>
                  <div class="mo__box-33">
                    <div class="mo__p-25">
                      <h6>Мебель на заказ</h6>
                      <li><a href="/frontend/web/site/finifur">Кухни на заказ</a></li>
                      <li><a href="/frontend/web/site/customfurn">Мебель для детской</a></li>
                      <li><a href="/frontend/web/site/customfurn">Мебель в ванную</a></li>
                      <li><a href="/frontend/web/site/customfurn">Мебель в спальню</a></li>
                      <li><a href="/frontend/web/site/customfurn">Мебель в прихожую</a></li>
                      <li><a href="/frontend/web/site/customfurn">Мебель для гостиной</a></li>
                    </div>
                  </div>
                  <div class="mo__box-33 mo__img-menu-mebel"></div>
                </ul>
              </li>
              <li class="mo__ac">
                <a
                  >Услуги <ion-icon name="caret-down-outline"></ion-icon
                ></a>
                <ul class="mo__sub-menu mo__flex">
                  <div class="mo__box-25">
                    <div class="mo__p-25">
                      <h6>Изготовление мебели</h6>
                      <li><a href="/frontend/web/site/services">Проект помещения</a></li>
                      <li><a href="/frontend/web/site/services">Проектирование мебели</a></li>
                      <li><a href="/frontend/web/site/services">Дизайн-проект</a></li>
                      <li><a href="/frontend/web/site/services">Модульные кухни</a></li>
                      <li><a href="/frontend/web/site/services">Мебель по индивидуальным размерам</a></li>
                    </div>
                  </div>
                  <div class="mo__box-25">
                    <div class="mo__p-25">
                      <h6>Изготовление дверей купе</h6>
                      <li>
                        <a href="/frontend/web/site/services">Двери-купе по индивидуальным размерам</a>
                      </li>
                      <li><a href="/frontend/web/site/services">Каталог сборки дверей-купе</a></li>
                    </div>
                    <div class="mo__p-25">
                      <h6>Изготовление фасадов</h6>
                      <li><a href="/frontend/web/site/services">Фасады под заказ</a></li>
                      <li><a href="/frontend/web/site/services">Замена фасадов кухни</a></li>
                    </div>
                  </div>
                  <div class="mo__box-25">
                    <div class="mo__p-25">
                      <h6>Кромление и распил плит</h6>
                      <li><a href="/frontend/web/site/services">Услуги распила</a></li>
                      <li><a href="/frontend/web/site/services">Услуги кромления</a></li>
                      <li><a href="/frontend/web/site/services">Плиты под заказ</a></li>
                    </div>
                  </div>
                  <div class="mo__box-25 mo__img-menu-serv"></div>
                </ul>
              </li>
              <li><a href="/frontend/web/site/news">Блог</a></li>
              <li><a href="/frontend/web/site/contacts">Контакты</a></li>
            </ul>
          </nav>

          <div class="mo__side-icons mo__flex mo__fj-flexend mo__fa-center">
            <a class="mo__flex mo__fa-center open-modal" <?=(!empty($_SESSION['usere']))?'href="/frontend/web/user/index"':'data-modal="#login"';?>>
              <?=(!empty($_SESSION['usere']))?$_SESSION['clientdat']['name']:'Войти';?><img
                src="/frontend/web/media/icons/person-outline.svg"
                alt="личный кабинет"
            /></a>
            <a  class="open-modal" data-modal="#searchmod"
              ><img src="/frontend/web/media/icons/search-outline.svg" alt="поиск"
            /></a>
            <a href="/frontend/web/basket/basket"
              ><img
                src="/frontend/web/media/icons/bag-outline.svg"
                alt="корзина товаров"
            /></a>
            <div class="mo__burger mo__flex mo__fa-center">
              <svg
                class="menu-icon ham hamRotate ham4"
                viewBox="0 0 100 100"
                width="45"
                onclick="this.classList.toggle('active')"
              >
                <path
                  class="line top"
                  d="m 70,33 h -40 c 0,0 -8.5,-0.149796 -8.5,8.5 0,8.649796 8.5,8.5 8.5,8.5 h 20 v -20"
                ></path>
                <path class="line middle" d="m 70,50 h -40"></path>
                <path
                  class="line bottom"
                  d="m 30,67 h 40 c 0,0 8.5,0.149796 8.5,-8.5 0,-8.649796 -8.5,-8.5 -8.5,-8.5 h -20 v 20"
                ></path>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </header>
    <!-- HERO TITLE ALL PAGES EXCLUDE DYNAMIC -->
    <section class="mo__hero">
      <div class="mo__img-bg"></div>
      <div class="container mo__flex">
        <div class="mo__box-70">
          <h1>Сборка дверей-купе</h1>
          <p>
            Наши опытные дизайнеры помогут определиться с размерами и
            материалом, сделаем точные расчеты и предоставим макет к вашему
            будущему проекту.
          </p>
        </div>
      </div>
    </section>