<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * Student model
 *
 * @property integer $id
 * @property string $first_name
 * @property string $second_name
 * @property string $patronymic
 * @property string $sex
 * @property string $phone
 * @property date $birthdate
 * @property timestamp $created_at
 * @property timestamp $closed_at
 */

class Student extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%student}}';
    }
}