<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use common\models\Goods;
use common\models\Price;
use common\models\GoodImages;
use common\models\Groups;
use common\models\CatGroupRels;
use common\models\GoodCategories;
use yii\helpers\Url;

?>
<div class="container-fluid usee-block <?=(count($_SESSION['product'])>1)?'':' d-none'?>">
    <div class="row">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="text-uppercase title-block text-center">Вы смотрели</h2>
                </div>
                <div class="col-12">
                    <div id="carouselExampleControls2" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            

                                    <?php 
                                    $c=0;
                                    $i=0;
                                    ?>
                                    <?='<div class="col-12 carousel-item active"><div class="row justify-content-md-center">';?>
									<?php foreach ($_SESSION['product'] as $good_id): ?>
										<?php 
										if($c/5 == 1){
										        echo '</div></div>';
										        echo '<div class="col-12 carousel-item"><div class="row justify-content-md-center">';
										}
										?>

										<?php
										$good = Goods::find()
											->where(['good_id' => $good_id])
											->one();
										$image = GoodImages::find()
											->where(['good_id' => $good->good_id])
                                            ->andFilterWhere(['is_main'=>1])
											->asArray()
											->one();
										$price = Price::find()
										->where(['good_id'=>$good->good_id])
										->one();
										if (!empty($image['image_link'])) {
											$img = $image['image_link'];
										} else {
											$img = 'https://le-go.net/images/noimage.gif';
										}
										?>

                                        <div class="col-5-th">
                                            <div class="product-card <?= ($good->is_new == 1) ? 'new' : ''; ?> ">
                                                <div class="img-thumbed"><img src="<?= $img ?>" alt=""
                                                                              class="img-fluid m-auto"></div>
                                                                              <a href="/frontend/web/product/product?id=77189" class="product-name link-mote text-uppercase stretched-link" style="z-index:0;"></a>
                                                <a href="/frontend/web/product/product?id=<?= $good->good_id ?>"
                                                   class="product-name link-mote text-uppercase"><?= $good->title ?></a>
        											<p class="p-0 m-0 text-center"><?= $good['description'] ?></p>
                                                <div class="card-bottom-item">
                                                    <p class="costing"><span class="cost"><?= $price-> value ?></span>
                                                        &#8381;/<?= $good['termin'] ?></p>
                                                    <div class="main-block-indicator">
                                                    <?php
$groups = Groups::findOne($good['cat_id']);
$group_id = $good['cat_id'];
$group_name = $groups->name;

$catgroups = CatGroupRels::find()
    ->select('cat_id')
    ->where(['group_id' => $group_id])
    ->one();
$category_id = $catgroups['cat_id'];

$category = GoodCategories::findOne($catgroups['cat_id']);
                                                    $min = ($category->store_small_value != 0 || isset($category->store_small_value) || !empty($category->store_small_value)) ? $category->store_small_value : 10;
                                                    $mid = ($category->store_medium_value != 0 || isset($category->store_medium_value) || !empty($category->store_medium_value)) ? $category->store_medium_value : 50;
                                                    $max =  ($category->store_large_value != 0 || isset($category->store_large_value) || !empty($category->store_large_value)) ? $category->store_large_value : 100;
?>
<div class="indicator-mote <?= ( $good['in_stock'] > $min ) ? 'active' : ''?> "></div>
<div class="indicator-mote <?= ( $good['in_stock'] > $mid ) ? 'active' : ''?> "></div>
<div class="indicator-mote <?= ( $good['in_stock'] > $max ) ? 'active' : ''?> "></div>
                                                    </div>
                                                    <form method="post"
                                                          action="<?= Url::to(['basket/add']); ?>"
                                                          class="add-to-basket">
                                                        <div class="stepper stepper--style-2 js-spinner bottom-left position-absolute dinon">
                                                            <input type="number" name="count" min="1" step="1" value="1"
                                                                   class="stepper__input" data-stepper-debounce="400">
                                                            <div class="stepper__controls">
                                                                <button type="button" spinner-button="up">+</button>
                                                                <button type="button" spinner-button="down">-</button>
                                                            </div>
                                                        </div>
														<?=
														Html::hiddenInput(
															Yii::$app->request->csrfParam,
															Yii::$app->request->csrfToken
														);
														?>
                                                        <input type="hidden" name="id"
                                                               value="<?= $good->good_id ?>">
                                                        <button style="border: none;" type="submit"
                                                                class="btn-mote btn-yellow bottom-right position-absolute dinon">
                                                            <i class="fas fa-cart-plus"></i>
                                                            В корзину
                                                        </button>

                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        
										<?php if($c/9 == 1){
										    $c=4;
										}
										    $i++;
										$c++;
										?>
									<?php endforeach; ?>
                                    <?='</div></div>';?>

                                
                        </div>
                        <?php if($i > 5){
                            echo' <a class="carousel-control-prev" href="#carouselExampleControls2" role="button" data-slide="prev"> <span class="carousel-control-prev-icon" aria-hidden="true"></span> <span class="sr-only">Previous</span> </a> <a class="carousel-control-next" href="#carouselExampleControls2" role="button" data-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="sr-only">Next</span></a>';}?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>