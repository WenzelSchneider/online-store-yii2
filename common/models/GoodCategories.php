<?php

namespace common\models;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "product".
 */
class GoodCategories extends ActiveRecord
{
    public static function tableName()
    {
        return 'good_categories';
    }
}
