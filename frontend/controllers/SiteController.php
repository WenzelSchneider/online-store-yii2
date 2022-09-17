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
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\ClientCommon;
use common\models\ClientFizData;
use common\models\ClientJurData;
use common\models\Goods;
use common\models\News;
use common\models\Projects;
use common\models\Users;
use common\models\Groups;
use common\models\ContentJobs;
use common\models\Basket;
use common\models\Bestseller;
use common\models\ContentPages;
use yii\data\Pagination;
use yii\db\Expression;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'login'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            //'verbs' => [
            //    'class' => VerbFilter::className(),
            //    'actions' => [
            //        'logout' => ['post'],
            //    ],
            //],
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
        $session->set('tokenen','mlfoy');
        $this->layout = 'main';
     
        $title = home;
   
    	$news = News::find()
    	->where(['is_hide'=>0])
                    ->orderBy('new_date DESC')
                    ->limit(2)
    	->all(); 
    	
    	$cats = new \yii\db\Query;
    	$cats->select('`cat_id`, COUNT(`good_id`) as counte')
    	->from('goods')
    	->where('`is_hide` = 0 AND `sale` = 1 AND `in_stock` > 2')
    	->groupBy('`cat_id`');
        $cats_command = $cats->createCommand();
        $cats_result = $cats_command->queryAll();
        $arra = array();
        foreach($cats_result as $cate){
            if($cate['counte'] > 3){
                $arra[] = $cate['cat_id'];
            }
        }
        
        shuffle($arra);
    	
    	$models = new \yii\db\Query;
            $models->select('`goods`.`good_id`,`goods`.`sale`,`goods`.`title`, `goods`.`termin`, `goods`.`manufacturer_id`, `goods`.`brand`, `goods`.`cat_id`, `goods`.`description`, `goods`.`in_stock`, `goods`.`discount`, `price`.`value`, `price`.`type_id`,  MATCH (description,title) AGAINST ("' . $search . '" IN BOOLEAN MODE) AS score');
        $models->from('`goods`')
        ->leftJoin('`price`','`goods`.`good_id` = `price`.`good_id`')
        ->leftJoin('`good_assoc`','`goods`.`good_id` = `good_assoc`.`good_id`');
        $models->andWhere(['>','`price`.`value`', 0]);
        $models->andFilterWhere(['=', '`goods`.`is_hide`', 0])
        ->andWhere('`goods`.`cat_id` = 130 OR `goods`.`cat_id` = 154');
        if(!empty($_SESSION['clientdat']['price_type'])){$data = $_SESSION['clientdat']['price_type'];}else{$data = 3;}
        if(!empty($_SESSION['clientdat']['price_type_ldsp'])){$data_ldsp = $_SESSION['clientdat']['price_type_ldsp'];}else{$data_ldsp = 11;}
        $models->andWhere('price.type_id = '.$data.' OR price.type_id = '.$data_ldsp)
        ->andWhere('goods.sale = 1')
        ->limit(4)
        ->orderBy(new Expression('rand()'));
        //var_dump($models->prepare(\Yii::$app->db->queryBuilder)->createCommand()->rawSql);
        $command = $models->createCommand();
        $handy = $command->queryAll();
    	
    	
    	$models = new \yii\db\Query;
            $models->select('`goods`.`good_id`,`goods`.`sale`,`goods`.`title`, `goods`.`termin`, `goods`.`manufacturer_id`, `goods`.`brand`, `goods`.`cat_id`, `goods`.`description`, `goods`.`in_stock`, `goods`.`discount`, `price`.`value`, `price`.`type_id`,  MATCH (description,title) AGAINST ("' . $search . '" IN BOOLEAN MODE) AS score');
        $models->from('`goods`')
        ->leftJoin('`price`','`goods`.`good_id` = `price`.`good_id`')
        ->leftJoin('`good_assoc`','`goods`.`good_id` = `good_assoc`.`good_id`');
        $models->andWhere(['>','`price`.`value`', 0]);
        $models->andFilterWhere(['=', '`goods`.`is_hide`', 0])
        ->andWhere('`goods`.`cat_id` = 171');
        if(!empty($_SESSION['clientdat']['price_type'])){$data = $_SESSION['clientdat']['price_type'];}else{$data = 3;}
        if(!empty($_SESSION['clientdat']['price_type_ldsp'])){$data_ldsp = $_SESSION['clientdat']['price_type_ldsp'];}else{$data_ldsp = 11;}
        $models->andWhere('price.type_id = '.$data.' OR price.type_id = '.$data_ldsp)
        ->andWhere('goods.sale = 1')
        ->limit(4)
        ->orderBy(new Expression('rand()'));
        //var_dump($models->prepare(\Yii::$app->db->queryBuilder)->createCommand()->rawSql);
        $command = $models->createCommand();
        $hottery = $command->queryAll();
        
        return $this->render('index', [
            'news' => $news,
            'hottery' => $hottery,
            'goods' => $handy
        ]);
    }
    
    public function actionCallback()
    {
        
        
            $users = Users::find()
            ->where(['email_notification'=>1])
            ->all();
            foreach($users as $user){
            Yii::$app->mailer->compose()
                ->setFrom(['robot@ural-dekor.ru' => 'Урал Декор'])
                ->setTo($user->email)
            ->setSubject('Обратный звонок на номер')
            ->setTextBody()
            ->setHtmlBody($_POST['name'].' : '.$_POST['phone'])
            ->send();
            }
            
            
            Yii::$app->session->setFlash('alert', "Заявка успешно отправлена");
            
        if(preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]))
        {
                if (\Yii::$app->request->referrer) {
                    return $this->redirect(Yii::$app->request->referrer);
                } else {
                    return $this->goHome();
                }
        }else{
                if (\Yii::$app->request->referrer) {
                    return $this->redirect(Yii::$app->request->referrer);
                } else {
                    return $this->goHome();
                }
        }
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    
    public function actionLogine()
    {
        if(!empty($_POST['username']) & !empty($_POST['password'])){
            $client = ClientCommon::find()
            ->where(['email' => $_POST['username']])
            ->orWhere(['phone' => $_POST['username']])
            ->asArray()
            ->one();
            
            if($client['passwrd']==$_POST['password']){
                
        	$session = Yii::$app->session; 
        	$session->open();
                if (!$session->has('usere')) {
                    $session->set('usere', []);
                } else {
                    $user = $session->get('usere');
                }
                if (!$session->has('basket')) {
                    $session->set('basket', []);
                } else {
                    $baske = $session->get('basket');
                }
    
                if (!$session->has('name')) {
                    $session->set('name', []);
                } else {
                    $user = $session->get('name');
                }
                
                
                $cookies = Yii::$app->response->cookies;
                $cookies_req = Yii::$app->request->cookies;
                
                //if ($cookies_req->get('usere') !== null) {
                //    $user = $cookies_req->get('usere');
                //}else{
                //    $cookies->add(new \yii\web\Cookie([
                //        'name' => 'usere',
                //        'value' => '',
                //    ]));
                //}
                
                
                if($client['client_type'] == 'fiz'){
                    $clienta = ClientFizData::find()
                    ->select('firstname')
                    ->where(['client_id'=>$client['client_id']])
                    ->one();
                    $_SESSION['name'] = $clienta['firstname'];
                    setcookie('name', $clienta['firstname'], strtotime('+100 days'), '/');
                }elseif($client['client_type'] == 'jur'){
                    $clienta = ClientJurData::find()
                    ->select('title_full')
                    ->where(['client_id'=>$client['client_id']])
                    ->one();
                    $_SESSION['name'] = $clienta['title_full'];
                    setcookie('name', $clienta['title_full'], strtotime('+100 days'), '/');
                }else{
                    $session['name'] = 15;
                    setcookie('name', 15, strtotime('+100 days'), '/');
                }
                    $session['usere'] = $client['client_id'];
                    setcookie('usere', $client['client_id'], strtotime('+100 days'), '/');
                
                if($client['client_id']>0){
        		    $userdata = ClientCommon::find()
        		    ->where(['client_id' => $client['client_id']])
        		    ->asArray()
        		    ->one();
        			    $clientdat['type'] = $userdata['client_type'];
        				$clientdat['email'] = $userdata['email'];
        				$clientdat['numberphone'] = $userdata['phone'];
        				$clientdat['price_type'] = $userdata['price_type_id'];
                        setcookie('clientdat', $userdata['price_type_id'], strtotime('+100 days'), '/');
        				$clientdat['price_type_ldsp'] = $userdata['price_type_id_two'];
        		    if($userdata['client_type'] == 'jur'){
        		        $clientdata = ClientJurData::find()
        		        ->where(['client_id' => $client['client_id']])
        		        ->one();
        				$clientdat['namecompany'] = $clientdata['title_full'];
        				$clientdat['name'] = $clientdata['title_full'];
        				$clientdat['address_fact'] = $clientdata['address_fact'];
        				$clientdat['address_jur'] = $clientdata['address_jur'];
        				$clientdat['contactface'] = $clientdata['contactface'];
        				$clientdat['name_dir'] = $clientdata['director_fio'];
        				$clientdat['inn'] = $clientdata['inn'];
        				$clientdat['ogrn'] = $clientdata['ogrn'];
        				$clientdat['bank'] = $clientdata['bank'];
        		    }elseif($userdata['client_type'] == 'fiz'){
        		        $clientdata = ClientFizData::find()
        		        ->where(['client_id' => $client['client_id']])
        		        ->one();
        				$clientdat['name'] = $clientdata['firstname'];
        				$clientdat['contactface'] = $clientdata['firstname'];
        		    }
        	        $session->set('clientdat', $clientdat);
        	        
        	        foreach($clientdat as $key => $value){
                        setcookie("clientdat[".$key."]", $value, strtotime('+100 days'), '/');
        	        }
        		}else{
                    if (\Yii::$app->request->referrer) {
                        return $this->redirect(Yii::$app->request->referrer);
                    } else {
                        return $this->goHome();
                    }
        		}
            Yii::$app->session->setFlash('loginlog', "Добро пожаловать на сайт УралДекор");
            $basketu = new Basket();
            $basketu->updateBasket($baske['products']);
            }else{
            Yii::$app->session->setFlash('danger', "Были введены неверные данные: Логин или Пароль");
            }
            if (\Yii::$app->request->referrer) {
                    return $this->redirect(Yii::$app->request->referrer);
            } else {
                    return $this->goHome();
            }
        }else{
                if (\Yii::$app->request->referrer) {
                    return $this->redirect(Yii::$app->request->referrer);
                } else {
                    return $this->goHome();
                }
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogaut()
    {
        
        setcookie('usere', $client['client_id'], strtotime('-100 days'), '/');
        setcookie('name', 1, strtotime('-100 days'), '/');
        setcookie("clientdat['name']", 1, strtotime('-100 days'), '/');
        setcookie("clientdat['namecompany']", 1, strtotime('-100 days'), '/');
        setcookie("clientdat['type']", 1, strtotime('-100 days'), '/');
        setcookie("clientdat['email']", 1, strtotime('-100 days'), '/');
        setcookie("clientdat['price_type']", 1, strtotime('-100 days'), '/');
        setcookie("clientdat['price_type_ldsp']", 1, strtotime('-100 days'), '/');
        setcookie("clientdat['inn']", 1, strtotime('-100 days'), '/');
        setcookie("clientdat['ogrn']", 1, strtotime('-100 days'), '/');
        setcookie("clientdat['name_dir']", 1, strtotime('-100 days'), '/');
        setcookie("clientdat['numberphone']", 1, strtotime('-100 days'), '/');
        	        
    	$session = Yii::$app->session;
    	$session->open();
        UnSet($session['usere']);
        UnSet($session['order']);
        UnSet($session['clientdat']);
        UnSet($session['basket']);
            return $this->redirect(['category/']);
    }
    public function actionCategory()
    {
    	$session = Yii::$app->session;
    	$session->open();
        return $this->render('category');
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContacts()
    {
    	$session = Yii::$app->session;
    	$session->open();
            return $this->render('contacts', [
                'model' => $model,
            ]);
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
    public function actionAboutus()
    {
    	$session = Yii::$app->session;
    	$session->open();
        return $this->render('aboutus',['pagedata'=>$pagedata]);
    }
    
    public function actionNews()
    {
    	$session = Yii::$app->session;
    	$session->open();
    	$count = News::find()
    	->where(['is_hide'=>0])
    	->count();
    	
    	
    			$pagination = new Pagination([
    	            'defaultPageSize' => 12,
    	            'totalCount' => $count,
    	            'pageSize' => 15,
    	        ]);
                   
    	$news = News::find()
    	->where(['is_hide'=>0])
                    ->limit($pagination->limit)
                    ->orderBy('new_date DESC')
    	->all(); 
                    
        
        return $this->render('news',['news'=>$news, 'pagination'=>$pagination]);
    }
    
    public function actionNew($id)
    {
    	$session = Yii::$app->session;
    	$session->open();
    	$new = News::find()
    	->where(['is_hide'=>0])
    	->andFilterWhere(['new_id'=>$id])
    	->one();
    	$meta['keywords'] = $new->new_meta_keywords;
    	$meta['description'] = $new->new_meta_description;
    	
        return $this->render('new',['new'=>$new, 'meta'=>$meta, 'desc'=>$new->new_description]);
        
    }
    
    public function actionAjax()
    {
    	$session = Yii::$app->session;
    	$session->open();
    	$data['count'] = count($_SESSION['basket']['products']);
    	$data['amount'] = $_SESSION['basket']['amount'];
    	$data = json_encode($data);
        return $data;
    }
    
    
    public function actionProjects()
    {
    	$session = Yii::$app->session;
    	$session->open();
    	$count = Projects::find()
    	->where(['is_hide'=>0])
    	->count();
    	
    	
    			$pagination = new Pagination([
    	            'defaultPageSize' => 12,
    	            'totalCount' => $count,
    	            'pageSize' => 15,
    	        ]);
                   
    	$projects = Projects::find()
    	->where(['is_hide'=>0])
                    ->limit($pagination->limit)
    	->all(); 
                    
        
        return $this->render('projects',['projects'=>$projects, 'pagination'=>$pagination]);
    }
    
    public function actionProject($id)
    {
    	$session = Yii::$app->session;
    	$session->open();
    	$project = Projects::find()
    	->where(['is_hide'=>0])
    	->andFilterWhere(['project_id'=>$id])
    	->one();
    	$meta['keywords'] = $project->project_description;
    	$meta['description'] = $project->project_description;
    	
        return $this->render('project',['project'=>$project, 'meta'=>$meta, 'desc'=>$project->project_description]);
        
    }
    
    public function actionKitchenfronts(){
        return $this->render('kitchen_fronts');
    }
    public function actionDoordesign(){
    	return $this->render('door_design');
    }   
    
    public function actionCustomfurn(){
        return $this->render('custom_furn');
    }  
    public function actionServices(){
        return $this->render('service');
    }  
    
    public function actionFinifur(){
        return $this->render('finifur');
    }   
    
    public function actionSlidingwardrobes(){
        return $this->render('sliding_wardrobes');
    }
    public function actionBathroomfurn(){
    	return $this->render('bathroom_furn');
    }  
    public function actionCommercsawing(){
    	return $this->render('commerc_sawing');
    }  
    public function actionDelivery(){
    	return $this->render('delivery');
    }     
    public function actionKitchenfurn(){
    	return $this->render('kitchen_furn');
    }  
    public function actionWardrobes(){
    	return $this->render('wardrobes');
    }
    
    public function actionCostcalc(){
    	return $this->render('cost_calc');
    }       
    public function actionEdging(){
    	return $this->render('edging');
    }     
    public function actionQaz(){
    	return $this->render('qaz');
    }

 /* new pages */

    public function actionBazis(){
    	return $this->render('bazis');
    } 
    public function actionJob(){
    	return $this->render('job');
    }  
    public function actionCoupeDoors(){
    	return $this->render('coupe-doors');
    }  
}