<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * AcademicDegree model
 *
 * @property integer $id
 * @property string $name
 */

class AcademicDegree extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%academic_degree}}';
    }
}