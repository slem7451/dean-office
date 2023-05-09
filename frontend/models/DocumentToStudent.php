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

    public static function primaryKey()
    {
        return ['student_id', 'document_id'];
    }

    public static function findDoucments($id)
    {
        $documents = [];
        $documentToStudents = self::findAll(['student_id' => $id]);
        foreach ($documentToStudents as $documentToStudent) {
            $documents[] = Document::findOne(['id' => $documentToStudent->document_id]);
        }
        return $documents;
    }

    public static function deleteDocument($student_id, $document_id)
    {
        $documentToStudent = DocumentToStudent::findOne(['student_id' => $student_id, 'document_id' => $document_id]);
        return $documentToStudent->delete();
    }
}