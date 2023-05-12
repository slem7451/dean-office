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

    public static function findStudents($full_name = null, $group = null, $closed_at = null)
    {
        $students = self::find()->joinWith('group');
        if ($full_name) {
            $students->andWhere(['ilike', 'CONCAT_WS(second_name, first_name, patronymic)', $full_name]);
        }
        if ($closed_at) {
            switch ($closed_at) {
                case 1:
                    $students->andWhere(['is', 'student.closed_at', new Expression('null')]);
                    break;
                case 2:
                    $students->andWhere(['is not', 'student.closed_at', new Expression('null')]);
                    break;
                default:
                    break;
            }
        }
        if ($group) {
            $students->andWhere(['group_id' => $group]);
        }
        return $students;
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

    public static function findAllNotClosedStudents()
    {
        return self::find()->where(['is', 'closed_at', new Expression('null')])->all();
    }

    public static function findStudentsInFlow($id)
    {
        return self::find()
            ->leftJoin('student_to_group', 'student.id = student_to_group.student_id')
            ->leftJoin('group_to_flow', 'group_to_flow.group_id = student_to_group.group_id')
            ->where(['group_to_flow.flow_id' => $id])
            ->andWhere(['is', 'student.closed_at', new Expression('null')])
            ->all();
    }

    public static function getStatistic()
    {
        $closedStudents = Student::find()->where(["DATE_PART('year', closed_at)" => date('Y')])->count();
        $openedStudents = Student::find()
            ->where(["DATE_PART('year', created_at)" => date('Y')])
            ->andWhere(['is', 'closed_at', new Expression('null')])
            ->count();
        $budgetStudents = Student::find()
            ->where(["DATE_PART('year', created_at)" => date('Y'), 'payment' => self::BUDGET_PAYMENT])
            ->andWhere(['is', 'closed_at', new Expression('null')])
            ->count();
        $contractStudents = Student::find()
            ->where(["DATE_PART('year', created_at)" => date('Y'), 'payment' => self::CONTRACT_PAYMENT])
            ->andWhere(['is', 'closed_at', new Expression('null')])
            ->count();
        return [
            'closedStudents' => $closedStudents,
            'openedStudents' => $openedStudents,
            'budgetStudents' => $budgetStudents,
            'contractStudents' => $contractStudents
        ];
    }
}