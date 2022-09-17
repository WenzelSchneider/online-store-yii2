<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/main.min.css?t=510',
        'css/modal.css?t=18',
        'css/style.css?t=20',
        'css/footer.css?t=12',
        'css/header.css?t=21',
        'css/hero.css?t=12'
    ];
    public $js = [
        'js/jquery3.js?t=1',
        'js/scripts.min.js?t=7',
        'js/scripts.js?t=11',
        'js/toast.js?t=7',
        'js/motive.js?t=5',
        'js/modal.js?t=10',
        'js/jquery.validate.min.js?t=6',
        'js/common.js?t=483',
        
    ];
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
