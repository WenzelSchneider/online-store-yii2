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
class LogErrors extends ActiveRecord
{
    public static function tableName()
    {
        return 'log_errors';
    }
}
