<?php
namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\ClientCommon;
use common\models\ClientFizData;
use common\models\ClientJurData;
use common\models\OrdersMain;
use common\models\OrderContents;
use common\models\Goods;
use common\models\Price;
use common\models\MailingList;
use yii\data\Pagination;
use yii\db\Expression;

/**
 * Product controller
 */
class UserController extends Controller
{
    
  public function postRequest($url, $data, $refer = "", $timeout = 10, $header = [])
    {
    $curlObj = curl_init();
    $ssl = stripos($url,'https://') === 0 ? true : false;
    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_AUTOREFERER => 1,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)',
        CURLOPT_TIMEOUT => $timeout,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
        CURLOPT_HTTPHEADER => ['Expect:'],
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        CURLOPT_REFERER => $refer
    ];
    if (!empty($header)) {
        $options[CURLOPT_HTTPHEADER] = $header;
    }
    if ($refer) {
        $options[CURLOPT_REFERER] = $refer;
    }
    if ($ssl) {
        //support https
        $options[CURLOPT_SSL_VERIFYHOST] = false;
        $options[CURLOPT_SSL_VERIFYPEER] = false;
    }
    curl_setopt_array($curlObj, $options);
    $returnData = curl_exec($curlObj);
    if (curl_errno($curlObj)) {
        //error message
        $returnData = curl_error($curlObj);
    }
    curl_close($curlObj);
    return $returnData;
    }
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
    	$session = Yii::$app->session;
    	$session->open();
    	$id = $_COOKIE['usere'];
    	
        if($_COOKIE['usere']){
		    $userdata = ClientCommon::find()
		    ->where(['client_id' => $id])
		    ->asArray()
		    ->one();
			    $clientdat['type'] = $userdata['client_type'];
				$clientdat['email'] = $userdata['email'];
				$clientdat['numberphone'] = $userdata['phone'];
				$clientdat['price_type'] = $userdata['price_type_id'];
				$clientdat['price_type_ldsp'] = $userdata['price_type_id_two'];
		    if($userdata['client_type'] == 'jur'){
		        $clientdata = ClientJurData::find()
		        ->where(['client_id' => $id])
		        ->one();
				$clientdat['namecompany'] = $clientdata['title_full'];
				$clientdat['address_fact'] = $clientdata['address_fact'];
				$clientdat['address_jur'] = $clientdata['address_jur'];
				$clientdat['contactface'] = $clientdata['contactface'];
				$clientdat['name'] = $clientdata['director_fio'];
				$clientdat['inn'] = $clientdata['inn'];
				$clientdat['ogrn'] = $clientdata['ogrn'];
				$clientdat['bank'] = $clientdata['bank'];
		    }elseif($userdata['client_type'] == 'fiz'){
		        $clientdata = ClientFizData::find()
		        ->where(['client_id' => $id])
		        ->one();
				$clientdat['name'] = $clientdata['firstname'];
				$clientdat['contactface'] = $clientdata['firstname'];
		    }
	        $session->set('clientdat', $clientdat);
		}else{
	        return $this->redirect(['category/index']);
		}
		
		$orders = OrdersMain::find()
    	->where(['client_id'=>$_COOKIE['usere']])
    	->orderBy(['order_id' => SORT_DESC])
    	->limit(10)
    	->all();
    	
    	$models = new \yii\db\Query;
                $models->select()
                ->from('`goods`')
                ->innerJoin('`price`','`goods`.`good_id` = `price`.`good_id`')
                ->innerJoin('`good_favourites`','`goods`.`good_id` = `good_favourites`.`good_id`')
                ->where(['>','`price`.`value`', 0]);
                $models->andWhere(['=','`good_favourites`.`is_hide`', 0])
                ->andWhere(['client_id'=>$_COOKIE['usere']]);
                if(!empty($_SESSION['clientdat']['price_type'])){$data = $_SESSION['clientdat']['price_type'];}else{$data = 3;}
                if(!empty($_SESSION['clientdat']['price_type_ldsp'])){$data_ldsp = $_SESSION['clientdat']['price_type_ldsp'];}else{$data_ldsp = 11;}
                $models->andWhere('price.type_id = '.$data.' OR price.type_id = '.$data_ldsp);
                $models->limit(8);
                $command = $models->createCommand();
                $goods = $command->queryAll();
    //var_dump($models->prepare(\Yii::$app->db->queryBuilder)->createCommand()->rawSql);
    	
        return $this->render('lkv2',[
            'clientdat'=>$clientdat,
            'orders'=>$orders,
            'goods'=>$goods
            ]);
    }
    
    public function actionEdit()
    {
    	$session = Yii::$app->session;
    	$session->open();
    	$clients = ClientCommon::find()
    	->where('client_id <>'.$_COOKIE['usere'])
    	->andWhere('phone = '.$_POST['numberphone'].' OR email = "'.$_POST['email'].'"')
    	->all();
    	if(!empty($clients)){
            Yii::$app->session->setFlash('danger', "Пользователь с такими контактными данными уже существует!");
            return $this->redirect('index');
    	};
    	if(!empty($_COOKIE['usere']) & !empty($_POST)){// Если он уже авторизован
            $clientcommona = ClientCommon::find()->where(['client_id' => $_COOKIE['usere']])->one();
            $clientcommona->phone = $_POST['numberphone'];
            $clientcommona->email = $_POST['email'];
            $clientcommona->save();
            if ($clientcommona->client_type == "jur") { // Если успешно перезаписаны данные для авторизации
                $clientjur = ClientJurData::find()
                ->where(['client_id'=>$_COOKIE['usere']])
                ->one();
                $clientjur->inn = $_POST['inn'];
                $clientjur->title = $_POST['namecompany'];
                $clientjur->title_full = $_POST['namecompany'];
                $clientjur->contactface = $_POST['contactface'];
                $clientjur->director_fio = $_POST['director'];
                if($clientjur->save()){ // Если успешно перезаписаны данные о клиенте
                    $clientcommonid = $_COOKIE['usere'];
                }else{
                    Yii::$app->session->setFlash('danger', "Произошла ошибка при перезаписи данных");
                }
            }elseif ($clientcommona->client_type == "fiz") { // Если успешно перезаписаны данные для авторизации
                $clientfiz = ClientFizData::find()
                ->where(['client_id'=>$_COOKIE['usere']])
                ->one();
                $clientfiz->firstname = $_POST['name'];
                if($clientfiz->save()){ // Если успешно перезаписаны данные о клиенте
                    $clientcommonid = $_COOKIE['usere'];
                }else{
                    Yii::$app->session->setFlash('danger', "Произошла ошибка при перезаписи данных");
                }
            }
        }else{
            //
        }
    	
        
        return $this->redirect('index');
    }
    
    public function actionMyOrder(){
    	$session = Yii::$app->session;
    	$session->open();
    	$orders = OrdersMain::find()
    	->where(['client_id'=>$_COOKIE['usere']])
    	->orderBy(['order_id' => SORT_DESC])
    	->all();
    	
        if(preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]) || $mobile == 1)
        {
        $this->layout = 'main_mobile';
        return $this->render('my_order_mob',['orders'=>$orders]);
        }
        
        return $this->render('my_order',['orders'=>$orders]);
    }
    
    public function actionDublicate(){
        //print_r($_GET);
    	$session = Yii::$app->session;
    	$session->open();
    	//print_r();
    	//$_SESSION['testing']=$_POST;
    	if($_COOKIE['usere']){
                 return json_encode(true);
    	}
             $responce = ClientCommon::find()
             ->where(['email'=>$_GET['email']])
             ->orWhere(['phone'=>$_GET['telephone']])
             ->one();
             if(!empty($responce['email'])){
                 return json_encode(false);
             }else{
                 return json_encode(true);
              }
                 return json_encode('Произошла неизвестная ошибка');
    }
    
    public function actionPasschange(){
    	$session = Yii::$app->session;
    	$session->open();
            $pasch = ClientCommon::find()
            ->where(['client_id'=>$_COOKIE['usere']])
            ->one();
        if(!empty($_POST['passwordold']) & !empty($_POST['passwordrepeat'])){
            if($_POST['passwordrepeat'] == $_POST['password']){
                if($pasch->passwrd == $_POST['passwordold']){
                    $pasch->passwrd = $_POST['password'];
                    $pasch->save();
                    Yii::$app->session->setFlash('danger', "Пароль успешно сменен");
                    return $this->redirect(['user/index']);
                }else{
                    Yii::$app->session->setFlash('danger', "Неверный пароль");
                    return $this->redirect(['user/index']);
                }
            }else{
                    Yii::$app->session->setFlash('danger', "Пароли должны совпадать");
                    return $this->redirect(['user/index']);
            }
        }else{
                    Yii::$app->session->setFlash('danger', "Не все поля заполнены");
                    return $this->redirect(['user/index']);
        }
    }
    
public function actionReplayOrder($id){
    	$session = Yii::$app->session;
    	$session->open();
    	
    $order = OrderContents::find()
    ->where(['order_id'=>$id])
    ->all();
    $_SESSION['basket'] = '';
    $_SESSION['basket']['amount']=0;
        foreach ($order as $product) {
            $good = Goods::find()
            ->where(['good_id'=>$product->good_id])
            ->one();
                if(!empty($_SESSION['clientdat']['price_type'])){$data = $_SESSION['clientdat']['price_type'];}else{$data = 3;}
                if(!empty($_SESSION['clientdat']['price_type_ldsp'])){$data_ldsp = $_SESSION['clientdat']['price_type_ldsp'];}else{$data_ldsp = 11;}
            $price = Price::find()
            ->where(['good_id'=> $good->good_id])
            ->andWhere('price.type_id = '.$data.' OR price.type_id = '.$data_ldsp)
            ->andWhere('price.value > 0')
            ->one();
            $_SESSION['basket']['products'];
            
            $_SESSION['basket']['products'][$product->good_id]['name'] = $good->title;
            $_SESSION['basket']['products'][$product->good_id]['price'] = $price->value;
            $_SESSION['basket']['products'][$product->good_id]['good_id'] = $good->good_id;
            $_SESSION['basket']['products'][$product->good_id]['count'] = $product->amount;
            $_SESSION['basket']['amount'] += $price->value * $product->amount;
        }
        return $this->redirect(['basket/basket']);
}
    
    public function actionResetpass(){
        $user = ClientCommon::find()
        ->where(['email'=>$_POST['email']])
        ->orWhere(['phone'=>$_POST['email']])
        ->one();
         if (!empty($user))
         {
             if($user->email == $_POST['email']){
                 $stype = 0;
             }elseif($user->phone == $_POST['email']){
                 $stype = 1;
             }else{
                 $stype = 2;
             }
                 $stype = 0;
            $simv = array ("92", "83", "7", "66", "45", "4", "36", "22", "1", "0", 
             "k", "l", "m", "n", "o", "p", "q", "1r", "3s", "a", "b", "c", "d", "5e", "f", "g", "h", "i", "j6", "t", "u", "v9", "w", "x5", "6y", "z5");
            for ($k = 0; $k < 8; $k++)
            {
             shuffle ($simv);
             $string = $string.$simv[1];
            }
           $user->passwrd = $string;
           if($user->save()){
                if($stype == 0){
                    Yii::$app->mailer->compose()
                    ->setFrom(['robot@ural-dekor.ru' => 'Урал Декор'])
                    ->setTo($user['email'])
                    ->setSubject('Смена пароля на сайте')
                    ->setTextBody('Вами был успешно сменен пароль на сайте. <br/>')
                    ->setHtmlBody('Не передавайте свои личные данные третьим лицам : ' . $string)
                    ->send();
                }elseif($stype == 1){
                    $data['login'] = 'ural-dekor';
                    $data['psw'] = 'uraldecor56';
                    $data['phones'] = $user['phone'];
                    $data['mes'] = 'Не передавайте свои личные данные третьим лицам :'.$string;
                    $sms = $this->postRequest('smsc.ru/sys/send.php', $data);
                }else{
                    if (\Yii::$app->request->referrer) {
                     Yii::$app->session->setFlash('success', "Неработаетсовсем");
                        return $this->redirect(Yii::$app->request->referrer);
                    } else {
                        return $this->goHome();
                    }
                }
                if (\Yii::$app->request->referrer) {
                 Yii::$app->session->setFlash('success', "Вам был отправлен новый пароль");
                    return $this->redirect(Yii::$app->request->referrer);
                } else {
                    return $this->goHome();
                }
           }else{
                if (\Yii::$app->request->referrer) {
                 Yii::$app->session->setFlash('danger', "Произошла ошибка при смене пароля");
                    return $this->redirect(Yii::$app->request->referrer);
                } else {
                    return $this->goHome();
                }
           }
        }else{
                 Yii::$app->session->setFlash('danger', "Пользователь с таким email или телефоном не был обнаружен");
                if (\Yii::$app->request->referrer) {
                    return $this->redirect(Yii::$app->request->referrer);
                } else {
                    return $this->goHome();
                }
        }
                if (\Yii::$app->request->referrer) {
                 Yii::$app->session->setFlash('danger', "Произошла ошибка при смене пароля");
                    return $this->redirect(Yii::$app->request->referrer);
                } else {
                    return $this->goHome();
                }
    }
    
    public function actionRegleg(){
        $session = Yii::$app->session;
        $session->open();
        return $this->render('userContactInformationLegal');
    }
    public function actionRegpriv(){
        $session = Yii::$app->session;
        $session->open();
        return $this->render('userContactInformationPrivate');
    }
    
    public function actionReg(){
        
        $session = Yii::$app->session;
        $session->open();
        
            $client = $_POST;
            $clientcommon = new \common\models\ClientCommon();
            
            // Если человек регистрируется как ЮР ЛИЦО
            if($client['type']=='jur'){
                if(!empty($_COOKIE['usere'])){// Если он уже авторизован
                    $clientcommona = ClientCommon::find()->where(['client_id' => $_COOKIE['usere']])->one();
                    $clientcommona->phone = $client['telephone'];
                    $clientcommona->email = $client['email'];
                    $clientcommona->save();
                    if ($clientcommona->save()) { // Если успешно перезаписаны данные для авторизации
                        $clientjur = ClientJurData::find()
                        ->where(['client_id'=>$_COOKIE['usere']])
                        ->one();
                        $clientjur->inn = $client['inn'];
                        $clientjur->title = $client['companyname'];
                        $clientjur->title_full = $client['companyname'];
                        $clientjur->director_fio = $client['contactname'];
                        if($clientjur->save()){ // Если успешно перезаписаны данные о клиенте
                            $clientcommonid = $_COOKIE['usere'];
                Yii::$app->session->setFlash('success', "Информация успешно поменяна");
                        }else{
                            Yii::$app->session->setFlash('danger', "Произошла ошибка при перезаписи данных о клиенте");
                        }
                    }else{
                        Yii::$app->session->setFlash('danger', "Произошла ошибка при перезаписи данных для авторизации1");
                        }
                }else{ // Если клиент еще не был авторизован, то создаем нового
                
                    $clientcommon = new \common\models\ClientCommon();
                    $clientcommon->client_type = 'jur';
                    $clientcommon->phone = $client['telephone'];
                    $clientcommon->email = $client['email'];
                    $clientcommon->passwrd = $client['password'];
                    if($clientcommon->save()){ // Если успешно создали нового клиента, то пишем данные о клиенте

                        $clientcommonid = $clientcommon->client_id;
                        
                        if(!empty($clientcommonid)){
                            $clientjur = new \common\models\ClientJurData();
                            $clientjur->client_id = $clientcommonid;
                            $clientjur->inn = $client['inn'];
                            $clientjur->title = $client['companyname'];
                            $clientjur->title_full = $client['companyname'];
                            $clientjur->director_fio = $client['contactname'];
                            if($clientjur->save()){ // Если успешно были записаны данные о клиенте
                                //$__SESSION['usere'] = $clientcommonid;
                                setcookie("usere", $clientcommonid, strtotime('+100 days'), '/');
                                setcookie('name', $client['companyname'], strtotime('+100 days'), '/');
            
                Yii::$app->session->setFlash('loginlog', "Вы успешно зарегестрированы");
                            }else{
                                Yii::$app->session->setFlash('danger', "Произошла ошибка при записи данных о клиенте");
                            }
                        }else{
                            Yii::$app->session->setFlash('danger', "Произошла ошибка при записи данных для авторизации2");
                        }
                    }else{
                        Yii::$app->session->setFlash('danger', "Произошла ошибка при записи данных для авторизации3");
                    }
                }
            }elseif($client['type']=='fiz'){
                if(!empty($_COOKIE['usere'])){// Если он уже авторизован
                    $clientcommona = ClientCommon::find()->where(['client_id' => $_COOKIE['usere']])->one();
                    $clientcommona->phone = $client['telephone'];
                    $clientcommona->email = $client['email'];
                    if ($clientcommona->save()) { // Если успешно перезаписаны данные для авторизации
                        $clientfiz = ClientFizData::find()
                        ->where(['client_id'=>$_COOKIE['usere']])
                        ->one();
                        $clientfiz->firstname = $client['contactname'];
                        if($clientfiz->save()){ // Если успешно перезаписаны данные о клиенте
                            $clientcommonid = $_COOKIE['usere'];
                Yii::$app->session->setFlash('success', "Информация успешно поменяна");
                        }else{
                            Yii::$app->session->setFlash('danger', "Произошла ошибка при перезаписи данных о клиенте");
                        }
                    }else{
                        Yii::$app->session->setFlash('danger', "Произошла ошибка при перезаписи данных для авторизации");
                        }
                }else{ // Если клиент еще не был авторизован, то создаем нового
                
                    $clientcommon = new \common\models\ClientCommon();
                    $clientcommon->client_type = 'fiz';
                    $clientcommon->phone = $client['telephone'];
                    $clientcommon->email = $client['email'];
                    $clientcommon->passwrd = $client['password'];
                    if($clientcommon->save()){ // Если успешно создали нового клиента, то пишем данные о клиенте

                        $clientcommonid = $clientcommon->client_id;
                        
                        if(!empty($clientcommonid)){
                            $clientfiz = new \common\models\ClientFizData();
                            $clientfiz->client_id = $clientcommonid;
                            $clientfiz->firstname = $client['contactname'];
                            if($clientfiz->save()){ // Если успешно были записаны данные о клиенте
                                //$__SESSION['usere'] = $clientcommonid;
                                setcookie("usere", $clientcommonid, strtotime('+100 days'), '/');
                                setcookie('name', $client['contactname'], strtotime('+100 days'), '/');
            
                Yii::$app->session->setFlash('loginlog', "Вы успешно зарегестрированы");
                            }else{
                                Yii::$app->session->setFlash('danger', "Произошла ошибка при записи данных о клиенте");
                            }
                        }else{
                            Yii::$app->session->setFlash('danger', "Произошла ошибка при записи данных для авторизации6");
                        }
                    }else{
                        Yii::$app->session->setFlash('danger', "Произошла ошибка при записи данных для авторизации4");
                    }
                }
            }else{
                Yii::$app->session->setFlash('danger', "Нет данных о типе клиента, повторите ввод данных в форму5");
            }
                if (\Yii::$app->request->referrer) {
                    return $this->redirect(Yii::$app->request->referrer);
                } else {
                    return $this->goHome();
                }
            
    }
    
    public function actionMailing(){
        
        $session = Yii::$app->session;
        $session->open();
        
        $test_mailing = MailingList::find()
        ->where(['ml_email'=>$_POST['email']])
        ->one();
        if(empty($test_mailing)){
            $mailing = new MailingList;
            $mailing->ml_email = $_POST['email'];
            $mailing->ml_creation_datetime =  new Expression('NOW()');
            $mailing->ml_ip = '8.8.8.8';
            $mailing->save();
            Yii::$app->session->setFlash('success', "Вы успешно подписались на рассылку!");
            
        }else{
            Yii::$app->session->setFlash('success', "Вы подписались на рассылку ранее!");
        }
                if (\Yii::$app->request->referrer) {
                    return $this->redirect(Yii::$app->request->referrer);
                } else {
                    return $this->goHome();
                }
    }
}
