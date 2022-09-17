<?php

namespace common\models;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "product".
 */
class OrdersMain extends ActiveRecord
{
    public static function tableName()
    {
        return 'orders_main';
    }
    
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_datetime', 'updated_datetime'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_datetime'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }
}
