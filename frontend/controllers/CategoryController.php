<?php
namespace frontend\controllers;

use yii\web\Controller;
use common\models\Basket;
use Yii;

class CategoryController extends Controller {
    
    public function actionIndex($category = 0) {
    	$session = Yii::$app->session;
    	$session->open();
        return $this->render('index', ['cat' => $category]);
    }

    public function actionAdd() {

        $basket = new Basket();

        /*
         * Данные должны приходить методом POST; если это не
         * так — просто показываем корзину
         */
        if (!Yii::$app->request->isPost) {
            return $this->redirect(['basket/index']);
        }

        $data = Yii::$app->request->post();
        if (!isset($data['id'])) {
            return $this->redirect(['basket/index']);
        }
        if (!isset($data['count'])) {
            $data['count'] = 1;
        }

        // добавляем товар и получаем содержимое корзины
        $basket->addToBasket($data['id'], $data['count']);

        if (Yii::$app->request->isAjax) { // с использованием AJAX
            // layout-шаблон нам не нужен, только view-шаблон
            $this->layout = false;
            $content = $basket->getBasket();
            return $this->render('modal', ['basket' => $content]);
        } else { // без использования AJAX
            return $this->redirect(['basket/index']);
        }
    }
    
    public function actionMinus(){

        $basket = new Basket();
        if (!Yii::$app->request->isPost) {
            return $this->redirect(['basket/index']);
        }

        $data = Yii::$app->request->post();
        if (!isset($data['id'])) {
            return $this->redirect(['basket/index']);
        }
        if (!isset($data['count'])) {
            $data['count'] = 1;
        }
        
        $basket->minusToBasket($data['id'], $data['count']);
            $content = $basket->getBasket();
            return $this->render('index', ['basket' => $content]);
        
    }
    
    public function actionPlus(){

        $basket = new Basket();
        if (!Yii::$app->request->isPost) {
            return $this->redirect(['basket/index']);
        }

        $data = Yii::$app->request->post();
        if (!isset($data['id'])) {
            return $this->redirect(['basket/index']);
        }
        if (!isset($data['count'])) {
            $data['count'] = 1;
        }
        
        $basket->plusToBasket($data['id'], $data['count']);
            $content = $basket->getBasket();
            return $this->render('index', ['basket' => $content]);
        
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
            return $this->render('index', ['basket' => $content]);
        }
    }
    
    public function actionUpdate() {
        $basket = new Basket();

        /*
         * Данные должны приходить методом POST; если это не
         * так — просто показываем корзину
         */
        if (!Yii::$app->request->isPost) {
            return $this->redirect(['basket/index']);
        }

        $data = Yii::$app->request->post();
        if (!isset($data['count'])) {
            return $this->redirect(['basket/index']);
        }
        if (!is_array($data['count'])) {
            return $this->redirect(['basket/index']);
        }

        $basket->updateBasket($data);

        return $this->redirect(['basket/index']);
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
            return $this->render('index', ['basket' => $content]);
        } else { // без использования AJAX
            return $this->render('index', ['basket' => $content]);
        }
    }
    

    
}