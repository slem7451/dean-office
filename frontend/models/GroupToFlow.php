<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * GroupToFlow model
 *
 * @property integer $group_id
 * @property integer $flow_id
 */

class GroupToFlow extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%group_to_flow}}';
    }
}