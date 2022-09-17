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
class ClientCommon extends ActiveRecord
{
    public static function tableName()
    {
        return 'client_common';
    }
    
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_datetime'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }
}
