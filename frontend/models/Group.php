<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * Group model
 *
 * @property integer $id
 * @property string $name
 * @property date $created_at
 * @property date $closed_at
 */

class Group extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%group}}';
    }

    public static function findGroups()
    {
        return self::find();
    }

    public static function findAllGroups()
    {
        return self::find()->all();
    }
}