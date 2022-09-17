<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use common\models\NewsImages;

$this->title = $project->project_title.' - Проекты - Урал Декор';
$this->params['breadcrumbs'][] = $this->title;
$this->registerMetaTag(['name' => 'description', 'content' => $meta['keywords']]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $meta['description']]);
?>

<!--breadcrumbs-->
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="breadcrumbs">
					<a href="/frontend/web/" class="breadcrumbs__link">Главная<span class="breadcrumbs_bold"></span></a>/
					<a href="/frontend/web/site/news" class="breadcrumbs__link">Новости<span class="breadcrumbs_bold"></span></a>/
					<a href="/frontend/web/site/new?id=<?=$new->new_id?>" class="breadcrumbs__link"><span class="breadcrumbs_bold">Услуги мебельного производства</span></a></div>
			</div>
		<div class="col-3 backbox top-indent-50">
			    <a onclick="window.history.back();" class="backbox__link"></a>
			</div>
		</div>
    </div>

	<!--jumborton-texless-->
	<div class="container top-indent-50">
		<div class="row">
			<div class="col-12 top-indent-25">
				<?php
				  if(!empty($project->project_image_link)){
				      $img = $project->project_image_link ;//frontend/web/images/goods/
				  }else{
				     $img = 'https://le-go.net/images/noimage.gif';
				  }
				?>
				<div class="jumborton-img">
					<img src="<?=$img?>" 
					alt="<?=$project->project_title;?>" 
					class="jumborton-img__img">
				</div>
			</div>
		</div>
	</div>
	
    <!--content static-->
    <div class="container">
        <div class="row top-indent-65">
            <div class="col-12">
                <h1><?=$project->project_title;?></h1>
            </div>
            <div class="col-12 col-md-11 offset-md-1 top-indent-25">
                <p>
                    <?=HtmlSpecialChars_Decode($desc)?>
                </p>
            </div>
        </div>
    </div>