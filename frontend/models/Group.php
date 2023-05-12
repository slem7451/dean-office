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

    public function getToStudents()
    {
        return $this->hasMany(StudentToGroup::class, ['group_id' => 'id']);
    }

    public function getStudents()
    {
        return $this->hasMany(Student::class, ['id' => 'student_id'])->via('toStudents');
    }

    public static function findGroups($name = null, $flow = null, $direction = null, $closed_at = null)
    {
        $groups = self::find()->joinWith(['flow', 'direction'])->with(['students']);
        if ($name) {
            $groups->andWhere(['ilike', 'public.group.name', $name]);
        }
        if ($flow) {
            $groups->andWhere(['flow.id' => $flow]);
        }
        if ($direction) {
            $groups->andWhere(['direction.id' => $direction]);
        }
        if ($closed_at) {
            switch ($closed_at) {
                case 1:
                    $groups->andWhere(['is', 'public.group.closed_at', new Expression('null')]);
                    break;
                case 2:
                    $groups->andWhere(['is not', 'public.group.closed_at', new Expression('null')]);
                    break;
                default:
                    break;
            }
        }
        return $groups;
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

    public static function findFlowsGroups($id, $name = null, $direction = null, $closed_at = null)
    {
        $groups = self::find()
            ->leftJoin('group_to_flow', 'public.group.id = group_to_flow.group_id')
            ->leftJoin('flow', 'flow.id = group_to_flow.flow_id')
            ->joinWith('direction')
            ->where(['group_to_flow.flow_id' => $id]);
        if ($name) {
            $groups->andWhere(['ilike', 'public.group.name', $name]);
        }
        if ($direction) {
            $groups->andWhere(['direction.id' => $direction]);
        }
        if ($closed_at) {
            switch ($closed_at) {
                case 1:
                    $groups->andWhere(['is', 'public.group.closed_at', new Expression('null')]);
                    break;
                case 2:
                    $groups->andWhere(['is not', 'public.group.closed_at', new Expression('null')]);
                    break;
                default:
                    break;
            }
        }
        return $groups;
    }

    public static function getStatistic()
    {
        $statistic = [];
        $groups = self::find()->where(["DATE_PART('year', created_at)" => date('Y')])->all();
        foreach ($groups as $group) {
            $statistic[] = [
                'name' => $group->name,
                'studentCount' => StudentToGroup::find()->where(['group_id' => $group->id])->andWhere(['is', 'closed_at', new Expression('null')])->count()
            ];
        }
        return $statistic;
    }
}