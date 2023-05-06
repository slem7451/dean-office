<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * Direction model
 *
 * @property string $id
 * @property string $name
 */

class Direction extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%direction}}';
    }
}