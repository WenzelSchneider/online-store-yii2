<?php
header('Content-Type: text/html; charset=utf-8');
header("Cache-control: public");
date_default_timezone_set('Europe/Yekaterinburg');
session_start();
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + (60 * 60 * 24 * 181))); // 180 day
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/main.php',
    require __DIR__ . '/../../common/config/main-local.php',
    require __DIR__ . '/../config/main.php',
    require __DIR__ . '/../config/main-local.php'
);

if(!empty($_GET['ads_tracker'])){
    $_SESSION['ads_tracker']=$_GET['ads_tracker'];
}

(new yii\web\Application($config))->run();
