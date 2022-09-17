<?php
namespace frontend\controllers;

use yii\web\Controller;
use common\models\Users;
use common\models\Order;
use common\models\OrdersMain;
use common\models\OrderContents;
use common\models\ClientCommon;
use common\models\ClientJurData;
use common\models\ClientFizData;
use common\models\Goods;
use common\models\Basket;
use common\models\PayLog;
use yii\httpclient\Client;
use Yii;

class OrderController extends Controller {
    
    public function actionIndex(){
        $session = Yii::$app->session;
        $session->open();
        
        $basket = (new Basket())->getBasket();

        if($_COOKIE['usere'] || $_SESSION['usere']){
    	    $userdata = ClientCommon::find()
    	    ->where(['client_id' => ($_COOKIE['usere'])?$_COOKIE['usere']:$_SESSION['usere']])
    	    ->asArray()
    	    ->one();
    	    $order['type'] = $userdata['client_type'];
    		$order['email'] = $userdata['email'];
    		$order['numberphone'] = $userdata['phone'];
    		if($userdata['client_type'] == 'fiz'){
                $clientdata = ClientFizData::find()
                ->where(['client_id' => $userdata['client_id']])
                ->one();
        		$order['name'] = $clientdata['firstname'];
        		$order['contactface'] = $clientdata['firstname'];
    	    }elseif($userdata['client_type'] == 'jur'){
                $clientdata = ClientJurData::find()
                ->where(['client_id' => $userdata['client_id']])
                ->one();
        		$order['namecompany'] = $clientdata['title_full'];
        		$order['inn'] = $clientdata['inn'];
        		$order['contactface'] = $clientdata['director_fio'];
        		$order['client_id'] = $clientdata['client_id'];
    	    }
            $session->set('order', $order);
    	}
    	
        return $this->render('index', ['basket' => $basket, 'order' => $order]);
    }
    
    public function actionComplete() {
        
        $session = Yii::$app->session;
        $session->open();
        $i=0;
        $str = '<table width="100%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td valign="top"><table cellpadding="10" cellspacing="0" width="100%" border="0"><tbody><tr><td colspan="2" valign="top"><table width="100%" cellspacing="0" border="0" cellpadding="6" style="font-size:11pt;margin-top:40px;"><tbody><tr><td style="font-weight:bolder;color:Gray;">№</td><td style="font-weight:bolder;color:Gray;">НАИМЕНОВАНИЕ</td><td style="font-weight:bolder;color:Gray;">ЦЕНА, P.</td><td style="font-weight:bolder;color:Gray;">КОЛИЧЕСТВО</td><td style="font-weight:bolder;color:Gray;">СУММА, Р.</td></tr>';           
        foreach ($_SESSION['basket']['products'] as $id => $item):
        $i++;
        $str .= '<tr><td align="center" style="border:1px solid silver;">'.$i.'</td><td align="center" style="border:1px solid silver;">'.$item["name"].'</td><td align="center" style="border:1px solid silver;" class="price_jq">'.$item["price"].'</td><td align="center" style="border:1px solid silver;">'.$item["count"].'</td> <td align="center" style="border:1px solid silver;"><b style="color:Orange;" class="cart_total_price">'.$item["price"] * $item["count"].'руб.</b></td></tr>';                                                 
        endforeach; 

        $str .='<tr><td colspan="3" align="left"><b style="color:Gray;">ИТОГО:</b><b style="color:Orange;" id="total">'.$_SESSION['basket']['amount'];
        $str .=' руб.</b></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><div class="row border"><div class="col-12"><h3 class="text-uppercase ml-2 mt-3 mb-3">Контактные данные</h5></div><div class="text-right font-weight-bold"><p>Адрес и удобная дата доставки: '.$_SESSION['order']['address'].$_SESSION['order']['datetime'];
        $str .='</p></div><div class="col-3 text-right font-weight-bold"><p>Способ оплаты: ';
                                if($_SESSION['order']['payment'] == 'online'){
                                    $str .='Онлайн';
                                }elseif($_SESSION['order']['payment'] == 'paymentonaccount'){
                                    $str .='Оплата по выставлении счета';
                                }elseif($_SESSION['order']['payment'] == 'paymentattheoffice'){
                                    $str .='Оплата в офисах компании';
                                }elseif($_SESSION['order']['payment'] == 'uponreceipt'){
                                    $str .='Оплата при получении';
                                }elseif($_SESSION['order']['payment'] == 'paymentbycreditcard'){
                                    $str .='Оплата на сайте банковской картой';
                                }elseif($_SESSION['order']['payment'] == 'fastpayment'){
                                    $str .='Оплата на сайте через систему быстрых платежей';
                                }else{
                                    $str .='Оплата не указана';
                                }
        $str .='</p></div><div class="col-3 text-right font-weight-bold"><p>Имя: '.$_SESSION['order']['contactface'].'</p></div>';
        $str .='<div class="col-3 text-right font-weight-bold"><p>Телефон: '.$_SESSION['order']['numberphone'].'</p></div>';
        $str .='<div class="col-3 text-right font-weight-bold"><p>Email: '.$_SESSION['order']['email'].'</p></div>';
        $str .='<div class="col-3 text-right font-weight-bold"><p>Сумма заказа: '.$_SESSION['basket']['amount'].'</p></div>';
        $order = (new Order())->completeOrder();
        //print_r($_SESSION);
        if($order == 'true'){
            $headers = array(
                'From' => 'webmaster@example.com',
                'Reply-To' => 'webmaster@example.com',
                'X-Mailer' => 'PHP/' . phpversion()
            );
            $orid = OrdersMain::find()
            ->where(['client_id' => ($_COOKIE['usere'])?$_COOKIE['usere']:$_SESSION['usere']])
            ->orderBy('order_id DESC')
            ->one();
            $str .='<div class="col-3 text-right font-weight-bold"><p>Номер заказа: '.(!empty($orid->order_id))?$orid->order_id:$_SESSION['order']['orderid'].'</p></div>';
            $str .='</div>';
            if(!empty($_SESSION['order']['password'])){
                $str .= '<div class="col-3 text-right font-weight-bold"><p>Ваш пароль:</p><p style="font-weight: 700; color: #dd1111;">'.$_SESSION['order']['password'].'</p></div>';
            }
           
                $cli = ClientCommon::find()
                ->where(['client_id' => ($_COOKIE['usere'])?$_COOKIE['usere']:$_SESSION['usere']])
                ->one();
                $mami = 0; 
                if($cli->client_type == "jur"){
                    if($cli->client_approved == 1){
                       $mami = 1; 
                    }
                }else{
                       $mami = 1; 
                }
                
                if($mami == 1){
                    Yii::$app->mailer->compose()
                    ->setFrom(['robot@ural-dekor.ru' => 'Урал Декор'])
                    ->setTo($_SESSION['order']['email'])
                    ->setSubject('Новый заказ на сайте')
                    ->setTextBody('Вами был совершен заказ на сайте УралДекор')
                    ->setHtmlBody($str)
                    ->send();
                        //mail($user->email, 'Новый заказ на сайте', $str, $headers);
                    $users = Users::find()
                    ->where(['email_notification'=>1])
                    ->all();
                    foreach($users as $user){
                        Yii::$app->mailer->compose()
                        ->setFrom(['robot@ural-dekor.ru' => 'Урал Декор'])
                        ->setTo($user->email)
                        ->setSubject('Новый заказ на сайте')
                        ->setTextBody('Был совершен заказ на сайте УралДекор')
                        ->setHtmlBody($str)
                        ->send();
                        //mail($user->email, 'Новый заказ на сайте', $str, $headers);
                    }
                }else{
                    $str = "<span style='color: red;'>ВНИМАНИЕ! Заказ создан на сайте, но НЕ БЫЛ отправлен в 1С. Чтобы он был отправлен в 1С, вам необходимо подтвердить реквизиты клиента-юридического лица, отредактировав его в ПУС Сайта. После этого, в течении 10 минут заказ появиться в 1С.</span>" . $str;
                    $users = Users::find()
                    ->where(['email_notification'=>1])
                    ->all();
                    foreach($users as $user){
                        Yii::$app->mailer->compose()
                        ->setFrom(['robot@ural-dekor.ru' => 'Урал Декор'])
                        ->setTo($user->email)
                        ->setSubject('Новый заказ на сайте')
                        ->setTextBody('Был совершен заказ новым клиентом на сайте УралДекор')
                        ->setHtmlBody($str)
                        ->send();
                        //mail($user->email, 'Новый заказ на сайте', $str, $headers);
                    }
                }
                if($_SESSION['order']['payment'] == 'paymentbycreditcard'){
                    $order_pay = OrdersMain::find((!empty($orid->order_id))?$orid->order_id:$_SESSION['order']['orderid']);
                    $client = new Client();
                    $response = $client->createRequest()
                        ->setMethod('POST')
                        //->setUrl('https://3dsec.sberbank.ru/payment/rest/register.do')
                        ->setUrl('https://securepayments.sberbank.ru/payment/rest/register.do')
                        ->setData(['userName' => 'P561100988596-api', 'password' => 'J348kFrNH3wHrXv', 'orderNumber' => (!empty($orid->order_id))?$orid->order_id:$_SESSION['order']['orderid'], 'amount' => $_SESSION['basket']['amount'] * 100, 'currency' => '643', 'failUrl' => 'https://ural-dekor.ru/frontend/web/order/fail', 'returnUrl' => 'https://ural-dekor.ru/frontend/web/order/success'])
                        ->send();
                    if ($response->isOk) {
                        if(!empty($response->data['errorCode'])){
                            $log = new PayLog();
                            $log->order_id = (!empty($orid->order_id))?$orid->order_id:$_SESSION['order']['orderid'];
                            $log->error_code = $response->data['errorCode'];
                            $log->error_text = $response->data['errorMessage'];
                            $log->save();
                            return $this->render('fail');
                        }else{
                        $log = new PayLog();
                        $log->error_text = $response->data['orderId'];
                            $log->order_id = (!empty($orid->order_id))?$orid->order_id:$_SESSION['order']['orderid'];
                        $log->save();
                            $_SESSION['order']['sber'] = $response->data;
                            $_SESSION['order']['paylink'] = $response->data['formUrl'];
                            
                            $orsb = OrdersMain::find()
                            ->where(['order_id' => (!empty($orid->order_id))?$orid->order_id:$_SESSION['order']['orderid']])
                            ->one();
                            $orsb->sber_id = $response->data['orderId'];
                            $orsb->sber_url = $response->data['formUrl'];
                            $orsb->save();
                            //print_r($response->data['formUrl']);
                            //print_r($response->data);
                            //echo 'Прошлооо 1';
                        }
                    }
                }
            
            return $this->render('complete', ['orid' => (!empty($orid->order_id))?$orid->order_id:$_SESSION['order']['orderid']]);
        }else{
            return $this->render('index');
        }
    }

    public function actionProductMethod() {
	    $session = Yii::$app->session;
    	$session->open();

        if (!$session->has('order')) {
            $session->set('order', []);
        } else {
		    $order = $session->get('order');
        }
        
        if($_POST['delivery'] == 'courier'){
            if(!empty($_POST['city'])&!empty($_POST['street'])&!empty($_POST['numh'])){
	            $order['delivery'] = 1;
	            $order['address'] = $_POST['city'].', улица '.$_POST['street'].', дом '.$_POST['numh'];
	            //$order['comment'] = $_POST['comment'];
	            //$order['datetime'] = $_POST['datetime'];
            }else{ 
                return false;
            }
        }else{
            $order['delivery'] = 0;
            $order['address'] = 'Склад на Туркестанской 68';
            if($_POST['delivery'] == 'selfgeturk'){
                $order['address'] = 'Склад на Юркина 9а';
            }elseif($_POST['delivery'] == 'selfgetturk'){
                $order['address'] = 'Склад на Туркестанской 68';
            }
        }
        
	    $session->set('order', $order);
	    
	    return true;
    }

    public function actionPaymentMethod() {
	    $session = Yii::$app->session;
    	$session->open();

	    $_SESSION['order']['payment'] = 'office';
        if(!empty($_POST['payment'])){
    	    $_SESSION['order']['payment'] = $_POST['payment'];
    	    $session->set('order', $_SESSION['order']);
        }
        //print_r($_POST);
        return true;
    }
    
    public function actionContactInformationLegal() {
    	$session = Yii::$app->session;
    	$session->open();
    	
    	if (!$session->has('order')) {
            $session->set('order', []);
    	} else {
    		$order = $session->get('order');
    	}
    
    	if(!empty($_POST['type'])){
    	    $order['type'] = $_POST['type'];
    		$order['namecompany'] = $_POST['companyname'];
    		$order['inn'] = $_POST['inn'];
    		$order['numberphone'] = $_POST['telephone'];
    		$order['email'] = $_POST['email'];
    		$order['contactface'] = $_POST['contactname'];
    		$order['password'] = $_POST['password'];
    	        $session->set('order', $order);
            $responce = ClientCommon::find()
             ->where(['email'=>$order['email']])
             ->orWhere(['phone'=>$order['numberphone']])
             ->one();
             if((!empty($responce['email']))){
                return "Пользователь с данным номером телефона или электронным адресом уже существует!";
             }else{
    			return true;
             }
    	}
        return false;
    }

    public function actionContactInformationPrivate() {
    	$session = Yii::$app->session;
    	$session->open();
    
    	if (!$session->has('order')) {
            $session->set('order', []);
    	} else {
    		$order = $session->get('order');
    	}
    
    	if(!empty($_POST['type'])){
    	    $order['type'] = $_POST['type'];
    		$order['name'] = $_POST['contactname'];
    		$order['email'] = $_POST['email'];
    		$order['numberphone'] = $_POST['telephone'];
    		$order['contactface'] = $_POST['contactname'];
    		$order['password'] = $_POST['password'];
    	        $session->set('order', $order);
            $responce = ClientCommon::find()
             ->where(['email'=>$order['email']])
             ->orWhere(['phone'=>$order['numberphone']])
             ->one();
             if(!empty($responce['email'])){
                 return "Пользователь с данным номером телефона или электронным адресом уже существует!";
             }else{
    	        return true;
             }
    	}
        return false;
    }
    
    public function actionDelivery(){
        $session = Yii::$app->session;
        $session->open();
        $basket = (new Basket())->getBasket();
        if($_COOKIE['usere'] || $_SESSION['usere']){
    	    $userdata = ClientCommon::find()
    	    ->where(['client_id' => ($_COOKIE['usere'])?$_COOKIE['usere']:$_SESSION['usere']])
    	    ->asArray()
    	    ->one();
    	    $order['type'] = $userdata['client_type'];
    		$order['email'] = $userdata['email'];
    		$order['numberphone'] = $userdata['phone'];
    		if($userdata['client_type'] == 'fiz'){
                $clientdata = ClientFizData::find()
                ->where(['client_id' => $userdata['client_id']])
                ->one();
        		$order['name'] = $clientdata['firstname'];
        		$order['contactface'] = $clientdata['firstname'];
    	    }elseif($userdata['client_type'] == 'jur'){
                $clientdata = ClientJurData::find()
                ->where(['client_id' => $userdata['client_id']])
                ->one();
        		$order['namecompany'] = $clientdata['title_full'];
        		$order['inn'] = $clientdata['inn'];
        		$order['contactface'] = $clientdata['director_fio'];
        		$order['client_id'] = $clientdata['client_id'];
    	    }
            $session->set('order', $order);
    	}else{
            $order = $_SESSION['order'];
    	}
        return $this->render('delivery', ['basket' => $basket, 'order' => $order]);
    }
    public function actionPayment(){
        $session = Yii::$app->session;
        $session->open();
        
        $basket = (new Basket())->getBasket();
            $order = $_SESSION['order'];
        return $this->render('payment', ['basket' => $basket, 'order' => $order]);
    }
    
    public function actionFail(){
        $session = Yii::$app->session;
        $session->open();
        $orsb = OrdersMain::find()
        ->where(['sber_id' => $_GET['orderId']])
        ->one();
        $orsb->pt_id = 2;
        $orsb->save();
        return $this->render('fail');
    }
    public function actionSuccess(){
        $session = Yii::$app->session;
        $session->open();
        
        $orsb = OrdersMain::find()
        ->where(['sber_id' => $_GET['orderId']])
        ->one();
        $orsb->order_paid = 1;
        $orsb->pt_id = 2;
        $orsb->save();
            
        return $this->render('success');
    }
}