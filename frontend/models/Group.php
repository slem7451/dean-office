<?php

namespace frontend\models;

use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Group model
 *
 * @property integer $id
 * @property string $name
 * @property string $direction_id
 * @property integer $academic_id
 * @property date $created_at
 * @property date $closed_at
 */

class Group extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%group}}';
    }

    public function getToFlow()
    {
        return $this->hasOne(GroupToFlow::class, ['group_id' => 'id']);
    }

    public function getFlow()
    {
        return $this->hasOne(Flow::class, ['id' => 'flow_id'])->via('toFlow');
    }

    public function getDirection()
    {
        return $this->hasOne(Direction::class, ['id' => 'direction_id']);
    }

    public function getAcademicDegree()
    {
        return $this->hasOne(AcademicDegree::class, ['id' => 'academic_id']);
    }

    public function getToStudents()
    {
        return $this->hasMany(StudentToGroup::class, ['group_id' => 'id']);
    }

    public function getStudents()
    {
        return $this->hasMany(Student::class, ['id' => 'student_id'])->via('toStudents');
    }

    public static function findGroups()
    {
        return self::find()->with(['flow', 'direction', 'academicDegree', 'students']);
    }

    public static function findAllGroups()
    {
        return self::find()->all();
    }

    public static function findGroup($id)
    {
        return self::findOne(['id' => $id]);
    }

    public static function closeGroup($id)
    {
        $group = self::findOne(['id' => $id]);
        $group->closed_at = new Expression('NOW()');
        return $group->save();
    }

    public static function findAllNotClosedGroups()
    {
        return self::find()->where(['is', 'closed_at', new Expression('null')])->all();
    }

    public static function findFlowsGroups($id)
    {
        return self::find()
            ->leftJoin('group_to_flow', 'public.group.id = group_to_flow.group_id')
            ->leftJoin('flow', 'flow.id = group_to_flow.flow_id')
            ->where(['group_to_flow.flow_id' => $id]);
    }
}