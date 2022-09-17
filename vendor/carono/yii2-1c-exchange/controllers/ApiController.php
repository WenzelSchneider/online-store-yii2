<?php

namespace carono\exchange1c\controllers; 

use carono\exchange1c\behaviors\BomBehavior;
use carono\exchange1c\ExchangeEvent;
use carono\exchange1c\ExchangeModule;
use carono\exchange1c\helpers\ByteHelper;
use carono\exchange1c\helpers\NodeHelper;
use carono\exchange1c\helpers\SerializeHelper;
use carono\exchange1c\interfaces\DocumentInterface;
use carono\exchange1c\interfaces\OfferInterface;
use carono\exchange1c\interfaces\ProductInterface;

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

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\web\Response;
use Zenwalker\CommerceML\CommerceML;
use Zenwalker\CommerceML\Model\Classifier;
use Zenwalker\CommerceML\Model\Group;
use Zenwalker\CommerceML\Model\Image;
use Zenwalker\CommerceML\Model\Offer;
use Zenwalker\CommerceML\Model\Product;
use Zenwalker\CommerceML\Model\PropertyCollection;
use Zenwalker\CommerceML\Model\Simple;
use Zenwalker\CommerceML\Model\RequisiteCollection;

/**
 * Default controller for the `api` module
 *
 * @property ExchangeModule $module
 */
class ApiController extends Controller
{
    public $enableCsrfValidation = false;
    public $commerceMLVersion = '2.10';

    const EVENT_BEFORE_UPDATE_PRODUCT = 'beforeUpdateProduct';
    const EVENT_AFTER_UPDATE_PRODUCT = 'afterUpdateProduct';
    const EVENT_BEFORE_UPDATE_OFFER = 'beforeUpdateOffer';
    const EVENT_AFTER_UPDATE_OFFER = 'afterUpdateOffer';
    const EVENT_BEFORE_PRODUCT_SYNC = 'beforeProductSync';
    const EVENT_AFTER_PRODUCT_SYNC = 'afterProductSync';
    const EVENT_BEFORE_OFFER_SYNC = 'beforeOfferSync';
    const EVENT_AFTER_OFFER_SYNC = 'afterOfferSync';
    const EVENT_AFTER_FINISH_UPLOAD_FILE = 'afterFinishUploadFile';
    const EVENT_AFTER_EXPORT_ORDERS = 'afterExportOrders';

    private $_ids;

    public function init()
    {
        set_time_limit($this->module->timeLimit);
        if ($this->module->memoryLimit) {
            ini_set('memory_limit', $this->module->memoryLimit);
        }
        parent::init();
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'bom' => [
                'class' => BomBehavior::class,
                'only' => ['query'],
            ],
        ]);
    }

    /**
     * @param \yii\base\Action $action
     * @param mixed $result
     * @return mixed|string
     */
    public function afterAction($action, $result)
    {
        Yii::$app->response->headers->set('uid', Yii::$app->user->getId());
        if (is_bool($result)) {
            return $result ? 'success' : 'failure';
        }

        if (is_array($result)) {
            $r = [];
            foreach ($result as $key => $value) {
                $r[] = is_int($key) ? $value : $key . '=' . $value;
            }
            return implode("\n", $r);
        }

        return parent::afterAction($action, $result);
    }

    /**
     * @param $type
     * @return array|bool
     */
    public function actionCheckauth($type)
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        return [
            'success',
            'PHPSESSID',
            Yii::$app->session->getId(),
        ];
    }

    /**
     * @return float|int|false
     */
    protected function getFileLimit()
    {
        $limit = ByteHelper::maximum_upload_size();
        if (!($limit % 4)) {
            $limit--;
        }
        return $limit;
    }

    /**
     * @return array
     */
    public function actionInit()
    {
        return [
            'zip' => class_exists('ZipArchive') && $this->module->useZip ? 'yes' : 'no',
            'file_limit' => $this->getFileLimit(),
        ];
    }

    /**
     * @param $type
     * @param $filename
     * @return bool
     */
    public function actionFile($type, $filename)
    {
        $body = Yii::$app->request->getRawBody();
        $filePath = $this->module->getTmpDir() . DIRECTORY_SEPARATOR . $filename;
        $isArchive = strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) === 'zip';
        file_put_contents($filePath, $body, FILE_APPEND);
        if ((int)Yii::$app->request->headers->get('Content-Length') != $this->getFileLimit()) {
            if ($isArchive) {
                $this->extractArchive($filePath);
            }
            $this->afterFinishUploadFile($filePath);
        }
        return true;
    }

    /**
     * @param $file
     */
    public function parsingImport($file)
    {
        $this->_ids = [];
        $commerce = new CommerceML();
        $commerce->loadImportXml($file);
        $classifierFile = Yii::getAlias($this->module->tmpDir . '/classifier.xml');
        if ($commerce->classifier->xml) {
            $commerce->classifier->xml->saveXML($classifierFile);
        } else {
            $commerce->classifier->xml = simplexml_load_string(file_get_contents($classifierFile));
        }
        $this->beforeProductSync();
        if ($groupClass = $this->getGroupClass()) {
            $groupClass::createTree1c($commerce->classifier->getGroups());
        }
        $productClass = $this->getProductClass();
        $productClass::createProperties1c($commerce->classifier->getProperties());
        foreach ($commerce->catalog->getProducts() as $product) {
            if (!$model = $productClass::createModel1c($product)) {
                Yii::error("Модель продукта не найдена, проверьте реализацию $productClass::createModel1c",
                    'exchange1c');
                continue;
            }
            $this->parseProduct($model, $product);
            $this->_ids[] = $model->getPrimaryKey();
            $model = null;
            unset($model, $product);
            gc_collect_cycles();
        }
        $this->afterProductSync();
    }

    /**
     * @param $file
     */
    public function parsingOffer($file)
    {
        $this->_ids = [];
        $commerce = new CommerceML();
        $commerce->loadOffersXml($file);
        if ($offerClass = $this->getOfferClass()) {
            $offerClass::createPriceTypes1c($commerce->offerPackage->getPriceTypes());
        }
        $this->beforeOfferSync();
        foreach ($commerce->offerPackage->getOffers() as $offer) {
            $product_id = $offer->getClearId();
            if ($product = $this->findProductModelById($product_id)) {
                $model = $product->getOffer1c($offer);
                $this->parseProductOffer($model, $offer);
                $this->_ids[] = $model->getPrimaryKey();
            } else {
                Yii::warning("Продукт $product_id не найден в базе", 'exchange1c');
                continue;
            }
            unset($model);
        }
        $this->afterOfferSync();
    }

    /**
     * @param $file
     */
    public function parsingOrder($file)
    {
        /**
         * @var DocumentInterface $documentModel
         */
        $commerce = new CommerceML();
        $commerce->addXmls(false, false, $file);
        $documentClass = $this->module->documentClass;
        foreach ($commerce->order->documents as $document) {
            if ($documentModel = $documentClass::findOne((string)$document->Номер)) {
                $documentModel->setRaw1cData($commerce, $document);
            }
        }
    }

    /**
     * @param $filePath
     */
    private function extractArchive($filePath)
    {
        $zip = new \ZipArchive();
        $zip->open($filePath);
        $zip->extractTo(dirname($filePath));
        $zip->close();
        if (!$this->module->debug) {
            FileHelper::unlink($filePath);
        }
    }

    /**
     * @param $type
     * @param $filename
     * @return bool
     */
    public function actionImport($type, $filename)
    {
        $file = $this->module->getTmpDir() . DIRECTORY_SEPARATOR . $filename;
        switch ($type) {
            case 'catalog':
                if (strpos($file, 'offer') !== false) {
                    $this->parsingOffer($file);
                } elseif (strpos($file, 'import') !== false) {
                    $this->parsingImport($file);
                }
                break;
            case 'sale':
                if (strpos($file, 'order') !== false) {
                    $this->parsingOrder($file);
                }
                break;
        }
        if (!$this->module->debug) {
            FileHelper::unlink($file);
        }
        return true;
    }

    protected function clearTmp()
    {
        FileHelper::removeDirectory($this->module->getTmpDir());
    }

    /**
     * @param $type
     * @return mixed
     */
    public function actionQuery($type)
    {
                
                // открываем файл, если файл не существует,
            //делается попытка создать его
            $fp = fopen("order.xml", "w+");
                fwrite($fp, '<?xml version="1.0" encoding="UTF-8"?>');
                fwrite($fp, '<КоммерческаяИнформация ВерсияСхемы="2.03" ДатаФормирования="2019-11-21">' . PHP_EOL);
                $ordersMain = OrdersMain::find()
                ->where(['sync' => 0])
                ->all();
            foreach ($ordersMain as $item) {
                if(!empty($item)){
                $clientCommon = ClientCommon::find()
                    ->where(['client_id' => $item["client_id"]])
                    ->one();
                    if($clientCommon['client_type'] == "jur"){
                        $clientJurData = ClientJurData::find()
                            ->where(['client_id' => $clientCommon["client_id"]])
                            ->one();
                            $clientData["id"] = $clientJurData["client_id"];
                            $clientData["naming"] = $clientJurData["title"];
                            $clientData["fullnaming"] = $clientJurData["title_full"];
                            $clientData["secondname"] = $clientJurData["director_fio"];
                            $clientData["name"] = $clientJurData["director_fio"];
                            $clientData["address"] = $clientJurData["address_jur"];
                            $clientData["represid"] = $clientJurData["client_id"];
                            $clientData["represfullname"] = $clientJurData["director_fio"];
                    }elseif($clientCommon['client_type'] == "fiz"){
                        $clientFizData = ClientFizData::find()
                            ->where(['client_id' => $clientCommon["client_id"]])
                            ->one();
                            $clientData["id"] = $clientFizData["client_id"];
                            $clientData["naming"] = $clientFizData["lastname"].' '.$clientFizData["firstname"];
                            $clientData["fullnaming"] = $clientFizData["lastname"].' '.$clientFizData["firstname"];
                            $clientData["secondname"] = $clientFizData["lastname"].' '.$clientFizData["firstname"];
                            $clientData["name"] = $clientFizData["firstname"];
                            $clientData["address"] = "0";
                            $clientData["represid"] = $clientFizData["client_id"];
                            $clientData["represfullname"] = $clientFizData["firstname"] . ' ' . $clientFizData["lastname"];
                    }
                fwrite($fp, '<Документ>' . PHP_EOL); 
                fwrite($fp, '<Ид>'.$item["order_id"].'</Ид>' . PHP_EOL);
                fwrite($fp, '<Номер>'.$item["order_id"].'</Номер>' . PHP_EOL);
                fwrite($fp, '<Дата>'.$item["created_datetime"].'</Дата>' . PHP_EOL);
                fwrite($fp, '<ХозОперация>Заказ товара'.$clientCommon['client_type'].'</ХозОперация>' . PHP_EOL);
                fwrite($fp, '<Роль>Продавец</Роль>' . PHP_EOL);
                fwrite($fp, '<Валюта>руб</Валюта>' . PHP_EOL);
                fwrite($fp, '<Курс>1</Курс>' . PHP_EOL);
                fwrite($fp, '<Сумма>'.$item["order_summ"].'</Сумма>' . PHP_EOL);
                fwrite($fp, '<Контрагенты>' . PHP_EOL);
                fwrite($fp, '<Контрагент>' . PHP_EOL);
                fwrite($fp, '<Ид>'.$clientData["id"].'</Ид>' . PHP_EOL);
                fwrite($fp, '<Наименование>'.$clientData["naming"].'</Наименование>' . PHP_EOL);
                fwrite($fp, '<Роль>Покупатель</Роль>' . PHP_EOL);
                fwrite($fp, '<ПолноеНаименование>'.$clientData["fullnaming"].'</ПолноеНаименование>' . PHP_EOL);
                fwrite($fp, '<Фамилия>'.$clientData["secondname"].'</Фамилия>' . PHP_EOL);
                fwrite($fp, '<Имя>'.$clientData["name"].'</Имя>' . PHP_EOL);
                fwrite($fp, '<АдресРегистрации>' . PHP_EOL);
                fwrite($fp, '<Представление>ггг</Представление>' . PHP_EOL);
                fwrite($fp, '<АдресноеПоле>' . PHP_EOL);
                fwrite($fp, '<Тип>Почтовый индекс</Тип>' . PHP_EOL);
                fwrite($fp, '<Значение>1111</Значение>' . PHP_EOL);
                fwrite($fp, '</АдресноеПоле>' . PHP_EOL);
                fwrite($fp, '<АдресноеПоле>' . PHP_EOL);
                fwrite($fp, '<Тип>Улица</Тип>' . PHP_EOL);
                fwrite($fp, '<Значение>'.$clientData["address"].'</Значение>' . PHP_EOL);
                fwrite($fp, '</АдресноеПоле>' . PHP_EOL);
                fwrite($fp, '</АдресРегистрации>' . PHP_EOL);
                
                fwrite($fp, '<Контакты/>' . PHP_EOL);
                
                fwrite($fp, '<Представители>' . PHP_EOL);
                fwrite($fp, '<Представитель>' . PHP_EOL);
                fwrite($fp, '<Контрагент>' . PHP_EOL);
                fwrite($fp, '<Отношение>Контактное лицо</Отношение>' . PHP_EOL);
                fwrite($fp, '<Ид>'.$clientData["represid"].'</Ид>' . PHP_EOL);
                fwrite($fp, '<Наименование>'.$clientData["represfullname"].'</Наименование>' . PHP_EOL);
                fwrite($fp, '</Контрагент>' . PHP_EOL);
                fwrite($fp, '</Представитель>' . PHP_EOL);
                fwrite($fp, '</Представители>' . PHP_EOL);
                fwrite($fp, '</Контрагент>' . PHP_EOL);
                fwrite($fp, '</Контрагенты>' . PHP_EOL);
                
                fwrite($fp, '<Время>18:22:40</Время>' . PHP_EOL);
                fwrite($fp, '<Комментарий>'.$item["order_comment"].'</Комментарий>' . PHP_EOL);
                
                fwrite($fp, '<Товары>' . PHP_EOL);
                
                $orderContents = OrderContents::find()
                ->where(['order_id'=> $item["order_id"]])
                ->all();
                
                
                foreach ($orderContents as $product) {
                    
                    $productitem = Goods::find()
                    ->where(['good_id' => $product["good_id"]])
                    ->andWhere(['id_os'=>'cb39a73c-3d8f-48ca-8f1b-d91bb94efccc'])
                    ->one();
                    $cos = Price::find()
                    ->where(['good_id' => $product["good_id"]])
                    ->andWhere(['type_id'=>3])
                    ->one();
                    $cost = $cos->value;
                    
                    fwrite($fp, '<Товар>' . PHP_EOL);
                    fwrite($fp, '<Ид>'.$productitem["oc_ref"].'</Ид>' . PHP_EOL);
                    fwrite($fp, '<ИдКаталога>'.$productitem["cat_id_1c"].'</ИдКаталога>' . PHP_EOL);
                    fwrite($fp, '<Наименование>'.$productitem["title"].'</Наименование>' . PHP_EOL);
                    fwrite($fp, '<БазоваяЕдиница Код="796" НаименованиеПолное="Штука" МеждународноеСокращение="PCE">шт</БазоваяЕдиница>' . PHP_EOL);
                    fwrite($fp, '<ЦенаЗаЕдиницу>'.$cost.'</ЦенаЗаЕдиницу>' . PHP_EOL);
                    fwrite($fp, '<Количество>'.$product["amount"].'</Количество>' . PHP_EOL);
                    fwrite($fp, '<Сумма>'.$product["amount"] * $cost.'</Сумма>' . PHP_EOL);
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
                    fwrite($fp, '</Товар>' . PHP_EOL);
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
                fwrite($fp, '<Значение>ыфпвкуп ыкрвпек 23</Значение>' . PHP_EOL);
                fwrite($fp, '</ЗначениеРеквизита>' . PHP_EOL);
                fwrite($fp, '<ЗначениеРеквизита>' . PHP_EOL);
                fwrite($fp, '<Наименование>Комментарий к заказу</Наименование>' . PHP_EOL);
                fwrite($fp, '<Значение>ыфпвкуп ыкрвпек 23</Значение>' . PHP_EOL);
                fwrite($fp, '</ЗначениеРеквизита>' . PHP_EOL);
                fwrite($fp, '<ЗначениеРеквизита>' . PHP_EOL);
                fwrite($fp, '<Наименование>Финальный статус</Наименование>' . PHP_EOL);
                fwrite($fp, '<Значение>false</Значение>');
                fwrite($fp, '</ЗначениеРеквизита>' . PHP_EOL);
                fwrite($fp, '<ЗначениеРеквизита>' . PHP_EOL);
                fwrite($fp, '<Наименование>Статус заказа</Наименование>' . PHP_EOL);
                fwrite($fp, '<Значение>[N] Принят</Значение>' . PHP_EOL);
                fwrite($fp, '</ЗначениеРеквизита>' . PHP_EOL);
                fwrite($fp, '<ЗначениеРеквизита>' . PHP_EOL);
                fwrite($fp, '<Наименование>Дата изменения статуса</Наименование>' . PHP_EOL);
                fwrite($fp, '<Значение>2019-11-18 4:19:27</Значение>' . PHP_EOL);
                fwrite($fp, '</ЗначениеРеквизита>' . PHP_EOL);
                fwrite($fp, '</ЗначенияРеквизитов>' . PHP_EOL);
                fwrite($fp, '</Документ>');
                }
                }
            fwrite($fp, '</КоммерческаяИнформация>');
            fclose($fp);
                
                Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'text/xml');
        $xml = file_get_contents('order.xml');
        return $this->renderPartial('index', ['xml' => $xml]);
    }

    /**
     * @param $type
     * @return bool
     */
    public function actionSuccess($type)
    {
        return true;
    }

    /**
     * @param $name
     * @param $value
     */
    protected static function setData($name, $value)
    {
        Yii::$app->session->set($name, $value);
    }

    /**
     * @param $name
     * @param null $default
     * @return mixed
     */
    protected static function getData($name, $default = null)
    {
        return Yii::$app->session->get($name, $default);
    }

    /**
     * @return bool
     */
    protected static function clearData()
    {
        return Yii::$app->session->closeSession();
    }

    /**
     * @param ProductInterface $model
     * @param \Zenwalker\CommerceML\Model\Product $product
     */
    protected function parseProduct($model, $product)
    {
        $this->beforeUpdateProduct($model);
        $model->setRaw1cData($product->owner, $product);
        $this->parseGroups($model, $product);
        $this->parseProperties($model, $product);
        $this->parseRequisites($model, $product);
        $this->parseImage($model, $product);
        $this->afterUpdateProduct($model);
        unset($group);
    }

    /**
     * @param OfferInterface $model
     * @param Offer $offer
     */
    protected function parseProductOffer($model, $offer)
    {
        $this->beforeUpdateOffer($model, $offer);
        $this->parseSpecifications($model, $offer);
        $this->parsePrice($model, $offer);
        $model->{$model::getIdFieldName1c()} = $offer->id;
        $model->save();
        $this->afterUpdateOffer($model, $offer);
    }

    /**
     * @param string $id
     *
     * @return ProductInterface|null
     */
    protected function findProductModelById($id)
    {
        /**
         * @var $class ProductInterface
         */
        $class = $this->getProductClass();
        return $class::find()->andWhere([$class::getIdFieldName1c() => $id])->one();
    }

    /**
     * @param Offer $offer
     *
     * @return OfferInterface|null
     */
    protected function findOfferModel($offer)
    {
        /**
         * @var $class ProductInterface
         */
        $class = $this->getOfferClass();
        return $class::find()->andWhere([$class::getIdFieldName1c() => $offer->id])->one();
    }

    /**
     * @return ActiveRecord
     */
    protected function createProductModel($data)
    {
        $class = $this->getProductClass();
        if ($model = $class::createModel1c($data)) {
            return $model;
        }

        return Yii::createObject(['class' => $class]);
    }

    /**
     * @param OfferInterface $model
     * @param Offer $offer
     */
    protected function parsePrice($model, $offer)
    {
        foreach ($offer->getPrices() as $price) {
            $model->setPrice1c($price);
        }
    }

    /**
     * @param ProductInterface $model
     * @param Product $product
     */
    protected function parseImage($model, $product)
    {
        $images = $product->getImages();
        foreach ($images as $image) {
            $path = realpath($this->module->getTmpDir() . DIRECTORY_SEPARATOR . $image->path);
            if (file_exists($path)) {
                $model->addImage1c($path, $image->caption);
            }
        }
    }

    /**
     * @param ProductInterface $model
     * @param Product $product
     */
    protected function parseGroups($model, $product)
    {
        $group = $product->getGroup();
        $model->setGroup1c($group);
    }

    /**
     * @param ProductInterface $model
     * @param Product $product
     */
    protected function parseRequisites($model, $product)
    {
        $requisites = $product->getRequisites();
        foreach ($requisites as $requisite) {
            $model->setRequisite1c($requisite->name, $requisite->value);
        }
    }

    /**
     * @param OfferInterface $model
     * @param Offer $offer
     */
    protected function parseSpecifications($model, $offer)
    {
        foreach ($offer->getSpecifications() as $specification) {
            $model->setSpecification1c($specification);
        }
    }

    /**
     * @param ProductInterface $model
     * @param Product $product
     */
    protected function parseProperties($model, $product)
    {
        $properties = $product->getProperties();
        foreach ($properties as $property) {
            $model->setProperty1c($property);
        }
    }

    /**
     * @return OfferInterface
     */
    protected function getOfferClass()
    {
        return $this->module->offerClass;
    }

    /**
     * @return ProductInterface
     */
    protected function getProductClass()
    {
        return $this->module->productClass;
    }

    /**
     * @return DocumentInterface
     */
    protected function getDocumentClass()
    {
        return $this->module->documentClass;
    }

    /**
     * @return \carono\exchange1c\interfaces\GroupInterface
     */
    protected function getGroupClass()
    {
        return $this->module->groupClass;
    }

    /**
     * @return bool
     */
    public function actionError()
    {
        return false;
    }

    /**
     * @param $filePath
     */
    public function afterFinishUploadFile($filePath)
    {
        $this->module->trigger(self::EVENT_AFTER_FINISH_UPLOAD_FILE, new ExchangeEvent(['filePath' => $filePath]));
    }

    public function beforeProductSync()
    {
        $this->module->trigger(self::EVENT_BEFORE_PRODUCT_SYNC, new ExchangeEvent());
    }

    public function afterProductSync()
    {
        $this->module->trigger(self::EVENT_AFTER_PRODUCT_SYNC, new ExchangeEvent(['ids' => $this->_ids]));
    }

    public function beforeOfferSync()
    {
        $this->module->trigger(self::EVENT_BEFORE_OFFER_SYNC, new ExchangeEvent());
    }

    public function afterOfferSync()
    {
        $this->module->trigger(self::EVENT_AFTER_OFFER_SYNC, new ExchangeEvent(['ids' => $this->_ids]));
    }

    /**
     * @param $model
     */
    public function afterUpdateProduct($model)
    {
        $this->module->trigger(self::EVENT_AFTER_UPDATE_PRODUCT, new ExchangeEvent(['model' => $model]));
    }

    /**
     * @param $model
     */
    public function beforeUpdateProduct($model)
    {
        $this->module->trigger(self::EVENT_BEFORE_UPDATE_PRODUCT, new ExchangeEvent(['model' => $model]));
    }

    /**
     * @param $model
     * @param $offer
     */
    public function beforeUpdateOffer($model, $offer)
    {
        $this->module->trigger(self::EVENT_BEFORE_UPDATE_OFFER, new ExchangeEvent([
            'model' => $model,
            'ml' => $offer,
        ]));
    }

    /**
     * @param $model
     * @param $offer
     */
    public function afterUpdateOffer($model, $offer)
    {
        $this->module->trigger(self::EVENT_AFTER_UPDATE_OFFER, new ExchangeEvent(['model' => $model, 'ml' => $offer]));
    }

    /**
     * @param $ids
     */
    public function afterExportOrders($ids)
    {
        $this->module->trigger(self::EVENT_AFTER_EXPORT_ORDERS, new ExchangeEvent(['ids' => $ids]));
    }
}
