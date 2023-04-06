<?php

namespace frontend\models;

use yii\db\ActiveRecord;

class StudentView extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%student_view}}';
    }
}