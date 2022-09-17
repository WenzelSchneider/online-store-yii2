<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use common\models\NewsImages;

$this->title = 'Проекты - Урал Декор';
$this->params['breadcrumbs'][] = $this->title;
?>

	<!--breadcrumbs-->
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="breadcrumbs">
					<a href="/frontend/web/" class="breadcrumbs__link">Главная<span class="breadcrumbs_bold"></span></a>/
					<a href="/frontend/web/site/projects" class="breadcrumbs__link">Проекты<span class="breadcrumbs_bold"></span></a>
    			</div>
    		</div>
        </div>
    </div>
<div class="container top-indent-25">
	<div class="row">
			<div class="col-lg-8 col-12">
			<h1>Проекты</h1>
			<p>Более 2 тысяч наименований, где вы найдете все для создания интерьера своей мечты</p>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
    <?php foreach ($projects as $project): ?>					
    	<?php
    	  if(!empty($project->project_image_link)){
    	      $img = $project->project_image_link;
    	  }else{
    	     $img = 'https://le-go.net/images/noimage.gif';
    	  }
    	  Yii::$app->formatter->asDate('now', 'php:y-m-d')
    	?>
		<div class="col-12 col-lg-6 mt-1">
			<div class="img-block-fh">
				<img src="<?=$img?>" alt="UD">
				<div class="img-block-fh__contents">
					<p class="img-block-fh__title"><?= $project->project_title ?></p>
					<p class="img-block-fh__content"><?= mb_strimwidth(HtmlSpecialChars_Decode($project->project_description), 0, 120, "...");?></p>
					<a href="/frontend/web/site/project?id=<?= $project->project_id ?>" class="img-block-fh__link just-link">Подробнее</a>
				</div>
			</div>
		</div>
    <?php endforeach; ?>
    </div>
</div>