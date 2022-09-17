<?php
namespace common\models;

use Yii;
use yii\base\Model;

class Basket extends Model
{
    
    public function plusToBasket($id, $count = 1) {
        $session = Yii::$app->session;
        $session->open();
        $count = (int)$count;
        $id = abs((int)$id);
                $models = new \yii\db\Query;
                $models->select()
                ->from('`goods`')
                ->leftJoin('`price`','`goods`.`good_id` = `price`.`good_id`');
                $models->where(['=','`goods`.`good_id`', $id]);
                if(!empty($_SESSION['clientdat']['price_type'])){$data = $_SESSION['clientdat']['price_type'];}else{$data = 3;}
                if(!empty($_SESSION['clientdat']['price_type_ldsp'])){$data_ldsp = $_SESSION['clientdat']['price_type_ldsp'];}else{$data_ldsp = 11;}
                    $models->andWhere('price.type_id = '.$data.' OR price.type_id = '.$data_ldsp);
                $command = $models->createCommand();
                $product = $command->queryOne();
        if (empty($product)) {
            return;
        }
        if (!$session->has('basket')) {
            $session->set('basket', []);
            $basket = [];
        } else {
            $basket = $session->get('basket');
        }
        if (isset($basket['products'][$product['good_id']])) { // такой товар уже есть?
            $count = $basket['products'][$product['good_id']]['count'] + 1;
            $basket['products'][$product['good_id']]['count'] = $count;
        } else { // такого товара еще нет
            $basket['products'][$product['good_id']]['name'] = $product['title'];
            if($product['discount'] == 0){
                $basket['products'][$product['good_id']]['price'] = $product['value'];
            }else{
                $basket['products'][$product['good_id']]['price'] = round($product['value'] / 100 * (100 - $product['discount']), 2);
            }
            $basket['products'][$product['good_id']]['good_id'] = $product['good_id'];
            $basket['products'][$product['good_id']]['count'] = $count;
        }
        $amount = 0.0;
        foreach ($basket['products'] as $item) {
            $amount = $amount + $item['price'] * $item['count'];
        }
        $basket['amount'] = $amount;
        $session->set('basket', $basket);
    }
    
    public function minusToBasket($id, $count = 1) {
        $session = Yii::$app->session;
        $session->open();
        $count = (int)$count;
        $id = abs((int)$id);
                $models = new \yii\db\Query;
                $models->select()
                ->from('`goods`')
                ->leftJoin('`price`','`goods`.`good_id` = `price`.`good_id`');
                $models->where(['=','`goods`.`good_id`', $id]);
                if(!empty($_SESSION['clientdat']['price_type'])){$data = $_SESSION['clientdat']['price_type'];}else{$data = 3;}
                if(!empty($_SESSION['clientdat']['price_type_ldsp'])){$data_ldsp = $_SESSION['clientdat']['price_type_ldsp'];}else{$data_ldsp = 11;}
                    $models->andWhere('price.type_id = '.$data.' OR price.type_id = '.$data_ldsp);
                $command = $models->createCommand();
                $product = $command->queryOne();
        if (empty($product)) {
            return;
        }
        if (!$session->has('basket')) {
            $session->set('basket', []);
            $basket = [];
        } else {
            $basket = $session->get('basket');
        }
        if (isset($basket['products'][$product['good_id']])) { // такой товар уже есть?
            $count = $basket['products'][$product['good_id']]['count'] - 1;
            if ($count < 1) {
                $count = 1;
            }
            $basket['products'][$product['good_id']]['count'] = $count;
        } else { // такого товара еще нет
            $basket['products'][$product['good_id']]['name'] = $product['title'];
            if($product['discount'] == 0){
                $basket['products'][$product['good_id']]['price'] = $product['value'];
            }else{
                $basket['products'][$product['good_id']]['price'] = round($product['value'] / 100 * (100 - $product['discount']), 2);
            }
            $basket['products'][$product['good_id']]['good_id'] = $product['good_id'];
            $basket['products'][$product['good_id']]['count'] = $count;
        }
        $amount = 0.0;
        foreach ($basket['products'] as $item) {
            $amount = $amount + $item['price'] * $item['count'];
        }
        $basket['amount'] = $amount;
        $session->set('basket', $basket);
    }
    
    public function addToBasket($id, $count = 1) {
        $session = Yii::$app->session;
        $session->open();
        $count = (int)$count;
        if ($count <= 0) {
            $count = 1;
        }
        $id = abs((int)$id);
                $models = new \yii\db\Query;
                $models->select()
                ->from('`goods`')
                ->leftJoin('`price`','`goods`.`good_id` = `price`.`good_id`');
                $models->where(['=','`goods`.`good_id`', $id])
                ->andWhere(['!=','price.value', 0])
                ->andWhere(['!=','goods.in_stock', 0]);
                if(!empty($_SESSION['clientdat']['price_type'])){$data = $_SESSION['clientdat']['price_type'];}else{$data = 3;}
                if(!empty($_SESSION['clientdat']['price_type_ldsp'])){$data_ldsp = $_SESSION['clientdat']['price_type_ldsp'];}else{$data_ldsp = 11;}
                    $models->andWhere('price.type_id = '.$data.' OR price.type_id = '.$data_ldsp);
                $command = $models->createCommand();
                $product = $command->queryOne();
        if (empty($product)) {
            Yii::$app->session->setFlash('danger', "Данного товара нет на складе.");
            return false;
        }
        if (!$session->has('basket')) {
            $session->set('basket', []);
            $basket = [];
        } else {
            $basket = $session->get('basket');
        }
        if (isset($basket['products'][$product['good_id']])) { // такой товар уже есть
            $count = $basket['products'][$product['good_id']]['count'] + $count;
            $basket['products'][$product['good_id']]['count'] = $count;
        } else { // такого товара еще нет
            $basket['products'][$product['good_id']]['name'] = $product['title'];
            if($product['discount'] == 0){
                $basket['products'][$product['good_id']]['price'] = $product['value'];
            }else{
                $basket['products'][$product['good_id']]['price'] = round($product['value'] / 100 * (100 - $product['discount']), 2);
            }
            $basket['products'][$product['good_id']]['good_id'] = $product['good_id'];
            $basket['products'][$product['good_id']]['count'] = $count;
            $basket['products'][$product['good_id']]['articul'] = $product['articul'];
        }
        $amount = 0.0;
        foreach ($basket['products'] as $item) {
            $amount = $amount + $item['price'] * $item['count'];
        }
        $basket['amount'] = $amount;
        $session->set('basket', $basket);
    }
    
    public function removeFromBasket($id) {
        $id = abs((int)$id);
        $session = Yii::$app->session;
        $session->open();
        if (!$session->has('basket')) {
            return;
        }
        $basket = $session->get('basket');
        if (!isset($basket['products'][$id])) {
            return;
        }
        unset($basket['products'][$id]);
        if (count($basket['products']) == 0) {
            $session->set('basket', []);
            return;
        }
        $amount = 0.0;
        foreach ($basket['products'] as $item) {
            $amount = $amount + $item['price'] * $item['count'];
        }
        $basket['amount'] = $amount;

        $session->set('basket', $basket);
    }
    
    public function getBasket() {
        $session = Yii::$app->session;
        $session->open();
        if (!$session->has('basket')) {
            $session->set('basket', []);
            return [];
        } else {
            return $session->get('basket');
        }
    }
    
    public function clearBasket() {
        $session = Yii::$app->session;
        $session->open();
        $session->set('basket', []);
    }
    
    public function updateBasket($data) {
        $this->clearBasket();
        foreach ($data as $id => $count) {
            $this->addToBasket($id, $count['count']);
        }
    }
}
