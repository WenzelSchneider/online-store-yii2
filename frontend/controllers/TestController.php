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
use common\models\Price;
use common\models\PriceType;
use common\models\News;
use common\models\Groups;
use yii\data\Pagination;

/**
 * Site controller
 */
class TestController extends Controller
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
    public function actionIndex(){
        
                $models = new \yii\db\Query;
                $models->select('COUNT(*)')
                ->from('`goods`')
                ->leftJoin('`price`','`goods`.`good_id` = `price`.`good_id`')
                ->where(['>','`price`.`value`', 0]);
                $command = $models->createCommand();
                $model = $command->queryOne();
   // SELECT `goods`.`title`, `price`.`value` FROM `goods` JOIN `price` ON `goods`.`good_id` = `price`.`good_id`


        return $this->render('test',[
            'model' => $model,
        ]);
    }
}
