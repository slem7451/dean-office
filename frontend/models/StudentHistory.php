<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * StudentHistory model
 *
 * @property integer $id
 * @property string $first_name
 * @property string $second_name
 * @property string $patronymic
 * @property string $sex
 * @property string $phone
 * @property tinyInteger $payment
 * @property date $birthdate
 * @property date $created_at
 * @property date $closed_at
 * @property date $updated_at
 * @property tinyInteger $operation
 */

class StudentHistory extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%student_history}}';
    }

    public static function findHistory($id)
    {
        return self::find()->where(['id' => $id]);
    }
}