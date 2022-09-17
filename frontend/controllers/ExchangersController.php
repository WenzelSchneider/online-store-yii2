<?php
namespace frontend\controllers;

use yii\web\Controller;
use common\models\Order;
use common\models\OrdersMain;
use common\models\OrderContents;
use common\models\ClientCommon;
use common\models\ClientJurData;
use common\models\ClientFizData;
use common\models\Goods;
use common\models\Groups;
use common\models\GoodAssoc;
use common\models\GoodImages;
use common\models\GoodOptions;
use common\models\GoodOptionValues;
use common\models\Manufacturers;
use common\models\Basket;
use common\models\Price;
use common\models\PriceType;
use common\models\Owner;
use common\models\Users;
use common\models\Stock;
use common\models\GoodBalance;
use common\models\LogErrors;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use Yii;

class ExchangersController extends Controller
{

    public function postRequest($url, $data, $refer = "", $timeout = 10, $header = [])
    {
        $curlObj = curl_init();
        $ssl = stripos($url, 'https://') === 0 ? true : false;
        $options = [CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => 1, CURLOPT_POST => 1, CURLOPT_POSTFIELDS => $data, CURLOPT_FOLLOWLOCATION => 1, CURLOPT_AUTOREFERER => 1, CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)', CURLOPT_TIMEOUT => $timeout, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0, CURLOPT_HTTPHEADER => ['Expect:'], CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4, CURLOPT_REFERER => $refer];
        if (!empty($header))
        {
            $options[CURLOPT_HTTPHEADER] = $header;
        }
        if ($refer)
        {
            $options[CURLOPT_REFERER] = $refer;
        }
        if ($ssl)
        {
            //support https
            $options[CURLOPT_SSL_VERIFYHOST] = false;
            $options[CURLOPT_SSL_VERIFYPEER] = false;
        }
        curl_setopt_array($curlObj, $options);
        $returnData = curl_exec($curlObj);
        if (curl_errno($curlObj))
        {
            //error message
            $returnData = curl_error($curlObj);
        }
        curl_close($curlObj);
        return $returnData;
    }

    public function actionIndex()
    {
        $request = Yii::$app->request;
        Yii::$app
            ->controller->enableCsrfValidation = false;

        switch ($request->get('mode'))
        {
            case 'checkauth':
                echo "success\n";
                echo session_name() . "\n";
                echo "o9qm1a8a65cho1kksn31ps20i0\n";
                exit;

            case 'init':
                $zip = extension_loaded('zip') ? 'yes' : 'no';
                echo 'zip=' . $zip . "\n";
                echo "file_limit=0\n";

                exit;

            case 'file':
                echo "success\n";
                exit;

            case 'import':
                echo "success\n";
                exit;

            case 'complete':
                echo "success\n";
                exit;

            case 'success':
                echo "success\n";
                exit;

            case 'query':
                // открываем файл, если файл не существует,
                //делается попытка создать его
                $fp = fopen("order.xml", "w");
                fwrite($fp, '<?xml version="1.0" encoding="UTF-8"?>');
                fwrite($fp, '<КоммерческаяИнформация ВерсияСхемы="2.03" ДатаФормирования="' . date("d-m-Y") . '">' . PHP_EOL);
                $ordersMain = OrdersMain::find()->where(['sync_furn' => 0])
                    ->all();

                $osarr = ([1 => 'ПЛИТ', 2 => 'ФУРН']);
                foreach ($ordersMain as $item)
                {
                    foreach ($osarr AS $osid => $osname)
                    {
                        $cli = ClientCommon::find()->where(['client_id' => $item["client_id"]])->one();
                        $mami = 0;
                        if ($cli->client_type == "jur")
                        {
                            if ($cli->client_approved == 1)
                            {
                                $mami = 1;
                            }
                        }
                        else
                        {
                            $mami = 1;
                        }
                        if ($mami == 1)
                        {
                            if (!empty($item))
                            { ///////////////////////////////////////
                                $testContent = new \yii\db\Query;
                                $testContent->select('`goods`.`good_id`')
                                    ->from('`order_contents`')
                                    ->innerJoin('`goods`', '`order_contents`.`good_id` = `goods`.`good_id`')
                                    ->where(['=', '`goods`.`osn`', $osid])->andFilterWhere(['`order_contents`.`order_id`' => $item["order_id"]]);
                                $command = $testContent->createCommand();
                                $orderContent = $command->queryAll();
                                if (count($orderContent) > 0)
                                { /////////////////////////////////////////
                                    $clientCommon = ClientCommon::find()->where(['client_id' => $item["client_id"]])->one();
                                    if ($clientCommon['client_type'] == "jur")
                                    {
                                        $clientJurData = ClientJurData::find()->where(['client_id' => $clientCommon["client_id"]])->one();
                                        $clientData["id"] = $clientJurData["client_id"];
                                        $clientData["naming"] = $clientJurData["title"];
                                        $clientData["fullnaming"] = $clientJurData["title_full"];
                                        $clientData["secondname"] = $clientJurData["director_fio"];
                                        $clientData["name"] = $clientJurData["director_fio"];
                                        $clientData["address"] = $clientJurData["address_jur"];
                                        $clientData["represid"] = $clientJurData["client_id"];
                                        $clientData["represfullname"] = $clientJurData["director_fio"];
                                    }
                                    elseif ($clientCommon['client_type'] == "fiz")
                                    {
                                        $clientFizData = ClientFizData::find()->where(['client_id' => $clientCommon["client_id"]])->one();
                                        $clientData["id"] = $clientFizData["client_id"];
                                        $clientData["naming"] = $clientFizData["lastname"] . ' ' . $clientFizData["firstname"];
                                        $clientData["fullnaming"] = $clientFizData["lastname"] . ' ' . $clientFizData["firstname"];
                                        $clientData["secondname"] = $clientFizData["lastname"] . ' ' . $clientFizData["firstname"];
                                        $clientData["name"] = $clientFizData["firstname"];
                                        $clientData["address"] = "0";
                                        $clientData["represid"] = $clientFizData["client_id"];
                                        $clientData["represfullname"] = $clientFizData["firstname"] . ' ' . $clientFizData["lastname"];
                                    }
                                    fwrite($fp, '<Документ>' . PHP_EOL);
                                    fwrite($fp, '<Ид>' . $item["order_id"] . '</Ид>' . PHP_EOL);
                                    fwrite($fp, '<Номер>' . $osname . $item["order_id"] . '</Номер>' . PHP_EOL);
                                    fwrite($fp, '<Дата>' . $item["created_datetime"] . '</Дата>' . PHP_EOL);
                                    fwrite($fp, '<ХозОперация>Заказ товара</ХозОперация>' . PHP_EOL);
                                    fwrite($fp, '<Роль>Продавец</Роль>' . PHP_EOL);
                                    fwrite($fp, '<Валюта>руб</Валюта>' . PHP_EOL);
                                    fwrite($fp, '<Курс>1</Курс>' . PHP_EOL);
                                    fwrite($fp, '<Сумма>' . $item["order_summ"] . '</Сумма>' . PHP_EOL);
                                    fwrite($fp, '<Контрагенты>' . PHP_EOL);
                                    fwrite($fp, '<Контрагент>' . PHP_EOL);
                                    fwrite($fp, '<Ид>' . $clientData["id"] . '</Ид>' . PHP_EOL);
                                    fwrite($fp, '<Наименование>' . $clientData["naming"] . '</Наименование>' . PHP_EOL);
                                    fwrite($fp, '<КлиСсылка>' . $clientCommon["osid"] . '</КлиСсылка>' . PHP_EOL);

                                    if ($clientCommon['client_type'] == "jur")
                                    {
                                        fwrite($fp, '<ОфициальноеНаименование>' . $clientData["naming"] . '</ОфициальноеНаименование>' . PHP_EOL);
                                        fwrite($fp, '<ЮридическийАдрес>' . $clientData["address"] . '</ЮридическийАдрес>' . PHP_EOL);
                                        fwrite($fp, '<Роль>Покупатель</Роль>' . PHP_EOL);
                                    }
                                    else
                                    {
                                        fwrite($fp, '<Роль>Покупатель</Роль>' . PHP_EOL);
                                        fwrite($fp, '<ПолноеНаименование>' . $clientData["fullnaming"] . '</ПолноеНаименование>' . PHP_EOL);
                                        fwrite($fp, '<Фамилия>' . $clientData["secondname"] . '</Фамилия>' . PHP_EOL);
                                        fwrite($fp, '<Имя>' . $clientData["name"] . '</Имя>' . PHP_EOL);
                                    }
                                    fwrite($fp, '<АдресРегистрации>' . PHP_EOL);
                                    fwrite($fp, '<Представление>ггг</Представление>' . PHP_EOL);
                                    fwrite($fp, '<АдресноеПоле>' . PHP_EOL);
                                    fwrite($fp, '<Тип>Почтовый индекс</Тип>' . PHP_EOL);
                                    fwrite($fp, '<Значение>460000</Значение>' . PHP_EOL);
                                    fwrite($fp, '</АдресноеПоле>' . PHP_EOL);
                                    fwrite($fp, '<АдресноеПоле>' . PHP_EOL);
                                    fwrite($fp, '<Тип>Улица</Тип>' . PHP_EOL);
                                    fwrite($fp, '<Значение>' . $clientData["address"] . '</Значение>' . PHP_EOL);
                                    fwrite($fp, '</АдресноеПоле>' . PHP_EOL);
                                    fwrite($fp, '</АдресРегистрации>' . PHP_EOL);

                                    fwrite($fp, '<Контакты>' . PHP_EOL);
                                    fwrite($fp, '<Контакт>' . PHP_EOL);
                                    fwrite($fp, '<Тип>ТелефонМобильный</Тип>' . PHP_EOL);
                                    fwrite($fp, '<Значение>' . $clientCommon["phone"] . '</Значение>' . PHP_EOL);
                                    fwrite($fp, '</Контакт>' . PHP_EOL);
                                    fwrite($fp, '<Контакт>' . PHP_EOL);
                                    fwrite($fp, '<Тип>Почта</Тип>' . PHP_EOL);
                                    fwrite($fp, '<Значение>' . $clientCommon["email"] . '</Значение>' . PHP_EOL);
                                    fwrite($fp, '</Контакт>' . PHP_EOL);
                                    fwrite($fp, '</Контакты>' . PHP_EOL);

                                    fwrite($fp, '<Представители>' . PHP_EOL);
                                    fwrite($fp, '<Представитель>' . PHP_EOL);
                                    fwrite($fp, '<Контрагент>' . PHP_EOL);
                                    fwrite($fp, '<Отношение>Контактное лицо</Отношение>' . PHP_EOL);
                                    fwrite($fp, '<Ид>' . $clientData["represid"] . '</Ид>' . PHP_EOL);
                                    fwrite($fp, '<Наименование>' . $clientData["represfullname"] . '</Наименование>' . PHP_EOL);
                                    fwrite($fp, '</Контрагент>' . PHP_EOL);
                                    fwrite($fp, '</Представитель>' . PHP_EOL);
                                    fwrite($fp, '</Представители>' . PHP_EOL);
                                    fwrite($fp, '</Контрагент>' . PHP_EOL);
                                    fwrite($fp, '</Контрагенты>' . PHP_EOL);

                                    fwrite($fp, '<Время>' . date("h:i:s") . '</Время>' . PHP_EOL);
                                    if ($item['need_for_delivery'] == 1)
                                    {
                                        $deliv = 'Необходима доставка: ';
                                    }
                                    else
                                    {
                                        $deliv = ' ';
                                    }
                                    if (!empty($item['delivery_comment']))
                                    {
                                        $comment = ' Комментарий клиента:' . $item['delivery_comment'];
                                    }
                                    else
                                    {
                                        $comment = ' ';
                                    }
                                    fwrite($fp, '<Комментарий>' . $deliv . $item["delivery_address"] . ',' . $item["date_delivery"] . $comment . ' КодСайт: ' . $item["order_id"] . ' Контактные данные' . $clientCommon["phone"] . '</Комментарий>' . PHP_EOL);
                                    fwrite($fp, '<Налоги>
                        			<Налог>
                        				<Наименование>НДС</Наименование>
                        				<УчтеноВСумме>true</УчтеноВСумме>
                        				<Сумма>0</Сумма>
                        			</Налог>
                        		</Налоги>');
                                    fwrite($fp, '<Товары>' . PHP_EOL);

                                    $orderContents = OrderContents::find()->where(['order_id' => $item["order_id"]])->all();

                                    foreach ($orderContents as $product)
                                    {

                                        $productitem = Goods::find()->where(['good_id' => $product["good_id"]])->andWhere(['=', '`goods`.`osn`', $osid])->one();
                                        $cos = Price::find()->where(['good_id' => $product["good_id"]])->andWhere(['type_id' => 3])
                                            ->one();
                                        $cost = $cos->value;
                                        $categor = Groups::find()->select('accounting_id')
                                            ->where(['id' => $productitem["cat_id"]])->one();

                                        if (!empty($productitem["title"]))
                                        {
                                            fwrite($fp, '<Товар>' . PHP_EOL);
                                            fwrite($fp, '<Ид>' . $productitem["oc_ref"] . '</Ид>' . PHP_EOL);
                                            fwrite($fp, '<ИдКаталога>' . $categor->accounting_id . '</ИдКаталога>' . PHP_EOL);
                                            fwrite($fp, '<Наименование>' . $productitem["title"] . '</Наименование>' . PHP_EOL);
                                            fwrite($fp, '<БазоваяЕдиница Код="796" НаименованиеПолное="Штука" МеждународноеСокращение="PCE">шт</БазоваяЕдиница>' . PHP_EOL);
                                            fwrite($fp, '<ЦенаЗаЕдиницу>' . $product["sum_per_one"] . '</ЦенаЗаЕдиницу>' . PHP_EOL);
                                            fwrite($fp, '<Количество>' . $product["amount"] . '</Количество>' . PHP_EOL);
                                            fwrite($fp, '<Сумма>' . $product["sum_total"] . '</Сумма>' . PHP_EOL);
                                            fwrite($fp, '<ЗначенияРеквизитов>' . PHP_EOL);
                                            fwrite($fp, '<ЗначениеРеквизита>' . PHP_EOL);
                                            fwrite($fp, '<Наименование>ВидНоменклатуры</Наименование>' . PHP_EOL);
                                            fwrite($fp, '<Значение>Товар</Значение>' . PHP_EOL);
                                            fwrite($fp, '</ЗначениеРеквизита>' . PHP_EOL);
                                            fwrite($fp, '<ЗначениеРеквизита>' . PHP_EOL);
                                            fwrite($fp, '<Наименование>ТипНоменклатуры</Наименование>' . PHP_EOL);
                                            fwrite($fp, '<Значение>Товар</Значение>' . PHP_EOL);
                                            fwrite($fp, '</ЗначениеРеквизита>' . PHP_EOL);
                                            fwrite($fp, '</ЗначенияРеквизитов>' . PHP_EOL);
                                            if ($productitem['discount'] > 0)
                                            {
                                                fwrite($fp, '<Скидки>' . PHP_EOL);
                                                fwrite($fp, '<Скидка>' . PHP_EOL);
                                                fwrite($fp, '<Наименование>' . $productitem["discount"] . '</Наименование>' . PHP_EOL);
                                                fwrite($fp, '<Сумма>' . $product["sum_total"] / (100 - $productitem["discount"]) * $productitem["discount"] . '</Сумма>' . PHP_EOL);
                                                fwrite($fp, '<Процент>' . $productitem["discount"] . '</Процент>' . PHP_EOL);
                                                fwrite($fp, '<УчтеноВСумме>true</УчтеноВСумме>' . PHP_EOL);
                                                fwrite($fp, '</Скидка>' . PHP_EOL);
                                                fwrite($fp, '</Скидки>' . PHP_EOL);
                                            }
                                            fwrite($fp, '</Товар>' . PHP_EOL);
                                        }
                                    }

                                    fwrite($fp, '</Товары>' . PHP_EOL);

                                    fwrite($fp, '<ЗначенияРеквизитов>' . PHP_EOL);
                                    fwrite($fp, '<ЗначениеРеквизита>' . PHP_EOL);
                                    fwrite($fp, '<Наименование>Метод оплаты</Наименование>' . PHP_EOL);
                                    fwrite($fp, '<Значение>Налоговый вычет</Значение>' . PHP_EOL);
                                    fwrite($fp, '</ЗначениеРеквизита>' . PHP_EOL);
                                    fwrite($fp, '<ЗначениеРеквизита>' . PHP_EOL);
                                    fwrite($fp, '<Наименование>Заказ оплачен</Наименование>' . PHP_EOL);
                                    fwrite($fp, '<Значение>true</Значение>' . PHP_EOL);
                                    fwrite($fp, '</ЗначениеРеквизита>' . PHP_EOL);
                                    fwrite($fp, '<ЗначениеРеквизита>' . PHP_EOL);
                                    fwrite($fp, '<Наименование>Доставка разрешена</Наименование>' . PHP_EOL);
                                    fwrite($fp, '<Значение>true</Значение>' . PHP_EOL);
                                    fwrite($fp, '</ЗначениеРеквизита>' . PHP_EOL);
                                    fwrite($fp, '<ЗначениеРеквизита>' . PHP_EOL);
                                    fwrite($fp, '<Наименование>Отменен</Наименование>' . PHP_EOL);
                                    fwrite($fp, '<Значение>false</Значение>' . PHP_EOL);
                                    fwrite($fp, '</ЗначениеРеквизита>' . PHP_EOL);
                                    fwrite($fp, '<ЗначениеРеквизита>' . PHP_EOL);
                                    fwrite($fp, '<Наименование>Адрес доставки для печати</Наименование>' . PHP_EOL);
                                    fwrite($fp, '<Значение>' . $item['delivery_address'] . '</Значение>' . PHP_EOL);
                                    fwrite($fp, '</ЗначениеРеквизита>' . PHP_EOL);
                                    fwrite($fp, '<ЗначениеРеквизита>' . PHP_EOL);
                                    fwrite($fp, '<Наименование>Комментарий к заказу</Наименование>' . PHP_EOL);
                                    fwrite($fp, '<Значение>Коммент ' . $item['delivery_address'] . ' </Значение>' . PHP_EOL);
                                    fwrite($fp, '</ЗначениеРеквизита>' . PHP_EOL);
                                    fwrite($fp, '<ЗначениеРеквизита>' . PHP_EOL);
                                    fwrite($fp, '<Наименование>Финальный статус</Наименование>' . PHP_EOL);
                                    fwrite($fp, '<Значение>false</Значение>');
                                    fwrite($fp, '</ЗначениеРеквизита>' . PHP_EOL);
                                    fwrite($fp, '<ЗначениеРеквизита>' . PHP_EOL);
                                    fwrite($fp, '<Наименование>Статус заказа</Наименование>' . PHP_EOL);
                                    fwrite($fp, '<Значение>Готов к обеспечению</Значение>' . PHP_EOL);
                                    fwrite($fp, '</ЗначениеРеквизита>' . PHP_EOL);
                                    fwrite($fp, '<ЗначениеРеквизита>' . PHP_EOL);
                                    fwrite($fp, '<Наименование>Дата изменения статуса</Наименование>' . PHP_EOL);
                                    fwrite($fp, '<Значение>' . date("Y-m-d h:i:s") . '</Значение>' . PHP_EOL);
                                    fwrite($fp, '</ЗначениеРеквизита>' . PHP_EOL);
                                    fwrite($fp, '</ЗначенияРеквизитов>' . PHP_EOL);
                                    fwrite($fp, '</Документ>');
                                }
                            }
                            $ordersync = OrdersMain::find()->where(['order_id' => $item["order_id"]])->one();
                            if ($osid == 1)
                            {
                                $ordersync->sync_furn = 1; // Поменять на еденёрку
                                
                            }
                            elseif ($osid == 2)
                            {
                                $ordersync->sync_plit = 1; // Поменять на еденёрку
                                
                            }
                            $ordersync->save();
                        }
                    }
                }
                fwrite($fp, '</КоммерческаяИнформация>');
                fclose($fp);

                Yii::$app
                    ->response->format = \yii\web\Response::FORMAT_RAW;
                Yii::$app
                    ->response
                    ->headers
                    ->add('Content-Type', 'text/xml');
                $xml = file_get_contents('order.xml');
                return $this->renderPartial('index', ['xml' => $xml]);
        }
    }

    public function actionGear()
    {
        Yii::$app
            ->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app
            ->response
            ->headers
            ->add('Content-Type', 'text/xml');
        $xml = file_get_contents('order.xml');
        return $this->renderPartial('index', ['xml' => $xml]);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function actionParse($idf)
    {
        $i = 0;
        $с = 0;
        $dir = '../runtime/1c_exchange/';
        $files = scandir($dir, 1);
        //Goods::updateAll(['is_hide' => 1], 'osn = 1');
        //echo 'Удалены Goods';
        //echo "<br>";
        //Groups::updateAll(['is_hide' => 1], 'is_hide = 0');
        //echo 'Удалены Groups';
        //echo "<br>";
        //GoodOptionValues::updateAll(['is_hide' => 1], 'is_hide = 0');
        //echo 'Удалены GoodOptionValues';
        //echo "<br>";
        //GoodAssoc::updateAll(['is_hide' => 1], 'is_hide = 0');
        //echo 'Удалены GoodAssoc';
        //echo "<br>";
        foreach ($files as $value)
        {
            if (stristr($value, 'import' . $idf) == true && stristr($value, 'import_files') == false)
            {
                echo $value;
                echo '<br>';
                $xml = simplexml_load_file("../runtime/1c_exchange/" . $value);

                $owner_arr['id'] = $xml
                    ->Классификатор
                    ->Владелец->Ид;
                $owner_arr['name'] = $xml
                    ->Классификатор
                    ->Владелец->ОфициальноеНаименование;

                $owner_test = Owner::find()->where(['id_oc' => $owner_arr['id']])->one();

                echo '------------------------------>ГРУППЫ';
                foreach ($xml
                    ->Классификатор
                    ->Группы->Группа as $item)
                {
                    echo 'Группа : ';
                    echo $item->Ид . ' ' . $item->Наименование;
                    echo '<br>';

                    $groups_test = Groups::find()->where(['accounting_id' => $item
                        ->Ид])
                        ->one();
                    if (!empty($groups_test->id))
                    {
                        $groups_test->name = $item->Наименование;
                        $groups_test->accounting_id = $item->Ид;
                        $groups_test->save();
                        $groupid = $groups_test->id;
                    }
                    else
                    {
                        $groups = new Groups();
                        $groups->name = $item->Наименование;
                        $groups->accounting_id = $item->Ид;
                        $groups->save();
                        $groupid = $groups->id;
                    }
                    if ($item
                        ->Группы
                        ->Группа)
                    {
                        echo '------------------------------>ПОДГРУППЫ';
                        foreach ($item
                            ->Группы->Группа as $itemq)
                        {
                            echo 'Подгруппа : ';
                            echo $itemq->Ид . ' ' . $itemq->Наименование;
                            echo '<br>';

                            $groups_test = Groups::find()->where(['accounting_id' => $itemq
                                ->Ид])
                                ->one();
                            if (!empty($groups_test->id))
                            {
                                $groups_test->name = $itemq->Наименование;
                                $groups_test->accounting_id = $itemq->Ид;
                                $groups_test->is_hide = 0;
                                $groups_test->save();
                                $groupidq = $groups_test->id;
                            }
                            else
                            {
                                $groups = new Groups();
                                $groups->name = $itemq->Наименование;
                                $groups->parent_id = $groupid;
                                $groups->accounting_id = $itemq->Ид;
                                $groups->is_hide = 0;
                                $groups->save();
                                $groupidq = $groups->id;
                            }
                            if ($itemq
                                ->Группы
                                ->Группа)
                            {
                                foreach ($itemq
                                    ->Группы->Группа as $itemqq)
                                {
                                    echo 'Подподгруппа : ';
                                    echo $itemqq->Ид;
                                    echo ' --> ';
                                    echo $itemqq->Наименование;
                                    echo '<br>';

                                    $groups_test = Groups::find()->where(['accounting_id' => $itemqq
                                        ->Ид])
                                        ->one();
                                    if (!empty($groups_test->id))
                                    {
                                        $groups_test->name = $itemqq->Наименование;
                                        $groups_test->accounting_id = $itemqq->Ид;
                                        $groups_test->is_hide = 0;
                                        $groups_test->save();
                                        $groupidqq = $groups_test->id;
                                    }
                                    else
                                    {
                                        $groups = new Groups();
                                        $groups->name = $itemqq->Наименование;
                                        $groups->parent_id = $groupidq;
                                        $groups->accounting_id = $itemqq->Ид;
                                        $groups->is_hide = 0;
                                        $groups->save();
                                        $groupidqq = $groups->id;
                                    }
                                    if ($itemqq
                                        ->Группы
                                        ->Группа)
                                    {
                                        foreach ($itemqq
                                            ->Группы->Группа as $itemqqq)
                                        {
                                            echo 'Подподподгруппа : ';
                                            echo $itemqqq->Ид;
                                            echo ' --> ';
                                            echo $itemqqq->Наименование;
                                            echo '<br>';
                                            $groups_test = Groups::find()->where(['accounting_id' => $itemqqq
                                                ->Ид])
                                                ->one();
                                            if (!empty($groups_test->id))
                                            {
                                                $groups_test->name = $itemqqq->Наименование;
                                                $groups_test->accounting_id = $itemqqq->Ид;
                                                $groups_test->is_hide = 0;
                                                $groups_test->save();
                                            }
                                            else
                                            {
                                                $groups = new Groups();
                                                $groups->name = $itemqq->Наименование;
                                                $groups->parent_id = $groupidqq;
                                                $groups->accounting_id = $itemqq->Ид;
                                                $groups->is_hide = 0;
                                                $groups->save();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    echo '<br>';
                }
                
                echo '------------------------------>СВОЙСТВА';
                foreach ($xml
                    ->Классификатор
                    ->Свойства->Свойство as $item)
                {
                    echo 'Свойство : ';
                    echo $item->Ид;
                    echo '<br>';

                    $options_test = GoodOptions::find()->where(['oc_ref' => $item
                        ->Ид])
                        ->one();
                    if (!empty($options_test->op_id))
                    {
                        $options_test->op_titile = $item->Наименование;
                        $options_test->oc_ref = $item->Ид;
                        $options_test->oc_company_ref = $owner_arr['id'];
                        if ($options_test->op_titile == 'БрендДляВыгрузкиНаСайт') $options_test->is_hide = 1;
                        if ($options_test->op_titile == 'КодДляВыгрузкиНаСайт') $options_test->is_hide = 1;
                        if ($options_test->op_titile == 'SALE') $options_test->is_hide = 1;
                        $options_test->save();
                        $goodOptionsId = $options_test->op_id;
                    }
                    else
                    {
                        $goodOptions = new GoodOptions();
                        $goodOptions->op_titile = $item->Наименование;
                        $goodOptions->oc_ref = $item->Ид;
                        $goodOptions->oc_company_ref = $owner_arr['id'];
                        if ($item->Наименование == 'БрендДляВыгрузкиНаСайт') $goodOptions->is_hide = 1;
                        if ($item->Наименование == 'КодДляВыгрузкиНаСайт') $goodOptions->is_hide = 1;
                        if ($item->Наименование == 'SALE') $goodOptions->is_hide = 1;
                        $goodOptions->save();
                        $goodOptionsId = $goodOptions->op_id;
                    }
                    if ($item
                        ->ВариантыЗначений
                        ->Справочник)
                    {
                        foreach ($item
                            ->ВариантыЗначений->Справочник as $itemq)
                        {
                            $value_test = GoodOptionValues::find()->where(['oc_ref' => $itemq
                                ->ИдЗначения])
                                ->one();
                            if (!empty($value_test->rec_id))
                            {
                                $value_test->oc_ref = $itemq->ИдЗначения;
                                $value_test->op_id = $goodOptionsId;
                                $value_test->value = $itemq->Значение;
                                $value_test->is_hide = 0;
                                $value_test->save();
                            }
                            else
                            {
                                $goodOptionsVal = new GoodOptionValues();
                                $goodOptionsVal->oc_ref = $itemq->ИдЗначения;
                                $goodOptionsVal->op_id = $goodOptionsId;
                                $goodOptionsVal->value = $itemq->Значение;
                                $goodOptionsVal->save();
                            }
                        }
                    }
                    echo '<br>';
                }
                echo '<br>';
                echo '------------------------------>ТОВАРЫ';
                echo '<br>';
                foreach ($xml
                    ->Каталог
                    ->Товары->Товар as $item)
                {
                    echo 'ТОВАР: ';
                    echo $item['Статус'] . ' ' . $item->Ид;
                    echo '<br>';
                    if (!empty($item
                        ->Изготовитель
                        ->Наименование) & !empty($item
                        ->Изготовитель
                        ->Ид))
                    {
                        $manufacturers_test = Manufacturers::find()->where(['oc_id' => $item
                            ->Изготовитель
                            ->Ид])
                            ->one();
                        if (!empty($manufacturers_test->oc_id))
                        {
                            $manufacturers_test->manufacturer_title = $item
                                ->Изготовитель->Наименование;
                            $manufacturers_test->oc_id = $item
                                ->Изготовитель->Ид;
                            $manufacturers_test->save();
                            $manufacturersid = $manufacturers_test->manufacturer_id;
                        }
                        else
                        {
                            $manufacturers = new Manufacturers();
                            $manufacturers->manufacturer_title = $item
                                ->Изготовитель->Наименование;
                            $manufacturers->oc_id = $item
                                ->Изготовитель->Ид;
                            $manufacturers->save();
                            $manufacturersid = $manufacturers->manufacturer_id;
                        }
                    }
                    else
                    {
                        $manufacturersid = NULL;
                    }

                    $groupid = Groups::find()->select('id')
                        ->where(['accounting_id' => $item
                        ->Группы
                        ->Ид])
                        ->one();
                    $goods_test = Goods::find()->where(['oc_ref' => $item
                        ->Ид])
                        ->one();
                    $groups_ldsp = ([127, 203, 239, 253, 254, 241, 243, 242, 248, 247, 255, 263]);
                    if (!empty($goods_test->oc_ref))
                    {
                        $goods_test->title = trim($item->Наименование);
                        $goods_test->manufacturer_id = $manufacturersid;
                        $goods_test->is_hide = ($item['Статус'] == 'Удален') ? 1 : 0;
                        $goods_test->updated_datetime = new Expression('NOW()');
                        $goods_test->oc_ref = $item->Ид;
                        $goods_test->termin = $item->БазоваяЕдиница['НаименованиеПолное'];
                        $goods_test->articul = $item->Артикул;
                        $goods_test->fuldesc = nl2br($item->Описание);
                        $goods_test->osn = (in_array($groupid->id, $groups_ldsp)) ? 2 : 1;
                        $goods_test->discount = (in_array($groupid->id, $groups_ldsp)) ? 0 : 5;
                        $goods_test->save();
                        $goodsid = $goods_test->good_id;
                    }
                    else
                    {
                        $goods = new Goods();
                        $goods->title = trim($item->Наименование);
                        $goods->manufacturer_id = $manufacturersid;
                        $goods->is_hide = ($item['Статус'] == 'Удален') ? 1 : 0;
                        $goods->is_new = 0;
                        $goods->is_action = 0;
                        $goods->cat_id = $groupid->id;
                        $goods->updated_datetime = new Expression('NOW()');
                        $goods->created_datetime = new Expression('NOW()');
                        $goods->oc_ref = $item->Ид;
                        $goods->articul = $item->Артикул;
                        $goods->fuldesc = nl2br($item->Описание);
                        $goods_test->osn = (in_array($groupid->id, $groups_ldsp)) ? 2 : 1;
                        $goods->termin = $item->БазоваяЕдиница['НаименованиеПолное'];
                        $goods_test->discount = (in_array($groupid->id, $groups_ldsp)) ? 0 : 5;
                        $goods->save();
                        $goodsid = $goods->good_id;
                    }

                    if ($item->Картинка)
                    {
                        $ima = explode('/', $item->Картинка);
                        $image_test = GoodImages::find()->where(['good_id' => $goodsid])->one();
                        if ($image_test->good_id)
                        {
                            $image_test->image_link = 'https://ural-dekor.ru/frontend/runtime/1c_exchange/' . $item->Картинка;
                            $image_test->save();
                            $image_id = $image_test->image_id;
                        }
                        else
                        {
                            $images = new GoodImages();
                            $images->good_id = $goodsid;
                            $images->is_main = 1;
                            $images->image_link = 'https://ural-dekor.ru/frontend/runtime/1c_exchange/' . $item->Картинка;
                            $images->save();
                            $image_id = $image->image_id;
                        }
                    }
                    if ($item
                        ->ЗначенияСвойств->ЗначенияСвойства && $item['Статус'] != 'Удален')
                    {
                        foreach ($item
                            ->ЗначенияСвойств->ЗначенияСвойства as $itemq)
                        {
                            if (!is_null($itemq->Ид) & !is_null($itemq->Значение))
                            {
                                $option = GoodOptions::find()->where(['oc_ref' => $itemq
                                    ->Ид])
                                    ->one();
                                echo '<br>';
                                echo $option->op_titile;
                                echo '<br>';
                                if ($option->op_titile == 'SALE')
                                {
                                    $opval = $optionV = GoodOptionValues::find()->where(['op_id' => $option->op_id])->one();
                                    echo $itemq->Значение;
                                    echo $opval->value;
                                    if($opval->value == 'да'){
                                        $goods_test = Goods::findOne($goodsid);
                                        $goods_test->sale = 1;
                                        $goods_test->save();
                                    }else{
                                        $goods_test = Goods::findOne($goodsid);
                                        $goods_test->sale = 0;
                                        $goods_test->save();
                                    }
                                }
                                if ($option->op_titile == 'КодДляСайта')
                                {
                                    echo $itemq->Значение;
                                    echo '<br>';
                                    $goods_test = Goods::findOne($goodsid);
                                    $goods_test->description = $itemq->Значение;
                                    $goods_test->description_digit = preg_match("/[^\d]/", $itemq->Значение);
                                    $goods_test->save();
                                }
                                if ($option->op_titile == 'БрендДляСайта')
                                {
                                    $goods_test = Goods::findOne($goodsid);
                                    $goods_test->brand = $itemq->Значение;
                                    $goods_test->save();
                                }
                                else
                                {
                                    $goods_test = Goods::findOne($goodsid);

                                    $manufacturers_test = Manufacturers::find()->where(['oc_id' => $item
                                        ->Изготовитель
                                        ->Ид])
                                        ->one();

                                    $goods_test->brand = $manufacturers_test->manufacturer_title;
                                    $goods_test->save();
                                }
                                $optionV = GoodOptionValues::find()->select('rec_id')
                                    ->where(['oc_ref' => $itemq
                                    ->Значение])
                                    ->one();

                                $goodassoc_test = GoodAssoc::find()->select('id')
                                    ->where(['good_id' => $goodsid])->andFilterWhere(['opt_id' => $option
                                    ->op_id])
                                    ->andFilterWhere(['opt_val_good' => $optionV
                                    ->rec_id])
                                    ->one();
                                if (!is_null($option->op_id) & !is_null($optionV->rec_id))
                                {
                                    if ($goodassoc_test->id)
                                    {
                                        $goodassoc_test->good_id = $goodsid;
                                        $goodassoc_test->opt_id = $option->op_id;
                                        $goodassoc_test->opt_val_good = $optionV->rec_id;
                                        $goodassoc_test->group_id = $groupid->id;
                                        $goodassoc_test->is_hide = 0;
                                        $goodassoc_test->save();
                                    }
                                    else
                                    {
                                        $goodAssoc = new GoodAssoc();
                                        $goodAssoc->good_id = $goodsid;
                                        $goodAssoc->opt_id = $option->op_id;
                                        $goodAssoc->opt_val_good = $optionV->rec_id;
                                        $goodAssoc->group_id = $groupid->id;
                                        $goodassoc->is_hide = 0;
                                        $goodAssoc->save();
                                    }
                                    //$optionV->is_hide = 0;
                                    $optionV->save();
                                }
                            }
                        }
                    }
                    if ($item
                        ->ЗначенияРеквизитов->ЗначениеРеквизита && $item['Статус'] != 'Удален')
                    {
                        foreach ($item
                            ->ЗначенияРеквизитов->ЗначениеРеквизита as $itemo)
                        {
                            if (!is_null($itemo->Наименование) & !is_null($itemo->Значение))
                            {
                                echo '<br>';
                                echo $option->op_titile;
                                echo '<br>';
                                if ($itemo->Наименование == 'КодДляСайта')
                                {
                                    echo $itemo->Значение;
                                    echo '<br>';
                                    $goods_test = Goods::findOne($goodsid);
                                    $goods_test->description = $itemo->Значение;
                                    $goods_test->description_digit = preg_match("/[^\d]/", $itemo->Значение);
                                    $goods_test->save();
                                }
                                if ($itemo->Наименование == 'ПутьКФайлу')
                                {
                                    $imgpath = 'https://ural-dekor.ru/frontend/web/img/udf/npics';
                                    $imglink = str_replace('Номенклатура',$imgpath,str_replace('\\','/',$itemo->Значение));
                                    echo '<br> >>>IMAGE>>>'.$imglink.'<br>';
                                    
                                    $image_test = GoodImages::find()->where("`image_link` LIKE '" . $imglink."'")->one();
                                    if ($image_test->good_id)
                                    {
                                        $image_test->image_link = $imglink;
                                        $image_test->save();
                                        $image_id = $image_test->image_id;
                                    }
                                    else
                                    {
                                        echo 'tuk';
                                        $images = new GoodImages();
                                        $images->good_id = $goodsid;
                                        $images->is_main = 1;
                                        $images->image_link = $imglink;
                                        $images->save();
                                        $image_id = $image->image_id;
                                    }
                                }
                                if ($itemo->Наименование == 'БрендДляСайта')
                                {
                                    $goods_test = Goods::findOne($goodsid);
                                    $goods_test->brand = $itemo->Значение;
                                    $goods_test->save();
                                }
                            }
                        }
                    }
                    echo '*********************************************************************************************************<br>';
                }
                echo '<br>ALL ' . $i . ' <br>';
                $i++;
            }
            if (stristr($value, 'offers0_' . $idf) == true)
            {
                $xmle = simplexml_load_file("../runtime/1c_exchange/" . $value);
                echo $value;
                foreach ($xmle
                    ->ПакетПредложений
                    ->ТипыЦен->ТипЦены as $item)
                {
                    echo '<br>';
                    echo 'ТипЦены :  ';
                    //echo $item->Ид;
                    //echo '  ->  ';
                    echo $item->Наименование;
                    $pricetype_test = PriceType::find()->where(['accounting_id' => $item
                        ->Ид])
                        ->one();
                    if (!empty($pricetype_test->id))
                    {
                        $pricetype_test->accounting_id = $item->Ид;
                        $pricetype_test->name = $item->Наименование;
                        $pricetype_test->currency = $item->Валюта;
                        $pricetype_test->save();
                    }
                    else
                    {
                        $priceType = new PriceType();
                        $priceType->accounting_id = $item->Ид;
                        $priceType->name = $item->Наименование;
                        $priceType->currency = $item->Валюта;
                        $priceType->save();
                    }
                    echo '<br>';
                }
                foreach ($xmle
                    ->ПакетПредложений
                    ->Склады->Склад as $item)
                {

                    $stock_test = Stock::find()->where(['guid' => $item
                        ->Ид])
                        ->one();

                    if (!empty($stock_test->id))
                    {
                        $stock_test->stock = $item->Наименование;
                        $stock_test->save();
                    }
                    else
                    {
                        $stock = new Stock();
                        $stock->guid = $item->Ид;
                        $stock->stock = $item->Наименование;
                        $stock->stock_group = 1;
                        $stock->save();
                    }
                    echo "=> " . $item->Наименование;
                    echo "<br>";
                }
                foreach ($xmle
                    ->ПакетПредложений
                    ->Предложения->Предложение as $item)
                {
                    echo '-=>';
                    echo $item->Ид;
                    $goods_cost = Goods::find()->where(['oc_ref' => $item
                        ->Ид])
                        ->one();
                    if (!empty($goods_cost->good_id))
                    {
                        $goods_cost->in_stock = $item->Количество;
                        if ($item->Количество < 1)
                        {
                            $goods_cost->is_hide = 1;
                        }
                        $goods_cost->save();
                    }
                    if ($item->Склад)
                    {
                        $s = 0;
                        foreach ($item->Склад as $itemb)
                        {
                            echo '<br/>';
                            $itemo = strval($itemb[$s++]['ИдСклада']);
                            //echo $itemo;
                            $stocke = Stock::find()->where("`guid` LIKE '" . $itemo . "'")->one();
                            echo $stocke->id;

                            if (!is_null($stocke->id) & !is_null($goods_cost->good_id))
                            {
                                $good_balance_test = GoodBalance::find()->where(['stock_id' => $stocke
                                    ->id])
                                    ->andFilterWhere(['good_id' => $goods_cost
                                    ->good_id])
                                    ->one();
                                if (!is_null($good_balance_test->id))
                                {
                                    $good_balance_test->balance = intval($itemb[$s++]['КоличествоНаСкладе']);
                                    //$good_balance_test->up_date = new Expression('NOW()');
                                    $good_balance_test->save();
                                    if (intval($itemb['КоличествоНаСкладе']) == 0)
                                    {
                                        $good_balance_test->delete();
                                    }
                                }
                                else
                                {
                                    if (intval($itemb[$s++]['КоличествоНаСкладе']) != 0)
                                    {
                                        $good_balance = new GoodBalance();
                                        $good_balance->good_id = $goods_cost->good_id;
                                        $good_balance->stock_id = $stocke->id;
                                        $good_balance->balance = intval($itemb[$s++]['КоличествоНаСкладе']);
                                        //$good_balance->up_date = new Expression('NOW()');
                                        $good_balance->save();
                                        echo 'balance' . intval($itemb[$s++]['КоличествоНаСкладе']);
                                    }
                                }

                            }
                            echo '<br/>';
                        }
                    }
                    if ($item
                        ->Цены
                        ->Цена)
                    {
                        foreach ($item
                            ->Цены->Цена as $itemq)
                        {
                            $pricetype_cost = PriceType::find()->where(['accounting_id' => $itemq
                                ->ИдТипаЦены])
                                ->one();
                            if (!is_null($pricetype_cost->id) & !is_null($goods_cost->good_id))
                            {
                                $price_test = Price::find()->where(['type_id' => $pricetype_cost
                                    ->id])
                                    ->andFilterWhere(['good_id' => $goods_cost
                                    ->good_id])
                                    ->one();
                                if (!empty($price_test->id))
                                {
                                    $price_test->value = $itemq->ЦенаЗаЕдиницу;
                                    $price_test->currency = $itemq->Валюта;
                                    $price_test->rate = $itemq->Коэффициент;
                                    $price_test->type_id = $pricetype_cost->id;
                                    $price_test->good_id = $goods_cost->good_id;
                                    $price_test->up_date = new Expression('NOW()');
                                    $price_test->save();
                                }
                                else
                                {
                                    $price = new Price();
                                    $price->value = $itemq->ЦенаЗаЕдиницу;
                                    $price->currency = $itemq->Валюта;
                                    $price->rate = $itemq->Коэффициент;
                                    $price->type_id = $pricetype_cost->id;
                                    $price->good_id = $goods_cost->good_id;
                                    $price->up_date = new Expression('NOW()');
                                    $price->save();
                                }

                                if ($itemq->ЦенаЗаЕдиницу == 0 && $pricetype_cost->id != 3)
                                {
                                    $price_tes = Price::find()->where(['type_id' => $pricetype_cost
                                        ->id])
                                        ->andFilterWhere(['good_id' => $goods_cost
                                        ->good_id])
                                        ->one();
                                    $price_te = Price::find()->where(['type_id' => 3])
                                        ->andFilterWhere(['good_id' => $goods_cost
                                        ->good_id])
                                        ->one();

                                    $price_tes->value = $price_te->value;
                                    $price_tes->currency = $price_te->currency;
                                    $price_tes->rate = $price_te->rate;
                                    $price_tes->save();
                                }

                            }
                            echo '<br/>';
                        }
                    }
                    //echo '*************************************************************<br>' . $c;
                    
                }
                $c++;
            }
        }
    }
    //функция POST-запроса по URL
    

    public function actionOrder()
    {
        //ExchangersController::actionCheck();
        $dir = '../runtime/1c_exchange/';
        $files = scandir($dir, 1);

        $maxvell = OrdersMain::find()->max('order_id');
        print_r($maxvell);

        foreach ($files as $value)
        {
            echo '<br><br>';
            echo $value . ' ';
            if (stristr($value, 'orders') === false)
            {
                if (stristr($value, '.zip') === false)
                {
                    //
                    
                }
                else
                {
                    unlink("../runtime/1c_exchange/" . $value);
                }
                echo " file none";
            }
            else
            {
                $xml = simplexml_load_file("../runtime/1c_exchange/" . $value);
                foreach ($xml->Документ AS $doc)
                {
                    $prefix = preg_replace("/[^a-zA-Zа-яА-Я]/", '', $doc->Номер);
                    $nomer = preg_replace('/[^0-9]/', '', $doc->Номер);
                    $num = (int)$nomer;
                    $osn = 2;
                    if ($prefix == "ПЛИТ")
                    {
                        $osn = 1;
                    }
                    echo "<br> >> ";
                    echo $doc->Номер;
                    echo "<br>";
                    echo $prefix;
                    echo "<br>";
                    echo $num;
                    echo "<br>";
                    echo $osn;
                    echo " << <br> ";

                    if ($maxvell < $num)
                    {
                        //
                        
                    }
                    else
                    {

                        echo $doc
                            ->ЗначенияРеквизитов
                            ->ЗначениеРеквизита[2]->Значение . ' ';

                        $ordersMain = OrdersMain::find()->where('order_id = ' . $num)->one();
                        $order_state = $ordersMain->sync_furn_return;
                        $client_order_id = $ordersMain['client_id'];

                        $client = ClientCommon::findOne($client_order_id);
                        $order_status = $ordersMain->sync_furn_return;
                        if ($doc
                            ->ЗначенияРеквизитов
                            ->ЗначениеРеквизита[2]->Значение == 'true')
                        {
                            $gog = new \yii\db\Query;
                            $gog->select('`goods`.`osn`')
                                ->from('`order_contents`')
                                ->leftJoin('`goods`', '`order_contents`.`good_id` = `goods`.`good_id`');
                            $gog->where("`goods`.`osn` = '" . $osn . "'")->andWhere("`order_contents`.`order_id` = " . $num);
                            $command = $gog->createCommand();
                            $query = $command->queryAll();

                            $state = array(
                                "0" => "Ожидается согласование",
                                "1" => "Ожидается аванс (до обеспечения)",
                                "2" => "Готов к обеспечению",
                                "3" => "Ожидается предоплата (до отгрузки)",
                                "4" => "Ожидается обеспечение",
                                "5" => "Готов к отгрузке",
                                "6" => "В процессе отгрузки",
                                "7" => "Ожидается оплата (после отгрузки)",
                                "8" => "Готов к закрытию",
                                "9" => "Закрыт",
                            );
                            $alarm = [3, 5];


                            echo "<br>" . $num . "->";

                            if (count($query) > 0)
                            {
                                // $ordersMain->sync_furn_return = 1;
                                $ordersMain->an_comment = $doc
                                    ->ЗначенияРеквизитов
                                    ->ЗначениеРеквизита[3]->Значение;
                                $ordersMain->sync_furn_return = array_search($doc
                                    ->ЗначенияРеквизитов
                                    ->ЗначениеРеквизита[3]->Значение, $state);
                                //echo array_search($doc->ЗначенияРеквизитов->ЗначениеРеквизита[3]->Значение, $state);
                                
                            }
                            else
                            {
                                $ordersMain->sync_furn_return = array_search($doc
                                    ->ЗначенияРеквизитов
                                    ->ЗначениеРеквизита[3]->Значение, $state);
                                $ordersMain->sync_plit_return = 15;
                                //$ordersMain->sync_furn_return = 2;
                                
                            }

                            foreach ($doc
                                ->ЗначенияРеквизитов->ЗначениеРеквизита as $rec)
                            {
                                if ($rec->Наименование == "ПроцентОплаты")
                                {
                                    $ordersMain->balance_furn = (strpos($rec->Значение, 'Закрыт') !== false)?$doc->Сумма:0;
                                    echo $rec->Наименование . '---->' . (strpos($rec->Значение, 'Закрыт') !== false)?'' . $doc->Сумма:$rec->Значение . '<br>';
                                }
                                if ($rec->Наименование == "ПроцентОтгрузки")
                                {
                                    $ordersMain->shipment_furn = $rec->Значение;
                                    echo $rec->Наименование . '-->' . $rec->Значение . '<br>';
                                }
                            }
                            echo $order_state;
                            echo array_search($doc
                                ->ЗначенияРеквизитов
                                ->ЗначениеРеквизита[3]->Значение, $state);
                            if ($order_state != array_search($doc
                                ->ЗначенияРеквизитов
                                ->ЗначениеРеквизита[3]->Значение, $state) && in_array(array_search($doc
                                ->ЗначенияРеквизитов
                                ->ЗначениеРеквизита[3]->Значение, $state) , $alarm))
                            {

                                if($order_status != array_search($doc
                                    ->ЗначенияРеквизитов
                                    ->ЗначениеРеквизита[3]->Значение, $state)){

                                    //Yii::$app
                                        //->mailer
                                        //->compose()
                                        //->setFrom(['robot@ural-dekor.ru' => 'Урал Декор'])
                                        //->setTo($client['email'])->setSubject('Статус заказа №' . $num . ' изменен')->setTextBody('Статус Вашего заказа на сайте Урал-Декор был изменен.<br>Новый статус заказа: ' . $doc
                                        //->ЗначенияРеквизитов
                                        //->ЗначениеРеквизита[3]
                                        //->Значение)
                                        //->setHtmlBody('Статус Вашего заказа на сайте Урал-Декор был изменен.<br>Новый статус заказа: ' . $doc
                                        //->ЗначенияРеквизитов
                                        //->ЗначениеРеквизита[3]
                                        //->Значение)
                                        //->send();
                                        
                                        $le = new LogErrors();
                                        $le->log = 'Статус заказа №' . $num . ' изменен. Новый статус заказа: ' . $doc->ЗначенияРеквизитов->ЗначениеРеквизита[3]->Значение;
                                        $le->save();

                                    echo $client['email'];

                                    $data['login'] = 'ural-dekor';
                                    $data['psw'] = 'uraldecor56';
                                    $data['phones'] = $client['phone'];
                                    $data['mes'] = 'Статус Вашего заказа №' . $num . ' на сайте Урал-Декор был изменен на "' . $doc
                                        ->ЗначенияРеквизитов
                                        ->ЗначениеРеквизита[3]->Значение . '"';
                                    $data['sender'] = 'Test';
                                    $sms = $this->postRequest('smsc.ru/sys/send.php', $data);
                                    echo ' +++';
                                }
                            }

                            echo $client_order_id . ', ' . $doc
                                ->ЗначенияРеквизитов
                                ->ЗначениеРеквизита[3]->Значение;

                        }
                        else
                        {
                            $ordersMain->order_status_id = $doc
                                ->ЗначенияРеквизитов
                                ->ЗначениеРеквизита[3]->Значение;
                        }

                        if ($ordersMain->save())
                        {
                            unlink("../runtime/1c_exchange/" . $value);
                            
                        }

                        echo '<br>';
                    }
                }
            }
        }
    }

    public function actionList()
    {
        //$goods = Goods::find()
        //->select(['description'])
        //->where(['is_hide'=>0])
        //->andWhere("`title` LIKE '%  %'")
        //->andWhere("`description` LIKE '%ЦБ-%'")
        //->all();
        $models = new \yii\db\Query;
        $models->select()
            ->from('`goods`')
            ->leftJoin('`goods_images`', '`goods`.`good_id` = `goods_images`.`good_id`');
        $models->where("`description` LIKE '%ЦБ-%'");
        $command = $models->createCommand();
        $query = $command->queryAll();
        foreach ($query AS $good)
        {
            //print_r($good);
            echo $good['description'];
            echo '<br>';
        }
    }

    public function actionLol()
    {
        $ima = GoodImages::find()->all();
        foreach ($ima AS $lil)
        {
            $imag = str_replace('http://ural-dekor.ru/frontend', '', $lil->image_link);
            if (file_exists("../" . $imag))
            {
                echo '<span style="color:#ff0000;">';
                echo $imag;
                echo '</span><br>';
            }
            else
            {
                // $model = GoodImages::find()->where(['image_link' => $lil->image_link])->one();
                //$model->delete();
                echo $imag;
                echo '<br>';
            }
        }
    }

    public function actionCheck()
    {

        $goods_count = Goods::find()->Where(['>', 'updated_datetime', date("Y-m-d 0:0:0", time() - (60 * 60 * 24)) ])
            ->andWhere(['is_hide' => 0])
            ->count();

        $price_count = Price::find()->where(['>', 'value', 0])
            ->orWhere(['>', 'up_date', date("Y-m-d 0:0:0", time() - (60 * 60 * 24)) ])
            ->count();

        $inos = OrdersMain::find()->where('(sync_plit = 0 AND updated_datetime > "' . date("Y-m-d H:i:s", time() - (60 * 60 * 24)) . '" AND updated_datetime < "' . date("Y-m-d H:i:s", time() - (60 * 60 * 0.5)) . '" AND sync_furn = 0 AND test_order LIKE "no")')
            ->all();

        $outos = OrdersMain::find()->where('(sync_plit_return = 0 AND updated_datetime > "' . date("Y-m-d H:i:s", time() - (60 * 60 * 24)) . '" AND updated_datetime < "' . date("Y-m-d H:i:s", time() - (60 * 60 * 0.5)) . '" AND sync_furn_return = 0 AND test_order LIKE "no")')
            ->all();

        $str = ' Ошибка!';

        $send_or_not_send = 0;

        echo 'Товаров : ';
        print_r($goods_count);
        echo '<br>';
        echo 'Цен : ';
        print_r($price_count);

        echo '<br>';
        echo 'В 1с заказов не отправлено : ';
        foreach ($inos as $in_os)
        {

            print_r($in_os->order_id);

            if ($in_os->order_id > 0)
            {
                $send_or_not_send = 1;
                $str .= ' Не был отправлен d 1с заказ № ' . $in_os->order_id . '<br>';
            }
        }

        echo '<br>';
        echo 'Из 1с заказов не пришло : ';
        foreach ($outos as $out_os)
        {

            print_r($out_os->order_id);

            if ($out_os->order_id > 0)
            {
                $send_or_not_send = 1;
                $str .= ' Не был отправлен из 1с заказ № ' . $out_os->order_id . '<br>';
            }
        }

        if ($price_count < 24000)
        {
            $send_or_not_send = 1;
        }
        $str .= ' Цен : ' . $price_count . '<br>';
        $str .= '<br>';
        if ($goods_count < 8000)
        {
            $send_or_not_send = 1;
        }
        $str .= ' Товаров : ' . $goods_count . '<br>';
        $str .= '<br>';

        if ($send_or_not_send == 1)
        {
            Yii::$app
                ->mailer
                ->compose()
                ->setFrom(['robot@ural-dekor.ru' => 'Урал Декор'])
                ->setTo('orenengine@gmail.com')
                ->setSubject('Состояние синхронизации.')
                ->setTextBody('')
                ->setHtmlBody($str)->send();
            Yii::$app
                ->mailer
                ->compose()
                ->setFrom(['robot@ural-dekor.ru' => 'Урал Декор'])
                ->setTo('solforbus@gmail.com')
                ->setSubject('Состояние синхронизации.')
                ->setTextBody('')
                ->setHtmlBody($str)->send();
            Yii::$app
                ->mailer
                ->compose()
                ->setFrom(['robot@ural-dekor.ru' => 'Урал Декор'])
                ->setTo('starkov@ssoft.ru')
                ->setSubject('Состояние синхронизации.')
                ->setTextBody('')
                ->setHtmlBody($str)->send();
        }
        return true;
    }
}