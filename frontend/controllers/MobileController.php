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
use common\models\Groups;
use common\models\GoodAssoc;
use common\models\GoodOptionValues;
use common\models\GoodOptions;
use yii\data\Pagination;

/**
 * Site controller
 */
class MobileController extends Controller
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
        $this->layout = 'main_mobile';
     
        $title = home;

        return $this->render('index');
    }
    
    public function actionItem($id)
    {
    	$session = Yii::$app->session;
    	$session->open();
        $this->layout = 'main_mobile';
            $good = Goods::find()
            ->where(['good_id' => $id])
            ->one();
            $options = GoodAssoc::find()
            ->where(['good_id' => $good['1c_ref']])
            ->all();
        $i=0;
        foreach ($options as $option) {
            $opt1 = GoodOptionValues::find()
            ->where(['1c_ref' => $option->opt_val_good])
            ->asArray()
            ->one();
            $opt2 = GoodOptions::find()
            ->where(['1c_ref' => $option->opt_id])
            ->asArray()
            ->one();
            
            $opti[$i]['option'] = $opt2['op_titile'];
            $opti[$i]['value'] = $opt1['value'];
            $i++;
        }
        if ($session->has('product'))$data = $session->get('product');
        $data[] = $id;
        $data = array_unique($data);
        $session->set('product', $data);
        
        
        return $this->render('item', [
            'good' => $good,
            'opti' => $opti,
            'data' => $data,
        ]);
    }
    
    public function actionCatalog($catid = 'none')
    {
    	
    	$session = Yii::$app->session;
    	$session->open();
        $this->layout = 'main_mobile';

         if (!$session->has('filter')) {
            $session->set('filter', []);
            $filter = [];
        } else {
            $filter = $session->get('filter');
        }

        // Проверка наличия категории в сессии
        	// Лучше сделать куками наверное
        if(!empty($filter['catid'])){
            if($filter['catid'] != $catid){
                unset($filter['checkbox']);
                unset($filter['summarea']);
                $filter['catid'] = $catid;
            }
        }else {
                $filter['catid'] = $catid;
        }
        // Наличие количества денег
        if(!empty($_POST['summarea'])){
            $filter['summarea'] = $_POST['summarea'];
        }elseif(!empty($_POST['formresponsed']) & empty($_POST['summarea'])) {
            unset($filter['summarea']);
        }
        // Наличие выбранных брендов
		if(!empty($_POST['checkbox']) || !empty($_GET['vendor'])){
		    
            if($_POST['checkbox']){
                $filter['checkbox'] = $_POST['checkbox'];
                unset($_GET['vendor']);
            }elseif($_GET['vendor']){
                $filter['checkbox'][] = $_GET['vendor'];
                unset($_GET['vendor']);
            }
        }elseif(!empty($_POST['formresponsed'])) {
            unset($filter['checkbox']);
        }
        $session->set('filter', $filter);
        // Запомнили сессию и можем узать
        
        if(!empty($_POST['orderbyinpt'])){
            $secondfilter['orderbyinpt'] = $_POST['orderbyinpt'];
        }elseif(!empty($_POST['formresponsed'])) {
            $secondfilter['orderbyinpt'] = 'cost';
        }
        
        
        if(!empty($_POST['countproductinpt'])){
            $secondfilter['countproductinpt'] = $_POST['countproductinpt'];
        }elseif(!empty($_POST['formresponsed'])) {
            $secondfilter['countproductinpt'] = 12;
        }
        
        $properch = $_POST['checkboxprop'];
        
        
							        $goodprope = GoodAssoc::find()
							        ->select('good_id')
							        ->where(['group_id'=>$catid])
                                    ->andFilterWhere(['IN', 'opt_val_good', $properch ])
							        ->orderBy('good_id')
							        ->asArray()
							        ->all();
							        
							        foreach($goodprope as $item){
							            $goodpropi[] = $item['good_id'];
							        }
							        
						            $count =  max(array_count_values($goodpropi));
						            for( $i=1; $i < $count ; $i++){
    							        $goodpropuniq = array_unique($goodpropi);
    							        $goodpropi = array_diff_key($goodpropi, $goodpropuniq);
    							        $goodprop = $goodpropi;
						            }
						            if($count = 1){
						                $goodpropuniq = array_unique($goodpropi);
						                $goodprop = $goodpropuniq;
						            }
        $orderli = $_POST['orderli'];
        if($_POST['is_new'] == 1){
        	$filterstate['is_new'] = [1,1];
        	$filterstates['is_new'] = 1;
        }else{
        	$filterstate['is_new'] = [0,1];
        	$filterstates['is_new'] = 0;
        }
        if($_POST['is_hit'] == 1){
        	$filterstate['is_hit'] = [1,1];
        	$filterstates['is_hit'] = 1;
        }else{
        	$filterstate['is_hit'] = [0,1];
        	$filterstates['is_hit'] = 0;
        }
        if($_POST['is_sale'] == 1){
        	$filterstate['is_sale'] = [1,1];
        	$filterstates['is_sale'] = 1;
        }else{
        	$filterstate['is_sale'] = [0,1];
        	$filterstates['is_sale'] = 0;
        }
        if($_POST['is_action'] == 1){
        	$filterstate['is_action'] = [1,1];
        	$filterstates['is_action'] = 1;
        }else{
        	$filterstate['is_action'] = [0,1];
        	$filterstates['is_action'] = 0;
        }
		
        
        if($catid=='none'){
            
    		$good = Goods::find()
                ->where(['>', 'cost', 0]);
                
            $min_price = $good
                ->min('cost');
            $max_price = $good
                ->max('cost');
            
            $brands = Goods::find()
                ->where(['>', 'cost', 0])
                ->select('vendor_name')
                ->distinct()
                ->all();

        	if(!empty($filter['summarea'])) {
            $summa = $filter['summarea'];
            $summ = explode(",", $summa);
            }
            if(!empty($filter['checkbox'])) {
                $brandch = $filter['checkbox'];
            }
    
            if(!empty($filter['summarea']) & !empty($filter['checkbox'])){
    
            	$count = Goods::find()
                ->where(['>', 'cost', 0])
                ->andFilterWhere(['>=', 'cost', $summ[0]])
                ->andFilterWhere(['<=', 'cost', $summ[1]])
                ->andFilterWhere(['IN', 'vendor_name', $brandch ])
                                    ->andFilterWhere(['IN', '1c_ref', $goodprop])
                                    ->andFilterWhere(['IN', 'is_new', $filterstate['is_new']])
                                    ->andFilterWhere(['IN', 'is_action', $filterstate['is_action']])
                ->andFilterWhere(['!=', 'is_hide', 1 ])
                ->andFilterWhere(['=', $filterstate['is_new'], $is_new ])
                ->andFilterWhere(['=', $filterstate['is_action'], $is_action ])
                ->count();
    
    			$pagination = new Pagination([
    	            'defaultPageSize' => 12,
    	            'totalCount' => $count,
    	            'pageSize' => $secondfilter['countproductinpt'],
    	        ]);
    
            	$query = Goods::find()
                ->where(['>', 'cost', 0])
                ->andFilterWhere(['>=', 'cost', $summ[0]])
                ->andFilterWhere(['<=', 'cost', $summ[1]])
                ->andFilterWhere(['IN', 'vendor_name', $brandch ])
                                    ->andFilterWhere(['IN', '1c_ref', $goodprop ])
                ->andFilterWhere(['!=', 'is_hide', 1 ])
                ->andFilterWhere(['=', $filterstate['is_new'], $is_new ])
                ->andFilterWhere(['=', $filterstate['is_action'], $is_action ])
                                    ->andFilterWhere(['IN', 'is_new', $filterstate['is_new']])
                                    ->andFilterWhere(['IN', 'is_action', $filterstate['is_action']])
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->orderBy($secondfilter['orderbyinpt'])
                ->all();
    
            }elseif(!empty($filter['summarea']) & empty($filter['checkbox'])){
    
            	$count = Goods::find()
                ->where(['>', 'cost', 0])
                ->andFilterWhere(['>=', 'cost', $summ[0]])
                ->andFilterWhere(['<=', 'cost', $summ[1]])
                ->andFilterWhere(['!=', 'is_hide', 1 ])
                ->andFilterWhere(['=', $filterstate['is_new'], $is_new ])
                ->andFilterWhere(['=', $filterstate['is_action'], $is_action ])
                                    ->andFilterWhere(['IN', '1c_ref', $goodprop ])
                                    ->andFilterWhere(['IN', 'is_new', $filterstate['is_new']])
                                    ->andFilterWhere(['IN', 'is_action', $filterstate['is_action']])
                ->count();
    
    			$pagination = new Pagination([
    	            'defaultPageSize' => 12,
    	            'totalCount' => $count,
    	            'pageSize' => $secondfilter['countproductinpt'],
    	        ]);
    
            	$query = Goods::find()
                ->where(['>', 'cost', 0])
                ->andFilterWhere(['>=', 'cost', $summ[0]])
                ->andFilterWhere(['<=', 'cost', $summ[1]])
                ->andFilterWhere(['!=', 'is_hide', 1 ])
                ->andFilterWhere(['=', $filterstate['is_new'], $is_new ])
                ->andFilterWhere(['=', $filterstate['is_action'], $is_action ])
                                    ->andFilterWhere(['IN', '1c_ref', $goodprop ])
                                    ->andFilterWhere(['IN', 'is_new', $filterstate['is_new']])
                                    ->andFilterWhere(['IN', 'is_action', $filterstate['is_action']])
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->orderBy($secondfilter['orderbyinpt'])
                ->all();
    
            }elseif(empty($filter['summarea']) & !empty($filter['checkbox'])){
    
            	$count = Goods::find()
                ->where(['>', 'cost', 0])
                ->andFilterWhere(['IN', 'vendor_name', $brandch ])
                ->andFilterWhere(['!=', 'is_hide', 1 ])
                ->andFilterWhere(['=', $filterstate['is_new'], $is_new ])
                ->andFilterWhere(['=', $filterstate['is_action'], $is_action ])
                                    ->andFilterWhere(['IN', '1c_ref', $goodprop ])
                                    ->andFilterWhere(['IN', 'is_new', $filterstate['is_new']])
                                    ->andFilterWhere(['IN', 'is_action', $filterstate['is_action']])
                ->count();
    
    			$pagination = new Pagination([
    	            'defaultPageSize' => 12,
    	            'totalCount' => $count,
    	            'pageSize' => $secondfilter['countproductinpt'],
    	        ]);
    
            	$query = Goods::find()
                ->where(['>', 'cost', 0])
                ->andFilterWhere(['IN', 'vendor_name', $brandch ])
                ->andFilterWhere(['!=', 'is_hide', 1 ])
                ->andFilterWhere(['=', $filterstate['is_new'], $is_new ])
                ->andFilterWhere(['=', $filterstate['is_action'], $is_action ])
                                    ->andFilterWhere(['IN', '1c_ref', $goodprop ])
                                    ->andFilterWhere(['IN', 'is_new', $filterstate['is_new']])
                                    ->andFilterWhere(['IN', 'is_action', $filterstate['is_action']])
                    ->limit($pagination->limit)
                    ->orderBy($secondfilter['orderbyinpt'])
                ->all();
    
            }elseif(empty($filter['summarea']) & empty($filter['checkbox'])){
    
            	$count = Goods::find()
                ->where(['>', 'cost', 0])
                ->andFilterWhere(['!=', 'is_hide', 1 ])
                ->andFilterWhere(['=', $filterstate['is_new'], $is_new ])
                ->andFilterWhere(['=', $filterstate['is_action'], $is_action ])
                                    ->andFilterWhere(['IN', '1c_ref', $goodprop ])
                                    ->andFilterWhere(['IN', 'is_new', $filterstate['is_new']])
                                    ->andFilterWhere(['IN', 'is_action', $filterstate['is_action']])
                ->count();
    
    			$pagination = new Pagination([
    	            'defaultPageSize' => 12,
    	            'totalCount' => $count,
    	            'pageSize' => $secondfilter['countproductinpt'],
    	        ]);
    
            	$query = Goods::find()
                ->where(['>', 'cost', 0])
                ->andFilterWhere(['!=', 'is_hide', 1 ])
                ->andFilterWhere(['=', $filterstate['is_new'], $is_new ])
                ->andFilterWhere(['=', $filterstate['is_action'], $is_action ])
                                    ->andFilterWhere(['IN', '1c_ref', $goodprop ])
                                    ->andFilterWhere(['IN', 'is_new', $filterstate['is_new']])
                                    ->andFilterWhere(['IN', 'is_action', $filterstate['is_action']])
                    ->orderBy($secondfilter['orderbyinpt'])
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->orderBy($secondfilter['orderbyinpt'])
                ->all();
    
            }
            
            $title['name'] = 'Каталог';
            
            return $this->render('catalog', [
            'goods' => $query,
            'pagination' => $pagination,
            'summval' =>$summa,
            'min_price' => $min_price,
            'max_price' => $max_price,
            'brands' => $brands,
            'brandch' => $brandch,
            'catid' => $catid,
            'title' => $title['name'],
            'properch' => $properch,
            'goodprop' => $goodprop,
            'filterstate' => $filterstate,
            'filterstates' => $filterstates,
            'orderli' => $orderli,
            'secondfilter' => $secondfilter,
        ]);
        }
		
		
		
		$good = Goods::find()
            ->where(['=', 'cat_id_1c', $catid])
            ->andFilterWhere(['>', 'cost', 0])
                                    ->andFilterWhere(['IN', '1c_ref', $goodprop ])
            ->andFilterWhere(['!=', 'is_hide', 1 ]);
            
        $min_price = $good
            ->min('cost');
        $max_price = $good
            ->max('cost');
            
        $brands = Goods::find()
            ->where(['=', 'cat_id_1c', $catid])
            ->andFilterWhere(['!=', 'is_hide', 1 ])
                                    ->andFilterWhere(['IN', '1c_ref', $goodprop ])
            ->andFilterWhere(['>', 'cost', 0])
            ->select('vendor_name')
            ->distinct()
            ->all();

        if(!empty($filter['summarea'])) {
            $summa = $filter['summarea'];
            $summ = explode(",", $summa);
        }
        if(!empty($filter['checkbox'])) {
            $brandch = $filter['checkbox'];
        }

        if(!empty($filter['summarea']) & !empty($filter['checkbox'])){

        	$count = Goods::find()
            ->where(['=', 'cat_id_1c', $catid])
            ->andFilterWhere(['>', 'cost', 0])
            ->andFilterWhere(['>=', 'cost', $summ[0]])
            ->andFilterWhere(['<=', 'cost', $summ[1]])
            ->andFilterWhere(['IN', 'vendor_name', $brandch ])
                                    ->andFilterWhere(['IN', '1c_ref', $goodprop ])
                                    ->andFilterWhere(['IN', 'is_new', $filterstate['is_new']])
                                    ->andFilterWhere(['IN', 'is_action', $filterstate['is_action']])
            ->andFilterWhere(['!=', 'is_hide', 1 ])
            ->andFilterWhere(['=', $filterstate['is_new'], $is_new ])
            ->andFilterWhere(['=', $filterstate['is_action'], $is_action ])
            ->count();

    			$pagination = new Pagination([
    	            'defaultPageSize' => 12,
    	            'totalCount' => $count,
    	            'pageSize' => $secondfilter['countproductinpt'],
    	        ]);

        	$query = Goods::find()
            ->where(['=', 'cat_id_1c', $catid])
            ->andFilterWhere(['>', 'cost', 0])
            ->andFilterWhere(['>=', 'cost', $summ[0]])
            ->andFilterWhere(['<=', 'cost', $summ[1]])
            ->andFilterWhere(['IN', 'vendor_name', $brandch ])
                                    ->andFilterWhere(['IN', '1c_ref', $goodprop ])
                                    ->andFilterWhere(['IN', 'is_new', $filterstate['is_new']])
                                    ->andFilterWhere(['IN', 'is_action', $filterstate['is_action']])
            ->andFilterWhere(['!=', 'is_hide', 1 ])
            ->andFilterWhere(['=', $filterstate['is_new'], $is_new ])
            ->andFilterWhere(['=', $filterstate['is_action'], $is_action ])
                    ->orderBy($secondfilter['orderbyinpt'])
                ->offset($pagination->offset)
                ->limit($pagination->limit)
            ->all();

        }elseif(!empty($filter['summarea']) & empty($filter['checkbox'])){

        	$count = Goods::find()
            ->where(['=', 'cat_id_1c', $catid])
            ->andFilterWhere(['>', 'cost', 0])
            ->andFilterWhere(['>=', 'cost', $summ[0]])
            ->andFilterWhere(['<=', 'cost', $summ[1]])
            ->andFilterWhere(['!=', 'is_hide', 1 ])
            ->andFilterWhere(['=', $filterstate['is_new'], $is_new ])
            ->andFilterWhere(['=', $filterstate['is_action'], $is_action ])
                                    ->andFilterWhere(['IN', '1c_ref', $goodprop ])
                                    ->andFilterWhere(['IN', 'is_new', $filterstate['is_new']])
                                    ->andFilterWhere(['IN', 'is_action', $filterstate['is_action']])
            ->count();

    			$pagination = new Pagination([
    	            'defaultPageSize' => 12,
    	            'totalCount' => $count,
    	            'pageSize' => $secondfilter['countproductinpt'],
    	        ]);

        	$query = Goods::find()
            ->where(['=', 'cat_id_1c', $catid])
            ->andFilterWhere(['>', 'cost', 0])
            ->andFilterWhere(['>=', 'cost', $summ[0]])
            ->andFilterWhere(['<=', 'cost', $summ[1]])
            ->andFilterWhere(['!=', 'is_hide', 1 ])
            ->andFilterWhere(['=', $filterstate['is_new'], $is_new ])
            ->andFilterWhere(['=', $filterstate['is_action'], $is_action ])
                                    ->andFilterWhere(['IN', '1c_ref', $goodprop ])
                                    ->andFilterWhere(['IN', 'is_new', $filterstate['is_new']])
                                    ->andFilterWhere(['IN', 'is_action', $filterstate['is_action']])
                    ->orderBy($secondfilter['orderbyinpt'])
                ->offset($pagination->offset)
                ->limit($pagination->limit)
            ->all();

        }elseif(empty($filter['summarea']) & !empty($filter['checkbox'])){

        	$count = Goods::find()
            ->where(['=', 'cat_id_1c', $catid])
            ->andFilterWhere(['>', 'cost', 0])
            ->andFilterWhere(['IN', 'vendor_name', $brandch ])
            ->andFilterWhere(['!=', 'is_hide', 1 ])
            ->andFilterWhere(['=', $filterstate['is_new'], $is_new ])
            ->andFilterWhere(['=', $filterstate['is_action'], $is_action ])
                                    ->andFilterWhere(['IN', '1c_ref', $goodprop ])
                                    ->andFilterWhere(['IN', 'is_new', $filterstate['is_new']])
                                    ->andFilterWhere(['IN', 'is_action', $filterstate['is_action']])
            ->count();

    			$pagination = new Pagination([
    	            'defaultPageSize' => 12,
    	            'totalCount' => $count,
    	            'pageSize' => $secondfilter['countproductinpt'],
    	        ]);

        	$query = Goods::find()
            ->where(['=', 'cat_id_1c', $catid])
            ->andFilterWhere(['>', 'cost', 0])
            ->andFilterWhere(['IN', 'vendor_name', $brandch ])
            ->andFilterWhere(['!=', 'is_hide', 1 ])
            ->andFilterWhere(['=', $filterstate['is_new'], $is_new ])
            ->andFilterWhere(['=', $filterstate['is_action'], $is_action ])
                                    ->andFilterWhere(['IN', '1c_ref', $goodprop])
                                    ->andFilterWhere(['IN', 'is_new', $filterstate['is_new']])
                                    ->andFilterWhere(['IN', 'is_action', $filterstate['is_action']])
                    ->orderBy($secondfilter['orderbyinpt'])
                ->offset($pagination->offset)
                ->limit($pagination->limit)
            ->all();

        }elseif(empty($filter['summarea']) & empty($filter['checkbox'])){

        	$count = Goods::find()
            ->where(['=', 'cat_id_1c', $catid])
            ->andFilterWhere(['>', 'cost', 0])
            ->andFilterWhere(['!=', 'is_hide', 1 ])
            ->andFilterWhere(['=', $filterstate['is_new'], $is_new ])
            ->andFilterWhere(['=', $filterstate['is_action'], $is_action ])
                                    ->andFilterWhere(['IN', '1c_ref', $goodprop ])
                                    ->andFilterWhere(['IN', 'is_new', $filterstate['is_new']])
                                    ->andFilterWhere(['IN', 'is_action', $filterstate['is_action']])
            ->count();

    			$pagination = new Pagination([
    	            'defaultPageSize' => 12,
    	            'totalCount' => $count,
    	            'pageSize' => $secondfilter['countproductinpt'],
    	        ]);

        	$query = Goods::find()
            ->where(['=', 'cat_id_1c', $catid])
            ->andFilterWhere(['>', 'cost', 0])
            ->andFilterWhere(['!=', 'is_hide', 1 ])
            ->andFilterWhere(['=', $filterstate['is_new'], $is_new ])
            ->andFilterWhere(['=', $filterstate['is_action'], $is_action ])
                                    ->andFilterWhere(['IN', '1c_ref', $goodprop ])
                                    ->andFilterWhere(['IN', 'is_new', $filterstate['is_new']])
                                    ->andFilterWhere(['IN', 'is_action', $filterstate['is_action']])
                    ->orderBy($secondfilter['orderbyinpt'])
                ->offset($pagination->offset)
                ->limit($pagination->limit)
            ->all();

        }
        $title = Groups::find()->select('name')->where(['accounting_id' => $catid])->one();
        return $this->render('catalog', [
            'goods' => $query,
            'pagination' => $pagination,
            'summval' =>$summa,
            'min_price' => $min_price,
            'max_price' => $max_price,
            'brands' => $brands,
            'brandch' => $brandch,
            'catid' => $catid,
            'title' => $title['name'],
            'properch' => $properch,
            'goodprop' => $goodprop,
            'filterstate' => $filterstate,
            'filterstates' => $filterstates,
            'orderli' => $orderli,
            'secondfilter' => $secondfilter,
        ]);
    }
    
}
