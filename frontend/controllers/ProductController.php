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
use common\models\Goods;
use common\models\GoodCategories;
use common\models\GoodAssoc;
use common\models\GoodOptionValues;
use common\models\GoodOptions;
use common\models\Groups;
use common\models\CatGroupRels;
use common\models\Manufacturers;
use common\models\GoodReviews;
use common\models\GoodFavourites;
use yii\db\Expression;
use yii\data\Pagination;

/**
 * Product controller
 */
class ProductController extends Controller
{
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
    $geter = $_GET;

    //Определяем категория или группа или все товары
    $str = '';
    $strp ='';
    $strq =''; 
    if($geter['category'] != 0 && !empty($geter['category'])){
        $groups = CatGroupRels::find()
        ->select('group_id')
        ->where(['cat_id'=>$geter['category']])
        ->asArray()
        ->all();
        $catname = GoodCategories::find()
        ->where(['cat_id'=>$geter['category']])
        ->one();
        $ifbrand = Groups::find()
        ->select('brand')
        ->where('id = '.$groups[0]['group_id'])
        ->groupBy(['brand'])
        ->one();
        $branufacture = $ifbrand->brand;
        foreach($groups as $item){
            if($item == end($groups)) {
                $str .= '`goods`.`cat_id` = ' . $item['group_id'];
                $strp .= '`good_assoc`.`group_id` = ' . $item['group_id'];
                $strq .= '`goods`.`cat_id` = ' . $item['group_id'];
            }else {
                $str .= '`goods`.`cat_id` = ' . $item['group_id'] . ' OR ';
                $strp .= '`good_assoc`.`group_id` = ' . $item['group_id'] . ' OR ';
                $strq .= '`goods`.`cat_id` = ' . $item['group_id'] . ' OR ';
            }
        }
    }
    if($geter['catid'] != 0 && !empty($geter['catid'])){
        $cat = Groups::find()
        ->where(['id'=>$geter['catid']])
        ->one();
        $str .= '`goods`.`cat_id` = ' . $geter['catid'];
        $strp .= '`good_assoc`.`group_id` = ' . $geter['catid'];
        $strq .= '`goods`.`cat_id` = ' . $geter['catid'];
        $ifbrand = Groups::find()
        ->select('brand')
        ->where('id = '.$geter['catid'])
        ->groupBy(['brand'])
        ->one();
        $branufacture = $ifbrand->brand;
    }

    //Определяем есть ли поиск
    if(!empty($geter['q'])){
        $search = $geter['q'];
        if($search == '' || empty($search) || is_null($search)){
            $search = 'лдсп';
        }
        preg_match_all("/\d+/", $search, $matches);
        $digit = " OR description LIKE '-" . implode("' OR description LIKE '-", $matches[0]) . "'";
        
        $search = preg_replace('/[^\w\s]/u', ' ', $search);
        $search = preg_replace('/^([ ]+)|([ ]){2,}/m', '$2', $search);
                                
        $search = trim($search);
        if($search==''){
           $search = '+';
        }else{
            unset($filter['ven']);
        }
        $search = explode(" ", $search);
        $u = 0;
        foreach($search as $ser){
            if(mb_strlen(trim($ser)) > 4){
                $search[$u] = preg_replace("/(ы|ый|ой|ая|ое|ые|ому|а|о|у|е|ого|ему|и|ство|ых|ох|ия|ий|ь|я|он|ют|ат)$/ui",'',$ser);
                $search[$u] = ltrim($search[$u], '0');
            }
            $u++;
        }
        $search = implode("* ", $search) . "*";
    }

    //Определяем есть ли фильтры
    if(!empty($geter['min_price'])){
        $price['min'] = $geter['min_price'];
    }
    if(!empty($geter['max_price'])){
        $price['max'] = $geter['max_price'];
    }
    if(!empty($geter['brand'])){
        $brand_ch = $geter['brand'];
    }
    if(!empty($geter['property'])){
        $optioner = $geter['property'];
        $stroka = '( `goods`.`good_id` IN (SELECT `good_assoc`.`good_id` FROM `good_assoc` WHERE';
        $countopt = 0;
        foreach($optioner as $key=>$value){
            $stroka .= '(';
            $stroka .= '( `good_assoc`.`opt_id` = ' . $key . ') AND (';
                $countopt += 1;
            foreach($value as $prop){
                $stroka .= '( `good_assoc`.`opt_val_good` = ' . $prop . ')';
                if($prop != end($value)){
                    $stroka .= ' OR ';
                }else{
                    $stroka .= ')';
                }
            }
            if($value != end($optioner)){
                $stroka .= ') OR ';
            }else{
                $stroka .= ') GROUP BY `good_assoc`.`good_id` HAVING COUNT(*) = ' . $countopt . '))';
            }
        }
    }
    //var_dump($stroka);
    //Определяем параметры сортировки
    $obi = 'value';
    if(!empty($geter['sort'])){
        if($geter['sort'] == 'price'){
            $obi = 'value';
        }elseif($geter['sort'] == 'novelty'){
            $obi = 'CHAR_LENGTH(description) DESC, description DESC';
        }elseif($geter['sort'] == 'pricedesc'){
            $obi = 'value DESC';
        }else{
            $obi = 'value';
        }
    }elseif(!empty($search)){
        $obi = 'value, score DESC';
    }

    //Определяем параметры вывода
    $prod_per_page = 28;
    $title['name'] = 'Каталог' ;
    if(!empty($geter['q'])){
        $title['name'] = $geter['q'];
    } 
    if(!empty($geter['catid'])){
        $title['name'] = $cat->name;
    }
    if(!empty($geter['category'])){
        $title['name'] = $catname->cat_title;
    }

    //Запрос за брэндами
    if($branufacture != 'brand'){
        $brands = Goods::find()
        ->where($strq)
        ->andWhere(['is_hide'=>0])
        ->andWhere(['>','`manufacturer_id`', 1])
        ->select('manufacturer_id')
        ->groupBy('manufacturer_id')
        ->all();
    }else{
        $brands = Goods::find()
        ->where($strq)
        ->andWhere(['is_hide'=>0])
        ->select('brand')
        ->groupBy('brand')
        ->all();
    }

    //Запрос за опциями товаров
    if($strp != ''){
        $models = new \yii\db\Query;
        $models->select('`good_assoc`.*, `good_options`.`op_titile`, `good_option_values`.`value`')
        ->from('`good_assoc`')
        ->leftJoin('`good_options`','`good_assoc`.`opt_id` = `good_options`.`op_id`')
        ->leftJoin('`good_option_values`','`good_assoc`.`opt_val_good` = `good_option_values`.`rec_id`')
        ->andWhere($strp)
        ->andFilterWhere(['=', '`good_option_values`.`is_hide`', 0])
        ->andFilterWhere(['=', '`good_options`.`is_hide`', 0])
        ->andFilterWhere(['=', '`good_assoc`.`is_hide_o`', 0])
        ->andFilterWhere(['=', '`good_assoc`.`is_hide`', 0])
        ->groupBy('`good_assoc`.`opt_val_good`');
        $command = $models->createCommand();
        $properties = $command->queryAll();
    }

    //Запрос за пагинацией
    $models = new \yii\db\Query;
    $models->select('COUNT(DISTINCT(`goods`.`good_id`)) as kount, MAX(`price`.`value`) as maxprice, MIN(`price`.`value`) as minprice');
    $models->from('`goods`')
    ->leftJoin('`price`','`goods`.`good_id` = `price`.`good_id`')
    ->leftJoin('`good_assoc`','`goods`.`good_id` = `good_assoc`.`good_id`');
    $models->andWhere(['>','`price`.`value`', 0])
    ->andWhere($str);
    $models->andFilterWhere(['>=', '`price`.`value`', $price['min']])
    ->andFilterWhere(['<=', '`price`.`value`', $price['max']])
    ->andFilterWhere(['=', '`goods`.`is_hide`', 0]);
    if($_GET['sale'] == 'on'){
        $models->andFilterWhere(['=', '`goods`.`sale`', 1]);
    }
    if($branufacture == 'manuf'){$models->andFilterWhere(['IN', 'manufacturer_id', $brand_ch ]);}
        else{$models->andFilterWhere(['IN', 'brand', $brand_ch ]);}
    if(!empty($geter['property'])){$models->andWhere($stroka);}
    if(!empty($_COOKIE['clientdat']['price_type'])){$data = $_COOKIE['clientdat']['price_type'];}else{$data = 3;}
    if(!empty($_COOKIE['clientdat']['price_type_ldsp'])){$data_ldsp = $_COOKIE['clientdat']['price_type_ldsp'];}else{$data_ldsp = 11;}
    $models->andWhere( 'price.type_id = '.$data);
    if(!empty($search)){$models->andWhere(" MATCH (description,title) AGAINST ('$search' IN BOOLEAN MODE)" . $digit);}
    $command = $models->createCommand();
    $count = $command->queryOne();
    
    $pagination = new Pagination([
        'totalCount' => $count['kount'],
        'pageSize' => 28,
    ]);
    if(!empty($count['maxprice'])){
        $mapr = $count['maxprice'];
        $mipr = $count['minprice'];
        
    }
    
    //Запрос за товарами
    $models = new \yii\db\Query;
    if(!empty($search)){
        $models->select('`goods`.`good_id`,`goods`.`sale`,`goods`.`title`, `goods`.`termin`, `goods`.`manufacturer_id`, `goods`.`brand`, `goods`.`cat_id`, `goods`.`description`, `goods`.`in_stock`, `goods`.`discount`, `price`.`value`, `price`.`type_id`,  MATCH (description,title) AGAINST ("' . $search . '" IN BOOLEAN MODE) AS score');
    }else{
       $models->select('`goods`.`good_id`,`goods`.`sale`,`goods`.`title`, `goods`.`termin`, `goods`.`manufacturer_id`, `goods`.`brand`, `goods`.`cat_id`, `goods`.`description`, `goods`.`in_stock`, `goods`.`discount`, `price`.`value`, `price`.`type_id`'); 
    }
    $models->from('`goods`')
    ->leftJoin('`price`','`goods`.`good_id` = `price`.`good_id`')
    ->leftJoin('`good_assoc`','`goods`.`good_id` = `good_assoc`.`good_id`');
    $models->andWhere(['>','`price`.`value`', 0])
    ->andWhere($str);
    $models->andFilterWhere(['>=', '`price`.`value`', $price['min']])
    ->andFilterWhere(['<=', '`price`.`value`', $price['max']])
    ->andFilterWhere(['=', '`goods`.`is_hide`', 0]);
    if($_GET['sale'] == 'on'){
        $models->andFilterWhere(['=', '`goods`.`sale`', 1]);
    }
    if($branufacture == 'manuf'){$models->andFilterWhere(['IN', 'manufacturer_id', $brand_ch ]);}
        else{$models->andFilterWhere(['IN', 'brand', $brand_ch ]);}
    if(!empty($geter['property'])){$models->andWhere($stroka);}
        if(!empty($_COOKIE['clientdat']['price_type'])){$data = $_COOKIE['clientdat']['price_type'];}else{$data = 3;}
        if(!empty($_COOKIE['clientdat']['price_type_ldsp'])){$data_ldsp = $_COOKIE['clientdat']['price_type_ldsp'];}else{$data_ldsp = 11;}
    $models->andWhere( 'price.type_id = '.$data);
    if(!empty($search)){$models->andWhere(" MATCH (description,title) AGAINST ('$search' IN BOOLEAN MODE)" . $digit);}
    if(!empty($search)){$models->orderBy('score DESC');}else{$models->orderBy($obi);}
    $models->groupBy('`goods`.`good_id`')
    ->limit($pagination->limit)
    ->offset($pagination->offset);
    //var_dump($models->prepare(\Yii::$app->db->queryBuilder)->createCommand()->rawSql);
    $command = $models->createCommand();
    $query = $command->queryAll();
    //Формируем возврат
        $this->layout = 'main';
    return $this->render('index', [
        //'testdata' =>  $brandch,
        'goods' => $query,
        'pagination' => $pagination,
        'mipr' => $mipr,
        'mapr' => $mapr,
        'brands' => $brands,
        'groups'=> $groups,
        'title' => $title['name'],
        'cpt' => $title['title'],
        'properties' => $properties,
        'data_ldsp' => $data_ldsp,
    ]);
}

    
    public function actionProduct($id = 0)
    {
        $session = Yii::$app->session;
        $session->open();
        $models = new \yii\db\Query;
        $models->select()
        ->from('`goods`')
        ->leftJoin('`price`','`goods`.`good_id` = `price`.`good_id`');
        $models->where(['=','`goods`.`good_id`', $id]);
        if(!empty($_COOKIE['clientdat']['price_type'])){$data = $_COOKIE['clientdat']['price_type'];}else{$data = 3;}
        if(!empty($_COOKIE['clientdat']['price_type_ldsp'])){$data_ldsp = $_COOKIE['clientdat']['price_type_ldsp'];}else{$data_ldsp = 11;}
        $models->andWhere( 'price.type_id = '.$data);
        $models->andWhere('price.value > 0' );
    //var_dump($models->prepare(\Yii::$app->db->queryBuilder)->createCommand()->rawSql);
                $command = $models->createCommand();
                $good = $command->queryOne();
            $options = GoodAssoc::find()
            ->where(['good_id' => $good['good_id']])
            ->andWhere(['is_hide_o' => 0])
            ->all();
        $i=0;
        foreach ($options as $option) {
            $opt1 = GoodOptionValues::find()
            ->where(['rec_id' => $option->opt_val_good])
            ->andWhere(['is_hide' => 0])
            ->asArray()
            ->one();
            $opt2 = GoodOptions::find()
            ->where(['op_id' => $option->opt_id])
            ->andWhere(['is_hide' => 0])
            ->asArray()
            ->one();
            
            $opti[$i]['option'] = $opt2['op_titile'];
            $opti[$i]['value'] = $opt1['value'];
            $i++;
        }
        
        $reviews = GoodReviews::find()
        ->where(['good_id'=>$id])
        ->andWhere(['review_approved_admin'=>1])
        ->all();
        
        
        $models = new \yii\db\Query;
        $models->select()
        ->from('`goods`')
        ->leftJoin('`price`','`goods`.`good_id` = `price`.`good_id`')
        ->leftJoin('`related_goods`','`goods`.`good_id` = `related_goods`.`good_id_p`');
        $models->andWhere(['>','`price`.`value`', 0]);
        $models->andWhere(['=','`related_goods`.`good_id_f`', $good['good_id']]);
        $models->andFilterWhere(['=', '`goods`.`is_hide`', 0]);
        if($bra == 'manuf'){
            $models->andFilterWhere(['IN', 'manufacturer_id', $brandch ]);
        }else{
            $models->andFilterWhere(['IN', 'brand', $brandch ]);
        }
        if(!empty($_COOKIE['clientdat']['price_type'])){$data = $_COOKIE['clientdat']['price_type'];}else{$data = 3;}
        if(!empty($_COOKIE['clientdat']['price_type_ldsp'])){$data_ldsp = $_COOKIE['clientdat']['price_type_ldsp'];}else{$data_ldsp = 11;}
            $models->andWhere( 'price.type_id = '.$data);
        $models->orderBy('rand()')
        ->limit(4);
        $command = $models->createCommand();
        $related_goods = $command->queryAll();
        
        
            $models = new \yii\db\Query;
            $models->select()
            ->from('`goods`')
            ->leftJoin('`price`','`goods`.`good_id` = `price`.`good_id`');
            $models->andWhere(['>','`price`.`value`', 0]);
            $models->andFilterWhere(['=', '`goods`.`is_hide`', 0])
            ->andFilterWhere(['=', '`goods`.`cat_id`', $good['cat_id']]);
            if($bra == 'manuf'){
                $models->andFilterWhere(['IN', 'manufacturer_id', $brandch ]);
            }else{
                $models->andFilterWhere(['IN', 'brand', $brandch ]);
            }
            if(!empty($_COOKIE['clientdat']['price_type'])){$data = $_COOKIE['clientdat']['price_type'];}else{$data = 3;}
            if(!empty($_COOKIE['clientdat']['price_type_ldsp'])){$data_ldsp = $_COOKIE['clientdat']['price_type_ldsp'];}else{$data_ldsp = 11;}
                $models->andWhere( 'price.type_id = '.$data);
            $models->orderBy('rand()')
            ->limit(4);
            
            $command = $models->createCommand();
            $query = $command->queryAll();
        
        if ($session->has('product')){$data = $session->get('product');};
        $data = array();
        $data = $session['product'];
        $data[$id] = $id;
        $data = array_unique($data);
        $session->set('product', $data);
        
        if($good['is_hide'] == 1){
            if (\Yii::$app->request->referrer) {
                return $this->redirect(Yii::$app->request->referrer);
            } else {
                return $this->goHome();
            }
        }
            $this->layout = 'main';
        return $this->render('product', [
            'good' => $good,
            'opti' => $opti,
            'data' => $data,
            'reviews' => $reviews,
            'goods' => $query,
            'related_goods' => $related_goods
        ]);
    }

    
    public function actionFavor()
    {
        $session = Yii::$app->session;
        $session->open();

            $usid = $_POST['idu'];
            $gid = $_POST['idg'];
        
            $gf = GoodFavourites::find()
            ->where(['client_id'=>$usid])
            ->andWhere(['good_id'=>$gid])
            ->one();

        if(isset($gf->record_id))
        {
          if($gf->is_hide == 0){
            $gf->is_hide = 1;
            $gf->save();
            Yii::$app->session->setFlash('success', "Товар удалён из избранного");
          }else{
            $gf->is_hide = 0;
            $gf->save();
            Yii::$app->session->setFlash('success', "Товар добавлен в избранное");
          }
        }elseif($_COOKIE['usere'] == $_POST['idu'])
        {
            $gfe = new GoodFavourites();
            $gfe->good_id = $_POST['idg'];
            $gfe->client_id = $_POST['idu'];
            $gfe->add_datetime = new Expression('NOW()');
            $gfe->save();
            Yii::$app->session->setFlash('success', "Товар добавлен в избранное");
        }
        
         if (\Yii::$app->request->referrer) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->goHome();
        }
    }
    
    public function actionPasschange()
    {
        $session = Yii::$app->session;
        $session->open();
        if ($session->has('favorite'))$data = $session->get('favorite');
        $data[] = isset($_REQUEST['id']) ? (int) $_REQUEST['id'] : 0;;
        $data = array_unique($data);
        $session->set('product', $data);
    }
    
    public function actionVendor($vendor)
    {
        $session = Yii::$app->session;
        $session->open();
        if (!$session->has('filter')) {
            $session->set('filter', []);
        } else {
            $filter = $session->get('filter');
            unset($filter['checkbox']);
            unset($filter['summarea']);
        }
        $models = new \yii\db\Query;
        $models->select()
        ->from('`goods`')
        ->InnerJoin('`cat_group_rels`','`goods`.`cat_id` = `cat_group_rels`.`group_id`')
        ->where(['`goods`.manufacturer_id'=>$vendor]);
        $command = $models->createCommand();
        $query = $command->queryOne();
        
        $ven = Manufacturers::find()
        ->where(['manufacturer_id'=>$vendor])
        ->one();
        $filter['catid'] = 0;
        $filter['checkbox'][0] = $vendor;
        $filter['ven'] = $vendor;
        //print_r($filter);
        $session->set('filter', $filter);
        //print_r($query);
        Yii::$app->response->redirect(['product/vendori']);
    }
    
    public function actionReview()
    {
       
       if($_POST['text']){
            $gr = new GoodReviews();
            $gr->good_id = $_POST['idg'];
            $gr->client_id = $_POST['idu'];
            $gr->review_rate = $_POST['rate'];
            $gr->review_text = $_POST['text'];
            $gr->review_creation_datetime = new Expression('NOW()');
            $gr->save();
        
        }
        if (\Yii::$app->request->referrer) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->goHome();
        }
    }
}
