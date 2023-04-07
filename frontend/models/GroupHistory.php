<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * GroupHistory model
 *
 * @property integer $group_id
 * @property string $name
 * @property date $created_at
 * @property date $closed_at
 * @property timestamp $updated_at
 * @property tinyInteger $operation
 */

class GroupHistory extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%group_history}}';
    }
}