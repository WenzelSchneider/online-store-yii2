<?php

namespace frontend\models;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "product".
 */
class Goods extends ActiveRecord
{
    public static function tableName()
    {
        return 'goods';
    }
}
