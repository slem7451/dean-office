<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * Direction model
 *
 * @property string $id
 * @property string $name
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
}