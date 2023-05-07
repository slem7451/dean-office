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

    public static function findAcademicDegrees()
    {
        return self::find();
    }

    public static function findAcademicDegree($id)
    {
        return self::findOne(['id' => $id]);
    }

    public static function deleteAcademicDegree($id)
    {
        $academicDegree = self::findOne(['id' => $id]);
        $groups = Group::findAll(['academic_id' => $id]);
        if (count($groups)) {
            return false;
        }
        return $academicDegree->delete();
    }

    public static function findAllAcademicDegrees()
    {
        return self::find()->all();
    }
}