<?php

namespace frontend\models;

use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Flow model
 *
 * @property integer $id
 * @property string $name
 * @property date $created_at
 * @property date $closed_at
 */

class Flow extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%flow}}';
    }

    public function getToGroups()
    {
        return $this->hasMany(GroupToFlow::class, ['flow_id' => 'id']);
    }

    public function getGroups()
    {
        return $this->hasMany(Group::class, ['id' => 'group_id'])->via('toGroups');
    }

    public static function findFlows()
    {
        return self::find()->with('groups');
    }

    public static function findFlow($id)
    {
        return self::findOne(['id' => $id]);
    }

    public static function closeFlow($id)
    {
        $success = true;
        $flow = self::findOne(['id' => $id]);
        $flow->closed_at = new Expression('NOW()');
        $groupToFlows = GroupToFlow::findAll(['flow_id' => $id]);
        foreach ($groupToFlows as $groupToFlow) {
            $group = Group::find()->where(['id' => $groupToFlow->group_id])->andWhere(['is', 'closed_at', new Expression('null')])->one();
            if ($group) {
                $success *= Group::closeGroup($groupToFlow->group_id);
            }
        }
        return $flow->save() * $success;
    }

    public static function findAllNotClosedFlows()
    {
        return self::find()->where(['is', 'closed_at', new Expression('null')])->all();
    }
}