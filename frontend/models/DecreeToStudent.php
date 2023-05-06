<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * DecreeToStudent model
 *
 * @property integer $decree_id
 * @property integer $student_id
 */

class DecreeToStudent extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%decree_to_student}}';
    }
}