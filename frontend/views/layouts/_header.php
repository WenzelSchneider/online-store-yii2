<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>
    <!-- HEADER -->
    <header class="2021__header">
     <!-- <a class="header-banner" style="width:100%" href="/frontend/web/site/new?id=26">
      <img  style="width:100%" src="\frontend\web\media\banner_wide.jpg" alt="акция" />
      </a> -->
     <div class="bot-header">
        <div class="flex fj-between fa-center" style="height:100%">
         <a href="/">
          <img
            class="logo"
            src="/frontend/web/media/mo-logo.svg"
            alt="Логотип Урал Декор"
          />
          </a>
          <nav class="main-menu">
            <ul class="flex">
              <li class="mo__ac">
                <a
                  >Каталог <ion-icon name="caret-down-outline"></ion-icon
                ></a>
                <ul class="sub-menu flex fd-row">
                  <div class="box-20">
                    <div class="p-25">
                    <h6>Плитные материалы</h6>
                      <li><a href="/frontend/web/product/index?category=1">ЛДСП</a></li>
                      <li><a href="/frontend/web/product/index?category=2&property%5B1142%5D%5B%5D=10557">Столешницы</a></li>
                      <li><a href="/frontend/web/product/index?category=2&property%5B1142%5D%5B%5D=10555">Cтеновые панели</a></li>
                      <li><a href="/frontend/web/product/index?category=3">ДВП и ХДФ</a></li>
                      <li><a href="/frontend/web/product/index?category=4">ЛМДФ и МДФ</a></li>
                      <li><a href="/frontend/web/product/index?category=5">ДСП</a></li>
                      <li><a href="/frontend/web/product/index?category=6">Фанера</a></li>
                      <li><a href="/frontend/web/product/index?category=38">АГТ-панели</a></li>
                    </div>
                    <div class="p-25">
                    <h6>Кромочный материал</h6>
                      <li><a href="/frontend/web/product/index?catid=151">Кромка</a></li>
                      <li class="d-none"><a href="/frontend/web/product/index?catid=134">Кромки для столешниц</a></li>
                    
                    </div>
                    <div class="p-25">
                    <h6>Системы открывания фасадов</h6>
                      <li><a href="/frontend/web/product/index?category=15">Петли</a></li>
                      <li><a href="/frontend/web/product/index?category=24&property%5B1022%5D%5B%5D=8477">Замки</a></li>
                      <li><a href="/frontend/web/product/index?category=24&property%5B1022%5D%5B%5D=8474">Магниты, демпферы</a></li>
                      <li><a href="/frontend/web/product/index?category=17">Механизмы</a></li>
                    </div>
                  </div>
                  <div class="box-20">
                    <div class="p-25">
                      <h6>Системы выдвижения</h6>
                      <li><a href="/frontend/web/product/index?category=11&property%5B1007%5D%5B%5D=11066">Системы выдвижения с боковинами</a></li>
                      <li><a href="/frontend/web/product/index?category=11&property%5B1007%5D%5B%5D=8273">Направляющие роликовые</a></li>
                      <li><a href="/frontend/web/product/index?category=11&property%5B1007%5D%5B%5D=8275">Направляющие шариковые</a></li>
                      <li><a href="/frontend/web/product/index?category=11&property%5B1007%5D%5B%5D=8276">Направляющие скрытого монтажа</a></li>
                      <li><a href="/frontend/web/product/index?category=11&property%5B1007%5D%5B%5D=8274">Аксессуары</a></li>
                    </div>
                    <div class="p-25">
                      <h6>Лицевая фурнитура</h6>
                      <li><a href="/frontend/web/product/index?category=13">Ручки</a></li>
                      <li><a href="/frontend/web/product/index?category=14">Крючки мебельные</a></li>
                      <li><a href="/frontend/web/product/index?category=16">Опоры</a></li>
                    </div>
                    <div class="p-25">
                      <h6>Освещение, блоки питания</h6>
                      <li><a href="/frontend/web/product/index?category=39&property%5B997%5D%5B%5D=8148">Ленты светодиодные</a></li>
                      <li><a href="/frontend/web/product/index?category=39&property%5B997%5D%5B0%5D=8156&property%5B997%5D%5B1%5D=8157&property%5B997%5D%5B2%5D=10949">Комплектующие для освещения</a></li>
                      <li><a href="/frontend/web/product/index?category=39&property%5B997%5D%5B%5D=8149">Блоки выдвижные</a></li>
                    </div>
                  </div>
                  <div class="box-20">
                    <div class="p-25">
                      <h6>Аксессуары для кухонь</h6>
                      <li><a href="/frontend/web/product/index?category=41">Барная стойка</a></li>
                      <li><a href="/frontend/web/product/index?category=47">Рейлинговая система</a></li>
                      <li><a href="/frontend/web/product/index?category=45">Лоток для приборов и поддоны</a></li>
                      <li><a href="/frontend/web/product/index?category=48">Сушки для посуды</a></li>
                      <li><a href="/frontend/web/product/index?category=49">Цоколь кухонный</a></li>
                      <li><a href="/frontend/web/product/index?category=43&property%5B1005%5D%5B%5D=8255&property%5B1005%5D%5B%5D=8256">Кухонный плинтус</a></li>
                      <li><a href="/frontend/web/product/index?category=43&property%5B1067%5D%5B%5D=8875&property%5B1067%5D%5B%5D=8876&property%5B1067%5D%5B%5D=8877&property%5B1067%5D%5B%5D=8881">Планки для столешниц</a></li>
                      <li><a href="/frontend/web/product/index?category=43&property%5B971%5D%5B%5D=7474&property%5B971%5D%5B%5D=7475&property%5B971%5D%5B%5D=7476&property%5B971%5D%5B%5D=7477&property%5B971%5D%5B%5D=9487">Планки для стеновых панелей</a></li>
                      <li><a href="/frontend/web/product/index?category=46">Мусорные системы</a></li>
                      <li><a href="/frontend/web/product/index?category=42">Корзины выдвижные</a></li>
                      <li><a href="/frontend/web/product/index?category=44">Мойки, смесители</a></li>
                    </div>
                    <div class="p-25">
                      <h6>Зеркало и комплектующие</h6>
                      <li><a href="/frontend/web/product/index?category=30&property%5B1123%5D%5B%5D=9094&property%5B1123%5D%5B%5D=9095">Зеркало, стекло</a></li>
                      <li><a href="/frontend/web/product/index?category=30&property%5B1123%5D%5B%5D=9092">Кожа</a></li>
                      <li><a href="/frontend/web/product/index?catid=185">Держатели для стекла и зеркала</a></li>
                    </div>

                  </div>
                  <div class="box-20">
                    <div class="p-25">
                      <h6>Комплектующие для шкафов</h6>
                      <li><a href="/frontend/web/product/index?category=12">Джокерная система</a></li>
                      <li><a href="/frontend/web/product/index?category=31">Наполнение для шкафов</a></li>
                      <li><a href="/frontend/web/product/index?category=50">Сетчатые изделия</a></li>
                      <li><a href="/frontend/web/product/index?category=37">Система Cadro</a></li>
                      <li><a href="/frontend/web/product/index?catid=184">Система SKM</a></li>
                      <li><a href="/frontend/web/product/index?catid=196">Системы 4 в 1</a></li>
                      <li><a href="/frontend/web/product/index?catid=196">Гардеробная система Аристо</a></li>
                    </div>
                    <div class="p-25">
                      <h6>Все для сборки мебели</h6>
                      <li><a href="/frontend/web/product/index?category=27&property%5B965%5D%5B%5D=7322&property%5B965%5D%5B%5D=7323&property%5B965%5D%5B%5D=7324">Инструменты</a></li>
                      <li><a href="/frontend/web/product/index?category=27&property%5B965%5D%5B%5D=7321">Шаблоны</a></li>
                      <li><a href="/frontend/web/product/index?category=18">Клей, герметики, клеящая лента</a></li>
                      <li><a href="/frontend/web/product/index?category=28">Заглушки</a></li>
                      <li><a href="/frontend/web/product/index?category=29">Мебельная косметика</a></li>
                    </div>

                  </div>
                  <div class="box-20">
                    <div class="p-25">
                    <h6>Крепежная и соедининения</h6>
                      <li><a href="/frontend/web/product/index?category=20">Уголки</a></li>
                      <li><a href="/frontend/web/product/index?category=25">Саморезы, винты</a></li>
                      <li><a href="/frontend/web/product/index?category=26">Эксцентрики, стяжки</a></li>
                      <li><a href="/frontend/web/product/index?category=21">Полкодержатели</a></li>
                      <li><a href="/frontend/web/product/index?catid=160">Подвески</a></li>
                     
                    </div>
                    <div class="p-25">
                      <h6>Наполнение для кровати</h6>
                      <li><a href="/frontend/web/product/index?category=10&property%5B1176%5D%5B%5D=11067">Матрасы</a></li>
                      <li><a href="/frontend/web/product/index?category=10&property%5B1176%5D%5B%5D=11076">Ортопедические основания</a></li>
                      <li><a href="/frontend/web/product/index?category=10&property%5B1176%5D%5B%5D=10978">Поролон</a></li>
                      <li><a href="/frontend/web/product/index?category=22&property%5B1175%5D%5B%5D=10976">Латы и латодержатели</a></li>
                    </div>
                  </div>
                </ul>
              </li>
              <li class="mo__ac">
                <a
                  >Мебель <ion-icon name="caret-down-outline"></ion-icon
                ></a>
                <ul class="sub-menu flex">
                  <div class="box-33">
                    <div class="p-25">
                      <h6>Готовая мебель</h6>
                      <li><a href="/frontend/web/product/index?catid=267">Столы</a></li>
                      <li><a href="/frontend/web/product/index?catid=268">Стулья</a></li>
                      <li><a href="/frontend/web/product/index?category=51">Кухни</a></li>
                      <li><a href="/frontend/web/product/index?category=51">Диваны</a></li>
                      <li><a href="/frontend/web/product/index?category=51">Шкафы</a></li>
                    </div>
                  </div>
                  <div class="box-33">
                    <div class="p-25">
                      <h6>Мебель на заказ</h6>
                      <li><a href="/frontend/web/site/finifur">Кухни на заказ</a></li>
                      <li><a href="/frontend/web/site/customfurn">Мебель для детской</a></li>
                      <li><a href="/frontend/web/site/customfurn">Мебель в ванную</a></li>
                      <li><a href="/frontend/web/site/customfurn">Мебель в спальню</a></li>
                      <li><a href="/frontend/web/site/customfurn">Мебель в прихожую</a></li>
                      <li><a href="/frontend/web/site/customfurn">Мебель для гостиной</a></li>
                    </div>
                  </div>
                  <div class="box-33 img-menu-mebel"></div>
                </ul>
              </li>
              <li class="mo__ac">
                <a
                  >Услуги <ion-icon name="caret-down-outline"></ion-icon
                ></a>
                <ul class="sub-menu flex">
                  <div class="box-25">
                    <div class="p-25">
                      <h6>Изготовление мебели</h6>
                      <li><a href="/frontend/web/site/services">Проект помещения</a></li>
                      <li><a href="/frontend/web/site/services">Проектирование мебели</a></li>
                      <li><a href="/frontend/web/site/services">Дизайн-проект</a></li>
                      <li><a href="/frontend/web/site/services">Модульные кухни</a></li>
                      <li><a href="/frontend/web/site/services">Мебель по индивидуальным размерам</a></li>
                    </div>
                  </div>
                  <div class="box-25">
                    <div class="p-25">
                      <h6>Изготовление дверей купе</h6>
                      <li>
                        <a href="/frontend/web/site/coupe-doors">Сборка дверей-купе</a>
                      </li>
                      <li><a href="/frontend/web/site/services">Каталог сборки дверей-купе</a></li>
                    </div>
                    <div class="p-25">
                      <h6>Изготовление фасадов</h6>
                      <li><a href="/frontend/web/site/services">Фасады под заказ</a></li>
                      <li><a href="/frontend/web/site/services">Замена фасадов кухни</a></li>
                    </div>
                  </div>
                  <div class="box-25">
                    <div class="p-25">
                      <h6>Кромление и распил плит</h6>
                      <li><a href="/frontend/web/site/services">Услуги распила</a></li>
                      <li><a href="/frontend/web/site/services">Услуги кромления</a></li>
                      <li><a href="/frontend/web/site/services">Плиты под заказ</a></li>
                    </div>
                  </div>
                  <div class="box-25 img-menu-serv"></div>
                </ul>
              </li>
              <li><a href="/frontend/web/site/news">Блог</a></li>
              <li><a href="/frontend/web/site/contacts">Контакты</a></li>
            </ul>
          </nav>

          <div class="side-icons flex fj-flexend fa-center">
            <a class="flex fa-center open-modal user" 

            <?=(!empty($_COOKIE['usere']))?'data-modal="#accmenu"':'data-modal="#login"';?>> 
            <?=(!empty($_COOKIE['usere']))?$_COOKIE['name']:'Войти';?>
            
            <img src="/frontend/web/media/icons/person-outline.svg" alt="вход в личный кабинет"/>
            </a>
            <a  class="open-modal" data-modal="#searchmod"
              ><img src="/frontend/web/media/icons/search-outline.svg" alt="поиск"
            /></a>
            <a href="/frontend/web/basket/basket"
              ><img
                src="/frontend/web/media/icons/bag-outline.svg"
                alt="корзина товаров"
            /></a>
            <div class="burger flex fa-center">
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
<div class="inner-header" style="position:relative;width:100%;height:90px"></div>