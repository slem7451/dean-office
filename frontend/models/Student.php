<?php

namespace frontend\models;

use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Student model
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
 */

class Student extends ActiveRecord
{
    const BUDGET_PAYMENT = 0;
    const CONTRACT_PAYMENT = 1;

    public static function tableName()
    {
        return '{{%student}}';
    }

    public function getToGroup()
    {
        return $this->hasOne(StudentToGroup::class, ['student_id' => 'id'])->where(['is', 'student_to_group.closed_at', new Expression('null')]);
    }

    public function getGroup()
    {
        return $this->hasOne(Group::class, ['id' => 'group_id'])->via('toGroup');
    }

    public static function findAllStudentsForSearch()
    {
        $students = self::find()->where(['is', 'closed_at', new Expression('null')])->all();
        $result = [];
        foreach ($students as $student) {
            $result[] = ['value' => $student->id, 'label' => $student->second_name . ' ' . $student->first_name . ($student->patronymic ? ' ' . $student->patronymic : '') . ' (' . $student->id . ')'];
        }
        return $result;
    }

    public static function findStudent($id)
    {
        return self::findOne(['id' => $id]);
    }

    public static function findStudents()
    {
        return self::find()->with('group');
    }

    public static function findStudentsByText($text)
    {
        return self::find()->where(['ilike', 'CONCAT_WS(second_name, first_name, patronymic)', $text]);
    }

    public static function findStudentsByGroupId($id)
    {
        return self::find()
            ->leftJoin('student_to_group', 'student_to_group.student_id = student.id')
            ->leftJoin('public.group', 'public.group.id = student_to_group.group_id')
            ->where(['public.group.id' => $id]);
    }

    public static function findStudentsNotInGroup($id)
    {
        return self::find()
            ->leftJoin('student_to_group', 'student_to_group.student_id = student.id')
            ->leftJoin('public.group', 'public.group.id = student_to_group.group_id')
            ->where(['!=', 'public.group.id', $id])
            ->all();
    }

    public static function closeStudent($id)
    {
        $student = Student::findOne(['id' => $id]);
        $student->closed_at = new Expression('NOW()');
        return $student->save();
    }

    public static function openStudent($id)
    {
        $student = Student::findOne(['id' => $id]);
        $student->closed_at = new Expression('null');
        return $student->save();
    }
}