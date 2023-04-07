<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * StudentToGroup model
 *
 * @property integer $student_id
 * @property integer $group_id
 * @property timestamp $created_at
 * @property timestamp $closed_at
 */

class StudentToGroup extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%student_to_group}}';
    }
}