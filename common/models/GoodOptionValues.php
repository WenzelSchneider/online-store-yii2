<?php

namespace common\models;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "product".
 */
class GoodOptionValues extends ActiveRecord
{
    public static function tableName()
    {
        return 'good_option_values';
    }
}
