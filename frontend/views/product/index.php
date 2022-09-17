<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\Pjax;

use common\models\Goods;
use common\models\Price;
use common\models\GoodAssoc;
use common\models\GoodOptionValues;
use common\models\GoodOptions;
use common\models\GoodImages;
use common\models\GoodCategories;
use common\models\Groups;
use common\models\Manufacturers;
use common\models\CatGroupRels;
/* @var $this yii\web\View */

$this->title = $title.' - каталог компании Урал Декор';

?>
<!--breadcrumbs-->
<section class="breadcrumbs">
<div class="container">

                    <a class="breadcrumbs__link" href="/frontend/web/"> Главная страница </a> / 
                        <a class="breadcrumbs__link" href="/frontend/web/category"> Каталог </a> / 

                        <?if($_GET['category']):
                        $cat_top = GoodCategories::find()
                        ->select('top_cat_id')
                        ->where(['cat_id' => $_GET['category']])
                        ->one();
                        $cat_top_id = $cat_top['top_cat_id'];
                        $cat_top_search = GoodCategories::findOne($cat_top_id);
                        $cat_top_name = $cat_top_search->cat_title;
                        ?>
                        <a href="/frontend/web/category/index?category=<?= $cat_top_id?>"> <?= $cat_top_name?> </a> /
                        <a id = "a_title" href="/frontend/web/product/index?category=<?= $_GET['category']?>"> <?= $title?> </a>
                        <?endif;?>
                        
                        <?if($_GET['catid']):

                            $cifir = CatGroupRels::find()
                            ->where(['group_id'=>$_GET['catid']])
                            ->one();
                            $categeg = GoodCategories::find()
                            ->where(['cat_id'=>$cifir->cat_id])
                            ->one();
                            $podcat = Groups::find()
                            ->where(['id'=>$_GET['catid']])
                            ->one();
                            
                        $cat_top = GoodCategories::find()
                        ->select('top_cat_id')
                        ->where(['cat_id' => $cifir->cat_id])
                        ->one();
                        $cat_top_id = $cat_top['top_cat_id'];
                        $cat_top_search = GoodCategories::findOne($cat_top_id);
                        $cat_top_name = $cat_top_search->cat_title;
                            ?>
                        <a class="breadcrumbs__link" href="/frontend/web/category/index?category=<?= $cat_top_id?>"> <?= $cat_top_name?> </a> /
                        <a class="breadcrumbs__link" href="/frontend/web/product/index?category=<?= $categeg->cat_id?>"> <?= $categeg->cat_title?> </a> /
                        <a class="breadcrumbs__link" href="/frontend/web/product/index?catid=<?= $_GET['catid']?>"><span class="breadcrumbs_bold"> <?= $podcat->name?> </span></a>
                        <?endif;?>
      
     
    </div> 
                        </section>


<!--
<section class="banner-catalog"> 
    <div class="container">                       
    
<?php if ($title == "Ручки") {
    echo '<img src="/frontend/web/media/sale-01.png" />';
}  else {
    echo " ";
}?>
</div>
</section>
-->



    <!--desc-block-product-->
   
    <section class="category">
       <div style="margin:10px auto 50px auto" class="container">
        <div class="row">
            <div class="col-lg-6 col-12">
                <h1><?= $title ;?></h1>
                
            </div>
            <div class="col-lg-3 offset-lg-1 d-flex align-items-center category-search">
                    <form action="/frontend/web/product/" method="GET" style=" width: 100%;">
                        <?php
                            foreach ($_GET as $key => $value) {
                                if($key != 'q'){
                                    if($key == 'property'){
                                        foreach($value as $kkey=>$item){
                                            foreach($item as $ite){
                                                echo "<input name='property[{$kkey}][]' value='{$ite}' class='d-none'>";
                                            }
                                        }
                                    }
                                    if($key == 'brand'){
                                        foreach($value as $kkey=>$item){
                                                echo "<input name='brand[]' value='{$item}' class='d-none'>";
                                        }
                                    }
                                    if($key != 'brand' && $key != 'property'){
                                        echo "<input name='{$key}' value='{$value}' class='d-none'>";
                                    }
                                }
                            }
                        ?>
                        <input placeholder="Поиск в категории" style="border-radius: 50px; padding-left:1em; border-color: #dedede; width: 100%; padding: .8em 2em; margin: 0;" type="text" name="q"  onchange="this.form.submit()" <?=(!empty($_GET['q']))? 'value="'.$_GET['q'].'"' : '' ?>>
                        <button type="submit" class="d-none" data-pjax="1">ОТПРАВИТЬ</button>
                    </form>
            </div>
            <div class="col-lg-2 d-flex align-items-center category-orderby">
                    <form action="" method="GET">
                        <?php
                            foreach ($_GET as $key => $value) {
                                if($key != 'sort'){
                                    if($key == 'property'){
                                        foreach($value as $kkey=>$item){
                                            foreach($item as $ite){
                                                echo "<input name='property[{$kkey}][]' value='{$ite}' class='d-none'>";
                                            }
                                        }
                                    }
                                    if($key == 'brand'){
                                        foreach($value as $kkey=>$item){
                                                echo "<input name='brand[]' value='{$item}' class='d-none'>";
                                        }
                                    }
                                    if($key != 'brand' && $key != 'property'){
                                        echo "<input name='{$key}' value='{$value}' class='d-none'>";
                                    }
                                }
                            }
                        ?>
                        <select name="sort" class="sort__select" onchange="this.form.submit()">
                            <option disabled>Сортировка</option>
                            <option <?=($_GET['sort'] == 'price')? 'selected' : '' ;?> value="price">Сначала дешевые</option>
                            <option <?=($_GET['sort'] == 'pricedesc')? 'selected' : '' ;?> value="pricedesc">Сначала дорогие</option>
                            <option <?=($_GET['sort'] == 'novelty')? 'selected' : '' ;?> value="novelty">По новизне</option>
                        </select>
                        <button type="submit" class="d-none" data-pjax="1">ОТПРАВИТЬ</button>
                    </form>
            </div>
        </div>
       </div>
    </section>


    <!--product-->


    <div class="container top-indent-25">
        <div class="row">
                <!--filter-->

          <div class="filter">
                <div id="filter">
                    <form action="" method="get" class="drop-filter" id="filter_form">
                        
                        <?php
                            if(!empty($_GET['category'])){
                                echo "<input name='category' value='{$_GET['category']}' class='d-none'>";
                            }
                            if(!empty($_GET['catid'])){
                                echo "<input name='catid' value='{$_GET['catid']}' class='d-none'>";
                            }
                            if(!empty($_GET['q'])){
                                echo "<input name='q' value='{$_GET['q']}' class='d-none'>";
                            }
                            if(!empty($_GET['sort'])){
                                echo "<input name='sort' value='{$_GET['sort']}' class='d-none'>";
                            }
                        ?>
                       <!-- <div class="filter__title"><span>Фильтр по товарам</span></div> -->
                        <div class="filter__block js-filter js-filter-trigger order-3">
                            <span data-filter="1" class="js-filter-trigger filter-title">Диапозон цен</span>
                          
                            <div class="drop-filter__dropblock js-filter-content" data-filter="1">
                                <div class="drop-filter__filter flex fw-wrap">
                                    <div class="flex fa-center">  
                                 От:
                                    <input class="price-input" type="text" name="min_price" placeholder="<?= (!empty($_GET['min_price'])) ? $_GET['min_price'] : $mipr; ?>" value="<?= (!empty($_GET['min_price'])) ? $_GET['min_price'] : $mipr; ?>" onchange="setTimeout(sayHi, 1000);">
                                </div>
                                  <div class="flex fa-center">
                                 До:
                                    <input class="price-input" type="text" name="max_price" placeholder="<?= (!empty($_GET['max_price'])) ? $_GET['max_price'] : $mapr; ?>" value="<?= (!empty($_GET['max_price'])) ? $_GET['max_price'] : $mapr; ?>" onchange="setTimeout(sayHi, 1000);">
                                  </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="filter__block js-filter order-3">
                            <span data-filter="2" class="js-filter-trigger filter-title">Бренд</span>
                         
                            <div class="drop-filter__dropblock js-filter-content" data-filter="2">
                                <div class="drop-filter__filter">
                                    <?php if(count($brands) > 1):
                                        foreach ($brands as $brand):
                                            $brandname = Manufacturers::find()
                                            ->select('manufacturer_title')
                                            ->where(['manufacturer_id'=>$brand["manufacturer_id"]])
                                            ->one();
                                                if(!empty($brand['manufacturer_id'])):?>
                                                <label class="w-100">
                                                    <input type="checkbox" name="brand[]" value="<?=$brand["manufacturer_id"];?>" <?=(in_array($brand["manufacturer_id"], $_GET['brand'])) ? 'checked' : '';?> onChange="this.form.submit()">
                                                    <span><?=$brandname['manufacturer_title']?></span>
                                                </label> 
                                            <?endif;
                                        endforeach;
                                        
                                        foreach ($brands as $brand):
                                            if(!empty($brand['brand'])):?>
                                                <label class="w-100">
                                                    <input type="checkbox" name="brand[]" value="<?=$brand['brand'];?>" <?=(in_array($brand['brand'], $_GET['brand'])) ? 'checked' : '';?> onChange="this.form.submit()">
                                                    <span><?=$brand['brand']?></span>
                                                </label>
                                        <? endif;
                                        endforeach;
                                    endif;?>
                                </div>
                            </div>
                        </div>
                                                
                        <?php 
                        $options = [];
                            foreach($properties as $item){
                                $options[$item['opt_id']]['title'] = $item['op_titile'];
                                $options[$item['opt_id']][$item['opt_val_good']] = $item['value'];
                            }
                            $scp=2;
                            foreach($options as $option_key=>$option_val):
                                $scp++;
                        ?>
                            <div class="filter__block js-filter order-<?=$scp?>"><span class="js-filter-trigger filter-title" data-filter="<?=$scp?>"><?=$option_val['title']?></span> 
                               <div class="drop-filter__dropblock js-filter-content " data-filter="<?=$scp?>">
                                    <div class="drop-filter__filter">
                                        <?foreach($option_val as $prop_key=>$prop_val):
                                            if ($prop_key == 'title') {
                                                continue;
                                            }
                                        ?>
                                            <label class="w-100">
                                                <input type="checkbox" name="property[<?=$option_key?>][]" value="<?=$prop_key?>" class="" onchange="this.form.submit()" <?=(in_array($prop_key, $_GET['property'][$option_key]))? 'checked' :'';?>>
                                                    <span><?=$prop_val?></span>
                                            </label>
                                        <?endforeach;?>
                                    </div>
                                </div>
                            </div>
                        <?endforeach;?>
                        

                      
                        <button type="submit" style="display:  none;" id="cost" data-pjax="1">ОТПРАВИТЬ</button>
                    </form>
                    <form action="" method="get" class="drop-filter" id="clear_form">
                        <?php
                            if(!empty($_GET['category'])){
                                echo "<input name='category' value='{$_GET['category']}' class='d-none'>";
                            }
                            if(!empty($_GET['catid'])){
                                echo "<input name='catid' value='{$_GET['catid']}' class='d-none'>";
                            }
                            if(!empty($_GET['q'])){
                                echo "<input name='q' value='{$_GET['q']}' class='d-none'>";
                            }
                        ?>
                            <button type="submit" style="display:  none;" id="clear" data-pjax="1">ОТПРАВИТЬ</button>
                            <div>
                            <label class="sale-item-filter"><input type="checkbox"  onchange="this.form.submit()" name="sale" class="d-none">Товары по скидке</label>
                        </div>
                            <div class="filter__clear" onclick="$('#clear').click()"><span>Сбросить фильтры</span></div>
                    </form>
                </div>

            </div>

<div class="filter-toggle"><ion-icon name="ellipsis-vertical-sharp"></ion-icon></div>

            <!-- PRODUCT LIST -->

<div class="col flex fw-wrap fj-flex-start position-relative">  
                <?php foreach ($goods as $good): ?>
                    <?php
                      $image = GoodImages::find()
                      ->where(['good_id'=>$good['good_id']])
                      ->andFilterWhere(['is_main'=>1])
                      ->asArray()
                      ->one();
                      if(!empty($image['image_link'])){
                          $img = $image['image_link'];//frontend/web/images/goods/
                      }else{
                         $img = 'https://le-go.net/images/noimage.gif';
                      }
                    ?>
                    <div class="product">
                        <div class="product__block">
                            <div class="product__container">
                                <?= ($good['is_new'] == 1) ? '<div class="product__sale"><span>Новинка сезона</span></div>' : ''; ?>
                                <div class="product__img-block"><img src="<?= $img ?>"
                                        alt="<?= $good['title'] ?>" /></div>
                                <div class="product__content">
                                    <div class="product__articl" <?=$good['type_id']?>>Арт. <?= $good['description']?></div>
                                    <div class="product__title"><a href="/frontend/web/product/product?id=<?= $good['good_id'] ?>"><span class="product__title_biggest"><?= $good['title'] ?></span><br><span class="product__title_smalllest"></span></a></div>
                                    
                                    <div class="product__cost"><?= $good['value'] ?> <span class="cost-desc">&#8381;/<?=$good['termin']?></span> </div>
                                 </div>
       
                                <?= ($good['new'] == 1) ? '<div class="product__sale"><span>NEW</span></div>' : ''; ?>
                                <?= ($good['sale'] == 1) ? '<div class="product__sale"><span>SALE</span></div>' : ''; ?>
                                <a href="/frontend/web/product/product?id=<?= $good['good_id'] ?>" class="stretched-link"></a>
                                <div class="product__btn-block">
    							    <?php
    							        if($good['in_stock'] <= 0){
    							        	echo '<a class="btn -black add-to-basket" data-cnt="1">Нет в наличии</a>' ;
    							        }else{
    							        	echo '<a class="btn -black add-to-basket" data-cnt="1" data-prod="' . $good['good_id'] . '">В корзину</a>';
    							        }
    							    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
            </div>
        </div>
    </div>
    

    <!-- LIST pagination  -->

    <div class="container top-indent-25">
        <div class="row">
            <div class="col-12"><?= LinkPager::widget(['pagination' => $pagination]) ?></div>
        </div>
    </div>

<!--fulldesk-block-product-->
    <?php 
        if(isset($_GET['category'])){
            $blyat = $_GET['category'];
        }else{
            $blyat = $cifir->cat_id;
        }
        $catedesc = GoodCategories::find()
        ->select('cat_page_description,cat_additional_image')
        ->where(['cat_id'=>$blyat])
        ->one();
        if(!empty($catedesc->cat_page_description)){
            $dnon = '';
        }else{
            $dnon = 'd-none';
        }
    ?>


 
  <section class="category-description">
        <div class="container">
        <div class="row <?=$dnon?>">
            <div class="category-description-col">
                <h2><?= $title ;?> от компании Урал Декор</h2>
                <p class="top-indent-25">
                    <?php
                    echo htmlspecialchars_decode($catedesc->cat_page_description);
                    ?>
                </p>
                
            </div>
            <div class="category-description-col">
                <div class="img-block-fh">
                    <img src="<?=$catedesc->cat_additional_image?>" alt="UD">
                </div>
            </div>
        </div>
       
    </div>
    </section>

<!--
    <section class="expert-advice">
        <div class="container">
            <div class="expert-advice-col">
            </div>
            <div class="expert-advice-col">
                <span class="subtitle">Затрудняетесь с выбором?</span>
                <span class="title">Консультация специалиста</span>
                <p>Оставьте заявку и наш специалист перезвонит, даст консультацию и поможет с выбором.</p>
                <a data-modal="#callback" class="btn -black top-indent-25">Консультация</a>
            </div>
        </div>
    </section>
-->