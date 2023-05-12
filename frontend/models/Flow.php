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
    public $flow_year;

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

    public static function findFlows($name = null, $year = null, $closed_at = null)
    {
        $flows = self::find()->with('groups');
        if ($name) {
            $flows->andWhere(['ilike', 'name', $name]);
        }
        if ($year) {
            $flows->andWhere(["DATE_PART('year', created_at)" => $year]);
        }
        if ($closed_at) {
            switch ($closed_at) {
                case 1:
                    $flows->andWhere(['is', 'closed_at', new Expression('null')]);
                    break;
                case 2:
                    $flows->andWhere(['is not', 'closed_at', new Expression('null')]);
                    break;
                default:
                    break;
            }
        }
        return $flows;
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

    public static function findAllFlows()
    {
        return self::find()->all();
    }

    public static function getYearFlows()
    {
        return self::find()->select(["DATE_PART('year', created_at) as flow_year"])->groupBy(["DATE_PART('year', created_at)"])->all();
    }
}