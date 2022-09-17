<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\ContentPages;

?>

<script>
document.addEventListener('DOMContentLoaded', function(){
    <?php if( Yii::$app->session->hasFlash('success') ): ?>
    Toast.add({
        text: '<?php echo Yii::$app->session->getFlash('success'); ?>',
        color: '#28a745',
        autohide: true,
        delay: 5000
    });
    console.log('<?php echo Yii::$app->session->getFlash('success'); ?>');
    openModal('#success');
    <?php endif;?>
    <?php if( Yii::$app->session->hasFlash('danger') ): ?>
    Toast.add({
        text: '<?php echo Yii::$app->session->getFlash('danger'); ?>',
        color: '#fb6a6a',
        autohide: true,
        delay: 5000
    });
    console.log('<?php echo Yii::$app->session->getFlash('danger'); ?>');
    openModal('#danger');
    <?php endif;?>
    
    <?php if( Yii::$app->session->hasFlash('loginlog') ): ?>
    console.log('<?php echo Yii::$app->session->getFlash('loginlog'); ?>');
    openModal('#loginlog');
    <?php endif;?>
});
</script>