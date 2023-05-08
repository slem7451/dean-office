<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * CertificateToStudent model
 *
 * @property integer $certificate_id
 * @property integer $student_id
 * @property date $created_at
 * @property date $closed_at
 */

class CertificateToStudent extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%certificate_to_student}}';
    }
}