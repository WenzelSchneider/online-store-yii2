<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use common\models\Goods;
use common\models\Price;
use common\models\GoodImages;
use common\models\GoodCategories;
use yii\helpers\Url;

?>

<?php
    $category = GoodCategories::find()
    ->where(['is_hide'=>0])
    ->all();
?>
                                        
<div class="col-3 d-md-block d-none">
    <ul class="menu-left">
		<?php foreach ($category as $cat): ?>
			<li class="menu-left-item">
				<a href="/frontend/web/product/index?category=<?=$cat->cat_id?>" class="menu-left-link">
					<i class="icon  <?=$cat->icon_css?>"></i>
					<span class="d-inline-block"><?=$cat->cat_title?></span>
				</a>
			</li>
		<?php endforeach; ?>
    </ul>
</div>