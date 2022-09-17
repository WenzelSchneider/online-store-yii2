<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\Expression;

class Order extends Model
{
    
    public function completeOrder() {
        $session = Yii::$app->session;
        $session->open();
        if (!$session->has('order')) {
            $session->set('order', []);
        } else {
            $order = $session->get('order');
        }
        
            $ordersmine = new \common\models\OrdersMain();
            $model = new \common\models\OrderContents();
            $clientcommon = new \common\models\ClientCommon();
            
            $session->set('order', $order);
            $order = $session->get('order');
            $basket = $session->get('basket');
            $products = $basket['products'];
            
            if($order['type']=='jur'){
                if(!empty($_COOKIE['usere'])){// Если он уже авторизован
                    $clientcommona = ClientCommon::find()->where(['client_id' => $_COOKIE['usere']])->one();
                    $clientcommona->phone = $order['numberphone'];
                    $clientcommona->email = $order['email'];
                    $clientcommona->save();
                    if ($clientcommona->save()) { // Если успешно перезаписаны данные для авторизации
                        $clientjur = ClientJurData::find()
                        ->where(['client_id'=>$_COOKIE['usere']])
                        ->one();
                        $clientjur->inn = $order['inn'];
                        $clientjur->title = $order['namecompany'];
                        $clientjur->title_full = $order['namecompany'];
                        $clientjur->director_fio = $order['contactface'];
                        if($clientjur->save()){ // Если успешно перезаписаны данные о клиенте
                            $clientcommonid = $_COOKIE['usere'];
                //Yii::$app->session->setFlash('success', "Информация успешно поменяна");
                        }else{
                            Yii::$app->session->setFlash('danger', "Произошла ошибка при перезаписи данных о клиенте");
                        }
                    }else{
                        Yii::$app->session->setFlash('danger', "Произошла ошибка при перезаписи данных для авторизации1");
                        }
                }else{ // Если клиент еще не был авторизован, то создаем нового
                
                    $clientcommon = new \common\models\ClientCommon();
                    $clientcommon->client_type = 'jur';
                    $clientcommon->phone = $order['numberphone'];
                    $clientcommon->email = $order['email'];
                    $clientcommon->passwrd = $order['password'];
                    if($clientcommon->save()){ // Если успешно создали нового клиента, то пишем данные о клиенте

                        $clientcommonid = $clientcommon->client_id;
                        
                        if(!empty($clientcommonid)){
                            $clientjur = new \common\models\ClientJurData();
                            $clientjur->client_id = $clientcommonid;
                            $clientjur->inn = $order['inn'];
                            $clientjur->title = $order['namecompany'];
                            $clientjur->title_full = $order['namecompany'];
                            $clientjur->director_fio = $order['contactface'];
                            if($clientjur->save()){ // Если успешно были записаны данные о клиенте
                                $_SESSION['usere'] = $clientcommonid;
                                setcookie("usere", $clientcommonid, strtotime('+100 days'), '/');
                                setcookie('name', $order['companyname'], strtotime('+100 days'), '/');
            
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
            }elseif($order['type']=='fiz'){
                if(!empty($_COOKIE['usere'])){// Если он уже авторизован
                    $clientcommona = ClientCommon::find()->where(['client_id' => $_COOKIE['usere']])->one();
                    $clientcommona->phone = $order['numberphone'];
                    $clientcommona->email = $order['email'];
                    if ($clientcommona->save()) { // Если успешно перезаписаны данные для авторизации
                        $clientfiz = ClientFizData::find()
                        ->where(['client_id'=>$_COOKIE['usere']])
                        ->one();
                        $clientfiz->firstname = $order['name'];
                        if($clientfiz->save()){ // Если успешно перезаписаны данные о клиенте
                            $clientcommonid = $_COOKIE['usere'];
                //Yii::$app->session->setFlash('success', "Информация успешно поменяна");
                        }else{
                            Yii::$app->session->setFlash('danger', "Произошла ошибка при перезаписи данных о клиенте");
                        }
                    }else{
                        Yii::$app->session->setFlash('danger', "Произошла ошибка при перезаписи данных для авторизации");
                        }
                }else{ // Если клиент еще не был авторизован, то создаем нового
                
                    $clientcommon = new \common\models\ClientCommon();
                    $clientcommon->client_type = 'fiz';
                    $clientcommon->phone = $order['numberphone'];
                    $clientcommon->email = $order['email'];
                    $clientcommon->passwrd = $order['password'];
                    if($clientcommon->save()){ // Если успешно создали нового клиента, то пишем данные о клиенте

                        $clientcommonid = $clientcommon->client_id;
                        if(!empty($clientcommonid)){
                            $clientfiz = new \common\models\ClientFizData();
                            $clientfiz->client_id = $clientcommonid;
                            $clientfiz->firstname = $order['name'];
                            if($clientfiz->save()){ // Если успешно были записаны данные о клиенте
                                $_SESSION['usere'] = $clientcommonid;
                                setcookie("usere", $clientcommonid, strtotime('+100 days'), '/');
                                setcookie('name', $order['name'], strtotime('+100 days'), '/');
            
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
            //echo $clientcommonid;
            if(!empty($clientcommonid)){
                if(empty($order['address'])){
                    $order['address'] = 'Склад на Туркестанской 68';
                }
                if(empty($order['delivery'])){
                    $order['delivery'] = 0;
                }
                $ordersmine->need_for_delivery = $order['delivery'];
                $ordersmine->delivery_address = $order['address'];
                $ordersmine->delivery_comment = $order['comment'];
                $ordersmine->order_comment = $order['order_comment'];
                $ordersmine->order_summ = $basket['amount'];
                $ordersmine->ads_tracker = $_SESSION['ads_tracker'];
                $ordersmine->date_delivery = $order['datetime'];
                $ordersmine->updated_datetime = new Expression('NOW()');
                $ordersmine->client_id = $clientcommonid;
                if($ordersmine->save()){
                $ordersmineid = $ordersmine->order_id;
                $_SESSION['order']['orderid'] = $ordersmineid;
                
                    foreach ($products as $product_id => $product) {
                        
                        $model = new \common\models\OrderContents();
                        
                            $model->order_id = $ordersmineid;
                            $model->good_id =  $product_id;
                            $model->amount = $product['count'];
                            $model->sum_per_one = $product['price'];
                            $model->sum_total = $product['count'] * $product['price'];
                            $model->save();
                    }
                    
                }else{
                    Yii::$app->session->setFlash('danger', "Критический сбой записи заказа");
                    return 'false';
                }
            }else {
                Yii::$app->session->setFlash('danger', "Критический сбой yii");
            }
                if(!empty($ordersmineid)){
                 Yii::$app->session->setFlash('succes', "Ваш заказ отправлен оператору и будет обработан в ближайшее время. Оператор свяжется с Вами для подтверждения заказа.");
                    UnSet($_SESSION['basket']);
                    return 'true';
                }else{
                    Yii::$app->session->setFlash('danger', "Критический сбой проверки заказа! Не все данные заполнены!");
                    return 'false';
                };
            
            
            $ordersmineid = $ordersmine->findOne($id);
            Yii::$app->session->setFlash('danger', "Критический сбой! Не все данные заполнены!");
            return 'false';
    }
}
