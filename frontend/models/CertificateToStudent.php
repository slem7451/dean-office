<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * CertificateToStudent model
 *
 * @property integer $certificate_id
 * @property integer $student_id
 */

class CertificateToStudent extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%certificate_to_student}}';
    }
}