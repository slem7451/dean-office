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

    public function getToGroup()
    {
        return $this->hasOne(StudentToGroup::class, ['student_id' => 'id', 'created_at' => 'created_at', 'closed_at' => 'closed_at']);
    }

    public function getGroup()
    {
        return $this->hasOne(Group::class, ['id' => 'group_id'])->via('toGroup');
    }

    public static function findStudents()
    {
        return self::find()->with('group');
    }
}