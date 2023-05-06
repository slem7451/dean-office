<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * Decree model
 *
 * @property integer $id
 * @property string $template_id
 * @property date $created_at
 */

class Decree extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%decree}}';
    }
}