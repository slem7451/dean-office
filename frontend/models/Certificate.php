<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * Certificate model
 *
 * @property integer $id
 * @property string $template_id
 * @property date $created_at
 */

class Certificate extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%certificate}}';
    }
}