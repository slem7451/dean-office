<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * StudentView model
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

class StudentView extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%student_view}}';
    }

    public static function primaryKey()
    {
        return ['id', 'closed_at', 'created_at'];
    }

    public function getToGroup()
    {
        return $this->hasOne(StudentToGroup::class, ['student_id' => 'id', 'closed_at' => 'closed_at']);
    }

    public function getGroup()
    {
        return $this->hasOne(Group::class, ['id' => 'group_id'])->via('toGroup');
    }

    public static function findStudents()
    {
        return self::find()->with('group');
    }

    public static function findStudentsNotInGroup($id)
    {
        return self::find()
            ->leftJoin('student_to_group', 'student_to_group.student_id = student_view.id and student_to_group.closed_at = student_view.closed_at')
            ->leftJoin('public.group', 'public.group.id = student_to_group.group_id')
            ->where(['!=', 'public.group.id', $id])
            ->all();
    }

    public static function findStudent($id)
    {
        return self::findOne(['id' => $id]);
    }

    public static function findStudentsByText($text)
    {

        return self::find()->where(['ilike', 'CONCAT_WS(second_name, first_name, patronymic)', $text]);
    }

    public static function findAllStudentsForSearch()
    {
        $students = self::find()->all();
        $result = [];
        foreach ($students as $student) {
            $result[] = ['value' => $student->id, 'label' => $student->second_name . ' ' . $student->first_name . ($student->patronymic ? ' ' . $student->patronymic : '')];
        }
        return $result;
    }

    public static function deleteStudent($id)
    {
        $student = self::findOne(['id' => $id]);
        $studentToGroup = StudentToGroup::findAll(['student_id' => $id]);
        $success = true;
        foreach ($studentToGroup as $item) {
            $success *= $item->delete();
        }
        $success *= $student->delete();
        return $success;
    }

    public static function findStudentsByGroupId($id)
    {
        return self::find()
            ->leftJoin('student_to_group', 'student_to_group.student_id = student_view.id')
            ->leftJoin('public.group', 'public.group.id = student_to_group.group_id')
            ->where(['public.group.id' => $id]);
    }
}