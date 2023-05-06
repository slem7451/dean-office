<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * DocumentToStudent model
 *
 * @property integer $document_id
 * @property integer $student_id
 */

class DocumentToStudent extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%document_to_student}}';
    }
}