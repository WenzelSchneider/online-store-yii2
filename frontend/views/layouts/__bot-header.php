<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>
<div class="container-fluid mt-2">
    <div class="row">
        <div class="container">
            <div class="row d-flex justify-content-between child-div-nopad">
                <a href="/frontend/web/site/index" class="col d-flex align-items-center p-0">
                    <img src="/images/logobigtop.png" class="img-fluid w-100" alt="">
                </a>
                <div class="col-2 d-flex align-items-center">
                    <p class="text-uppercase">Поставщик ЛДСП, МДФ, Мебельной фурнитуры в Оренбурге</p>
                </div>
                <div class="col-2 d-flex align-items-baseline flex-wrap">
                    <a class="link-mote"><i class="small mr-2 fas fa-map-marker-alt" aria-hidden="true"></i> г.Оренбург ул.Юркина 9а</a>
                    <p class="mb-0"><a href="tel:83532307333"><i class="small mr-2 fas fa-phone-alt" aria-hidden="true"></i><span class="t-n-b">8 (3532) 307-333</span></a> - отдел продаж</p>
                    <p class="mb-0"><a href="tel:83532308333"><i class="small mr-2 fas fa-phone-alt" aria-hidden="true"></i><span class="t-n-b">8 (3532) 308-333</span></a> - отдел мебели на заказ</p>
                    <p class="m-0"><i class="small mr-2 far fa-clock" aria-hidden="true"></i><span class="d-inline-flex">Пн - Пт: 9:00 - 17:00<br>
                        Сб: 10:00 - 16:00 <br> Вс - выходной</p>
                </div>
                <div class="col-2 d-flex align-items-baseline flex-wrap">
                    <a class="link-mote"><i class="small mr-2 fas fa-map-marker-alt" aria-hidden="true"></i> ул.Туркестанская 68</a>
                    <p class="mb-0"><a href="tel:83532306333"><i class="small mr-2 fas fa-phone-alt" aria-hidden="true"></i><span class="t-n-b">8 (3532) 306-333</span></a>
                    доб. <a href="tel:83532306333,300"><span class="t-n-b">300</span></a>, <a href="tel:83532306333,302"><span class="t-n-b">302</span></a> - отдел продаж, доб. <a href="tel:83532306333,303"><span class="t-n-b">303</span></a> - отдел мебели на заказ</p>
                    <p class="m-0"><i class="small mr-2 far fa-clock" aria-hidden="true"></i><span class="d-inline-flex">Пн - Пт: 10:00 - 19:00<br>
                        Сб-Вс: 10:00 - 17:00</p>
                </div>
                <div class="col align-items-center justify-content-between">
                    <div class="row">
                        <div class="col-12 p-0 d-flex">
                            <a href="https://vk.com/ural_dekor" target="blank" class="btn-mote btn-ss-blue m-1"><i class="fab fa-vk" aria-hidden="true"></i></a>
                            <a href="https://www.instagram.com/uraldekor/" target="blank" class="btn-mote btn-ss-blue m-1"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                            <a href="https://ok.ru/uraldekor" target="blank" class="btn-mote btn-ss-blue m-1"><i class="fab fa-odnoklassniki" aria-hidden="true"></i></a><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 p-0">
                            <a href="mailto:ural.dekor@bk.ru" class="link-mote"><i class="small mr-2 fas fa-envelope" aria-hidden="true"></i> ural.dekor@bk.ru</a> - плитный отдел<br>
                            <a href="mailto:baza-stroi@yandex.ru" class="link-mote"><i class="small mr-2 fas fa-envelope" aria-hidden="true"></i> baza-stroi@yandex.ru</a> - отдел фурнитуры<br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 p-0">
                            <a href="" target="_blank" class="text-uppercase just-link" data-toggle="modal" data-target="#road_map_duo"><i class="small mr-2 fas fa-search-location" aria-hidden="true"></i> Схема проезда</a>
                        </div>
                    </div>
                </div>
                <div class="col d-flex align-items-baseline flex-wrap">
                    <div class="col-12"> </div>
                    <div class="col-12"> </div>
                    <div class="col-12"> </div>
                    <a href="tel:#" class="btn-mote btn-empty-blue without-icon mt-1 mb-1" data-toggle="modal" data-target="#callback_modal"><i class="fas fa-phone-alt mr-2" aria-hidden="true"></i>  <span>Обратный звонок</span></a>
                    <a href="mailto:ural.dekor@bk.ru" class="btn-mote btn-empty-blue w-100 without-icon mt-3" data-toggle="modal" data-target="#post_modal"><i class="fas fa-envelope mr-2" aria-hidden="true"></i> <span>Напишите нам</span></a>
                    <div class="col-12"> </div>
                    <div class="col-12"> </div>
                    <div class="col-12"> </div>
                </div>
                <div class="col-1 d-flex">
                    <a href="/frontend/web/basket/index" class="m-auto btn-mote fs-25 p-0 position-relative">
                        <i class="icon icon-Cart cart-top"></i>
                        <span class="d-block position-absolute cifir-basket">
                            <span class="circet-basket position-absolute"></span>
                            <span class="position-relative count-basket-top"><?= count($_SESSION['basket']['products'])?></span>
                        </span>
                        <div class="position-absolute" style="bottom: -60%; right: 40%; transform: translate(50%, 0);">
                            <span class="text-center fs-10" style="display: block;"><span class="count-basket"><?=count($_SESSION['basket']['products']);?></span> Товаров</span>
                            <span class="text-center fs-10" style="display: block;">На сумму <span class="amount-basket"><?=$_SESSION['basket']['amount'];?></span>руб</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>