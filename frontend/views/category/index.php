<?php

// Каталог

/* @var $this yii\web\View */

use yii\helpers\Html;
use common\models\GoodCategories;
$this->title = 'Каталог продукции компании Урал Декор';
$this->params['breadcrumbs'][] = $this->title;
?>
   
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="breadcrumbs">
					<a class="breadcrumbs__link" href="/frontend/web/"> Главная страница </a> / 
                        <a class="breadcrumbs__link" href="/frontend/web/category"> Каталог </a>  
                        
                        <?php
                            if($cat != 0){
                                $category = GoodCategories::find()
                                ->where(['cat_id'=>$cat])
                                ->one();
                                echo '<a class="breadcrumbs__link" href="/frontend/web/category/index?category='.$cat.'" >'.$category->cat_title.'</a>';
                                $catatle = $category->cat_title;
                            }else{
                                echo '';
                            }
                        ?>						
				</div>
			</div>
		</div>
	</div>
	<div class="container top-indent-25">
		<div class="row">
			<div class="col-lg-8 col-12">
                                <?php
                                if($cat != 0){
                                    $category = GoodCategories::find()
                                    ->where(['cat_id'=>$cat])
                                    ->one();
                                    echo '<h1>'.$category->cat_title.'</h1>';
                                }else{
                                    echo '<h1>Каталог</h1>';
                                }
                                ?>
				<p>Более 2 тысяч наименований, где вы найдете все для создания интерьера своей мечты</p>
			</div>
		</div>
	</div>
    <section class="catalog">
	<div class="container">
		<div class="catalog-list-wrapper flex fw-wrap ">

		<?php
            if($cat != 0){
                $category = GoodCategories::find()
                ->where(['is_hide'=>0])
                ->andWhere(['top_cat_id'=>$cat])
                ->all();
            }else{
                $category = GoodCategories::find()
                ->where(['is_hide'=>0])
                ->all();
            }
        ?>
		<?php foreach ($category as $cat): ?>
                    <?php
                        $categor = GoodCategories::find()
                        ->where(['is_hide'=>0])
                        ->andWhere(['top_cat_id'=>$cat->cat_id])
                        ->one();
                        if($categor->cat_id){
                            $link = '/frontend/web/category/index?category='.$cat->cat_id;
                        }else{
                            $link = '/frontend/web/product/index?category='.$cat->cat_id;
                        }
                    ?>
                    <div class="catalog-item">
					
					<img src="<?=($cat->icon_link)? $cat->icon_link : '/frontend/web/img/Vector 16.png';?>" alt="">
					<a href="<?=$link;?>">
				<h3><?=$cat->cat_title?></h3></a>
				
				
			</div>

				<?php endforeach; ?>
		</div>
	</div>
</section>
    
    <?php
    if(!empty($catatle)):
        ?>
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
	<div class="container top-indent-50">
		<div class="row <?=$dnon?>">
			<div class="col-12 col-lg-7 mb-1">
				<span class="h1 mb-1"><?= $catatle ;?> от компании Урал Декор</span>
				<p class="top-indent-25">
                    <?php
					echo htmlspecialchars_decode($catedesc->cat_page_description);
					?>
				</p>
                <a data-modal="#callback" class="open-modal light-btn d-flex mb-1 top-indent-25">Консультация специалиста</a>
			</div>
			<div class="col-12 col-lg-5">
				<div class="img-block-fh">
					<img src="<?=$catedesc->cat_additional_image?>" alt="UD">
				</div>
			</div>
		</div>
	</div>   
    <?php endif;?>