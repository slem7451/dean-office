<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * DecreeToStudent model
 *
 * @property integer $decree_id
 * @property integer $student_id
 * @property date $created_at
 * @property date $closed_at
 */

class DecreeToStudent extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%decree_to_student}}';
    }
}