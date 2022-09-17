<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;

    //<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=<ваш API-ключ>" type="text/javascript"></script>
    //<script src="https://yandex.st/jquery/2.2.3/jquery.min.js" type="text/javascript"></script>
    //<script src="route_panel_control.js" type="text/javascript"></script>
/* @var $this yii\web\View */

$this->title = 'Урал Декор | Каталог мебельной фурнитуры и плитной продукции для производства мебели';
?>

<!--breadcrumbs-->
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="breadcrumbs">
					<a href="#" class="breadcrumbs__link">Главная<span class="breadcrumbs_bold"></span></a>/
					<a href="#" class="breadcrumbs__link"><span class="breadcrumbs_bold">Контакты</span></a></div>
			</div>
		</div>
	</div>

	<!--contact-->
	<div class="container page-title">
				<h1>Контакты</h1>
				<p>Если у вас возникли вопросы по товару или наличию, мы всегда рады помочь и проконсультируем вас.</p>
	</div>
  <div class="container">

		<div class="contacts-row contacts-big">
			<div class="contacts-box">
       <ul class="contacts-ul">
							<li class="contacts-li contacts-li-title">г.Оренбург, ул.Юркина, 9а</li>
							<li class="contacts-li">Пн - Пт: 9:00 - 17:00, Сб: 10:00 - 16:00, Вс - вых.</li>
							<li class="contacts-li">+7 (3532) 307-333</li>
       </ul>
      </div>
      <div class="contacts-box">
       <ul class="contacts-ul">
							<li class="contacts-li contacts-li-title">г.Оренбург, ул.Туркестанская, 68</li>
							<li class="contacts-li">Пн - Пт: 10:00 - 19:00, Сб - Вс: 10:00 - 17:00</li>
							<li class="contacts-li">+7 (3532) 306-333</li>
       </ul>
      </div>
		</div>

    <div class="contacts-row contacts-small">
			<div class="contacts-box">
       <ul class="contacts-ul">
							<li class="contacts-li contacts-li-title">Отдел продаж</li>
							<li class="contacts-li">+7 (3532) 307-333 (доб.202)</li>
							<li class="contacts-li">ural.dekor@bk.ru</li>
       </ul>
      </div>
      <div class="contacts-box">
       <ul class="contacts-ul">
							<li class="contacts-li contacts-li-title">Отдел фасадов и дверей-купе</li>
							<li class="contacts-li">+7 (3532) 307-333 (доб.301)</li>
							<li class="contacts-li">ural.dekor@bk.ru</li>
       </ul>
      </div>
      <div class="contacts-box">
       <ul class="contacts-ul">
							<li class="contacts-li contacts-li-title">Отдел распила</li>
							<li class="contacts-li">+7 (3532) 307-333 (доб.216)</li>
							<li class="contacts-li">ural.dekor@bk.ru</li>
       </ul>
      </div>
      <div class="contacts-box">
       <ul class="contacts-ul">
							<li class="contacts-li contacts-li-title">Отдел мебели на заказ</li>
							<li class="contacts-li">+7 (3532) 307-333 (доб.402)</li>
							<li class="contacts-li">mebel@ural-dekor.ru</li>
       </ul>
      </div>
      <div class="contacts-box">
       <ul class="contacts-ul">
							<li class="contacts-li contacts-li-title">Отдел доставок</li>
							<li class="contacts-li">+7 (987) 346-7101</li>
							<li class="contacts-li">ural.dekor@bk.ru</li>
       </ul>
      </div>
      <div class="contacts-box">
       <ul class="contacts-ul">
							<li class="contacts-li contacts-li-title">Отдел маркетинга</li>
							<li class="contacts-li">+7 (3532) 307-333 (доб.207)</li>
							<li class="contacts-li">marketing@ural-dekor.ru</li>
       </ul>
      </div>
      <div class="contacts-box">
       <ul class="contacts-ul">
							<li class="contacts-li contacts-li-title">Отдел бухгалтерии</li>
							<li class="contacts-li">+7 (3532) 307-333 (доб.207)</li>
							<li class="contacts-li">ural.dekor@bk.ru</li>
       </ul>
      </div>
      <div class="contacts-box">
       <ul class="contacts-ul">
							<li class="contacts-li contacts-li-title">Отдел закупок</li>
							<li class="contacts-li">+7 (3532) 307-333 (доб.205)</li>
							<li class="contacts-li">ural.dekor@bk.ru</li>
       </ul>
      </div>
		</div>
		
	<!--	<div id="map" class="h-100"></div> -->
	</div>

	<!--three img-->
	<div class="container top-indent-50">
		<div class="row">
			<div class="col-7 col-lg-3 top-indent-25"><a href="#" class="w-100 h-100 overhid d-flex"><img src="/frontend/web/img/cont1.png"
						alt="Склад" class="simple-img"></a></div>
			<div class="col-5 col-lg-3 top-indent-25"><a href="#" class="w-100 h-100 overhid d-flex"><img src="/frontend/web/img/cont2.png"
						alt="Склад" class="simple-img"></a></div>
			<div class="col-12 col-lg-6 top-indent-25"><a href="#" class="w-100 h-100 overhid d-flex"><img src="/frontend/web/img/cont3.png"
						alt="Склад" class="simple-img"></a></div>
		</div>
	</div>
	<!--<script>
	    ymaps.ready(function () {
            var myMap = new ymaps.Map('map', {
                center: [56.753994, 37.622093],
                zoom: 15,
                // Добавим панель маршрутизации.
                controls: ['routePanelControl']
            });
        
            var control = myMap.controls.get('routePanelControl');
        
            // Зададим состояние панели для построения машрутов.
            control.routePanel.state.set({
                // Тип маршрутизации.
                type: 'masstransit',
                // Выключим возможность задавать пункт отправления в поле ввода.
                fromEnabled: true,
                // Адрес или координаты пункта отправления.
                to: 'Оренбург, Юркина 9а',
                // Включим возможность задавать пункт назначения в поле ввода.
                toEnabled: false
                // Адрес или координаты пункта назначения.
                //from: 'Петербург'
            });
        
            // Зададим опции панели для построения машрутов.
            control.routePanel.options.set({
                // Запрещаем показ кнопки, позволяющей менять местами начальную и конечную точки маршрута.
                allowSwitch: false,
                // Включим определение адреса по координатам клика.
                // Адрес будет автоматически подставляться в поле ввода на панели, а также в подпись метки маршрута.
                reverseGeocoding: true,
                // Зададим виды маршрутизации, которые будут доступны пользователям для выбора.
                types: { masstransit: true, pedestrian: true, taxi: true }
            });
        
            // Создаем кнопку, с помощью которой пользователи смогут менять местами начальную и конечную точки маршрута.
            var switchPointsButton = new ymaps.control.Button({
                data: {content: "Поменять местами", title: "Поменять точки местами"},
                options: {selectOnClick: false, maxWidth: 160}
            });
            // Объявляем обработчик для кнопки.
            switchPointsButton.events.add('click', function () {
                // Меняет местами начальную и конечную точки маршрута.
                control.routePanel.switchPoints();
            });
            myMap.controls.add(switchPointsButton);
        });
	</script>-->
