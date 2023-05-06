<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * Document model
 *
 * @property integer $id
 * @property string $name
 * @property string $scan
 */

class Document extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%document}}';
    }
}