<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * StudentHistory model
 *
 * @property integer $student_id
 * @property string $first_name
 * @property string $second_name
 * @property string $patronymic
 * @property date $birthdate
 * @property timestamp $created_at
 * @property timestamp $closed_at
 * @property timestamp $updated_at
 * @property tinyInteger $operation
 */

class StudentHistory extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%student_history}}';
    }
}