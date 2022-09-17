<?php
namespace frontend\controllers;

use yii\web\Controller;
use common\models\Basket;
use common\models\BasketSystemLog;
use Yii;

class BasketController extends Controller {
    
    public function actionIndex() {

        return $this->redirect(['basket/basket']);
    }
    
    public function actionBasket() {
        $basket = (new Basket())->getBasket();

        return $this->render('index', ['basket' => $basket]);
    }

    public function actionAdd($id, $count = 1) {

        $session = Yii::$app->session;
        $session->open();
        $basket = new Basket();
        $data['id'] = $id;
        $data['count'] = $count;
        if (!isset($data['id'])) {
            return $this->redirect(['basket/index']);
        }
        if (!isset($data['count']) || $data['count']<1) {
            $data['count'] = 1;
        }
        // добавляем товар и получаем содержимое корзины
        $basket->addToBasket($data['id'], $data['count']);
        
        $basket_log = new BasketSystemLog;
        $basket_log->good_id = $data['id'];
        $basket_log->client_id = ($_COOCKIE['usere'])?$_COOCKIE['usere']:NULL;
        $basket_log->good_count = $data['count'];
        $basket_log->log_ip = $_SERVER['REMOTE_ADDR'];
        $basket_log->save();
        
        if (Yii::$app->request->isAjax) { // с использованием AJAX
            // layout-шаблон нам не нужен, только view-шаблон
            $this->layout = false;
            $content = $basket->getBasket();
            return $this->asJson($content);
        } else { // без использования AJAX
            return $this->redirect(['basket/index']);
        }
    }
    
    
    public function actionMinus($id, $count = 1){

        $basket = new Basket();

        if (!isset($count) || $count<1) {
            $count = 1;
        }
        $basket->minusToBasket($id, $count);
            $content = $basket->getBasket();
            
        return $count;
        
    }
    
    public function actionPlus($id, $count = 1){

        $basket = new Basket();

        if (!isset($count) || $count<1) {
            $count = 1;
        }
        
        $basket->plusToBasket($id, $count);
            $content = $basket->getBasket();
        return $count;
        
    }
    
    public function actionRemove($id) {
        $basket = new Basket();
        $basket->removeFromBasket($id);
        /*
         * Тут возможны две ситуации: пришел просто GET-запрос
         * или GET-запрос с использованием XmlHttpRequest
         */
        if (Yii::$app->request->isAjax) { // с использованием AJAX
            // layout-шаблон нам не нужен, только view-шаблон
            $content = $basket->getBasket();
            return $this->render('index', ['basket' => $content]);
        } else { // без использования AJAX
            return $this->redirect(['basket/basket']);
        }
    }
    
    public function actionUpdate($id, $count = 1) {
        
        $basket = new Basket();

        /*
         * Данные должны приходить методом POST; если это не
         * так — просто показываем корзину
         */
        if(!empty($id)){
            $session = Yii::$app->session;
            $session->open();
            $session->get('basket');
            
            if ($count<1) {
                $count = 1;
                Yii::$app->session->setFlash('danger', "Нельзя брать товара количеством меньше одного!");
            }
            $_SESSION['basket']['products'][$id]['count'] = $count;
            
            $basket = $_SESSION['basket'];
            foreach ($basket['products'] as $item) {
                $amount = $amount + $item['price'] * $item['count'];
            }
            $basket['amount'] = $amount;
            $session->set('basket', $basket);
            return $this->redirect(['basket/index/']);
        }

        return $this->redirect(['basket/index/']);
    }
    

    
    
    public function actionClear() {
        $basket = new Basket();
        $basket->clearBasket();
        /*
         * Тут возможны две ситуации: пришел просто GET-запрос
         * или GET-запрос с использованием XmlHttpRequest
         */
        if (Yii::$app->request->isAjax) { // с использованием AJAX
            // layout-шаблон нам не нужен, только view-шаблон
            $this->layout = false;
            $content = $basket->getBasket();
            return $this->redirect(['basket/index']);
        } else { // без использования AJAX
            return $this->redirect(['basket/index']);
        }
    }
    
    public function actionCount() {
        $session = Yii::$app->session;
        $session->open();
        $session->get('basket');
        
        return count($_SESSION['basket']['products']);
    }
    
    public function actionAmount() {
        $session = Yii::$app->session;
        $session->open();
        $session->get('basket');
        
        return $_SESSION['basket']['amount'];
    }
    

    
}