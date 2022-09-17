<?php

namespace common\models;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "product".
 */
class Groups extends ActiveRecord
{
    public static function tableName()
    {
        return 'groups';
    }
}
