<?php

namespace frontend\models;

use yii\db\ActiveRecord;
use yii\db\Expression;

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

    public static function primaryKey()
    {
        return ['student_id', 'group_id'];
    }

    public static function addStudents($id, $students)
    {
        $success = true;
        foreach ($students as $student) {
            $studentToGroup = StudentToGroup::find()->where(['student_id' => $student, "DATE_PART('year', closed_at)" => 3000])->one();
            $studentToGroup->closed_at = new Expression('NOW()');
            $success *= $studentToGroup->save();

            $studentToGroup = new StudentToGroup();
            $studentToGroup->group_id = $id;
            $studentToGroup->student_id = $student;
            $success *= $studentToGroup->save();
        }
        return $success;
    }
}