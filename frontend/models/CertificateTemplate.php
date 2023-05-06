<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * CertificateTemplate model
 *
 * @property string $id
 * @property string $name
 * @property text $template
 */

class CertificateTemplate extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%certificate_template}}';
    }
}