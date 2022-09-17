<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use common\models\NewsImages;

$this->title = $new->new_title.' - Новости - Урал Декор';
$this->params['breadcrumbs'][] = $this->title;
$this->registerMetaTag(['name' => 'description', 'content' => $meta['keywords']]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $meta['description']]);
?>

<!--breadcrumbs-->


	<!--jumborton-texless-->
	<div class="container top-indent-50" style="margin-top:50px">
		<div class="row">
			<div class="col-12 top-indent-25">
				<?php
				  $image = NewsImages::find()
				  ->where(['new_id'=>$new->new_id])
				  ->andFilterWhere(['is_main'=>1])
				  ->asArray()
				  ->one();
				  if(!empty($image['image_link'])){
				      $img = $image['image_link'];//frontend/web/images/goods/
				  }else{
				     $img = 'https://le-go.net/images/noimage.gif';
				  }
				?>
				<div class="jumborton-img">
					<img src="<?=$img?>" 
					alt="<?=$new->new_title;?>" 
					class="jumborton-img__img">
				</div>
			</div>
		</div>
	</div>
	
    <!--content static-->
    <div class="container">
        <div class="row top-indent-65">
            <div class="col-12">
                <h1><?=$new->new_title;?></h1>
            </div>
            <div class="col-12 col-md-11 offset-md-1 top-indent-25">
                <p>
                    <?=HtmlSpecialChars_Decode($desc)?>
                </p>
            </div>
        </div>
    </div>
    <div class="container d-none">
        <div class="row top-indent-50">
            <div class="col-6 d-flex">
                <img src="/frontend/web/img/staim1.png" alt="UD" class="simple-img">
            </div>
            <div class="col-6 d-flex">
                <img src="/frontend/web/img/staim2.png" alt="UD" class="simple-img">
            </div>
        </div>
    </div>