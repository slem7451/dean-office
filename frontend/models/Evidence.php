<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * Evidence model
 *
 * @property string $evidence_name
 * @property integer $user_id
 * @property string $description
 */

class Evidence extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%evidence}}';
    }
}