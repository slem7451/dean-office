<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * DecreeTemplate model
 *
 * @property string $id
 * @property string $name
 * @property text $template
 */

class DecreeTemplate extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%decree_template}}';
    }
}