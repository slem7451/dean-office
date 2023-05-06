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
        return $this->hasMany(Group::class, ['group_id' => 'id'])->via('toGroups');
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
        $flow = self::findOne(['id' => $id]);
        $flow->closed_at = new Expression('NOW()');
        return $flow->save();
    }
}