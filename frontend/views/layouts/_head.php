<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\ContentPages;

?>

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta property="og:image" content="path/to/image.jpg">
	<meta name="theme-color" content="#fff">
	
	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-LCB9VFVV90"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-LCB9VFVV90');
</script>
	

<!-- Yandex.Metrika counter
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(66106084, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script> -->

<noscript><div><img src="https://mc.yandex.ru/watch/66106084" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->


<!-- or the reference on CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/css/splide.min.css">
<script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>

<!-- Google Font: Manrope -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>

.modalreg{
    min-width: 70vw;
}
.tabs_reg {
    width: 100%;
    padding: 0;
}

.tabs_reg>input[type="radio"] {
display: none;
}

.tabs_reg>div {
/* скрыть контент по умолчанию */
display: none;
font-size: 1.5rem;
}

/* отобразить контент, связанный с вабранной радиокнопкой (input type="radio") */
#tab-btn-1:checked~#content-1,
#tab-btn-2:checked~#content-2,
#qotrifiz:checked~#ocontent-1,
#qotrijur:checked~#ocontent-2 {
display: block;
}

.tabs_reg>label {
display: inline-block;
text-align: left
vertical-align: middle;
user-select: none;
background-color: #f5f5f5;
padding: 1.3rem 1.5rem;
font-size: 1.5rem;
line-height: 1.5;
transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out;
cursor: pointer;
position: relative;
top: 1px;
    border: 1px solid #999;
    width:50%;
    margin-left: -2px;
    margin-right: -2px;
}

.tabs_reg>label:not(:first-of-type) {
border-left: none;
}

.tabs_reg>input[type="radio"]:checked+label {
background-color: #fff;
}
.tabs_reg form input{
    width: 100%;
    background-color: #fff;
    min-height: 50px;
    position: relative;
    width: 100%;
    margin: 0;
    margin-top: 2px;
    border: 1px solid #999;
    box-shadow: none;
    padding: 1.3rem 1.5rem;
    font-size: 1rem;
}
</style>
