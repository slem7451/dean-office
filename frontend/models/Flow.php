<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * Flow model
 *
 * @property integer $id
 * @property string $name
 * @property date $created_at
 * @property date $closed_at
 */

class Flow extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%flow}}';
    }
}