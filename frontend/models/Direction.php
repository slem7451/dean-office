<?php

namespace frontend\models;

use common\helpers\StatisticHelper;
use yii\db\ActiveRecord;

/**
 * Direction model
 *
 * @property string $id
 * @property string $full_name
 * @property string $short_name
 * @property string $academic_name
 * @property string $profile
 */

class Direction extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%direction}}';
    }

    public static function findDirections()
    {
        return self::find();
    }

    public static function deleteDirection($id)
    {
        $direction = self::findOne(['id' => $id]);
        $groups = Group::findAll(['direction_id' => $id]);
        if (count($groups)) {
            return false;
        }
        return $direction->delete();
    }

    public static function findDirection($id)
    {
        return self::findOne(['id' => $id]);
    }

    public static function findAllDirections()
    {
        return self::find()->all();
    }

    public static function getStatistic($year)
    {
        $statistic = [];
        $directions = self::find()->all();
        foreach ($directions as $direction) {
            $groupCount = Group::find()->where(["DATE_PART('year', created_at)" => $year, 'direction_id' => $direction->id])->count();
            $statistic[] = [
                'name' => $direction->id . ' ' . $direction->short_name,
                'count' => $groupCount,
                'color' => StatisticHelper::getRandomColor(),
                'id' => $direction->id
            ];
        }
        return $statistic;
    }
}