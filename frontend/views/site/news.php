<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use common\models\NewsImages;

$this->title = 'Новости - Урал Декор';
$this->params['breadcrumbs'][] = $this->title;
?>

	<!--breadcrumbs-->

    <div class="container top-indent-25">
    	<div class="row">
    			<div class="col-lg-8 col-12">
    			<h1>Новости</h1>
    			
    		</div>
    	</div>
    </div>
    <div class="container">
        <?php foreach ($news as $new): ?>					
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
        	  Yii::$app->formatter->asDate('now', 'php:y-m-d')
        	?>
        	<div class="row news">
        		<div class="col-md-6 col-12 news__img"><img src="<?=$img?>" alt="we"></div>
        		<div class="col-md-6 col-12 news__content">
        		    <div class="news__time"><span><?=Yii::$app->formatter->asDate($new->new_date, 'php:d.m.Y')?></span></div>
        			<div class="news__title"><h2><?= $new->new_title ?></h2></div>
        			<div class="news__short-desk"><p><?= mb_strimwidth(HtmlSpecialChars_Decode($new->new_short_description), 0, 120, "..."); ?></p></div>
        			<div class="news__link"><a href="/frontend/web/site/new?id=<?= $new->new_id ?>" class="just-link">Подробнее</a></div>
        		</div>
    		</div>
        <?php endforeach; ?>
    </div>
</div>