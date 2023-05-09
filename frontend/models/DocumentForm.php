<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class DocumentForm extends Model
{
    public $document;

    public function rules()
    {
        return [
            ['document', 'required', 'message' => 'Обязательно для заполнения']
        ];
    }

    public function saveDocument($id)
    {
        $success = true;
        foreach ($this->document as $num => $item) {
            $file = UploadedFile::getInstance($this, 'document[' . $num . '][scan-c]');
            $time = time();
            $file->saveAs(Yii::getAlias('@frontend') .'/web/uploads/' . $id . '_' . $file->baseName . '_' . $time . '.' . $file->extension);
            $document = new Document();
            $document->name = $item['name-c'];
            $document->scan = $id . '_' . $file->baseName . '_' . $time . '.' . $file->extension;
            $success *= $document->save();

            $documentToStudent = new DocumentToStudent();
            $documentToStudent->student_id = $id;
            $documentToStudent->document_id = $document->id;
            $success *= $documentToStudent->save();
        }
        return $success;
    }

    public function updateDocument($student_id, $document_id)
    {
        $document = Document::findOne(['id' => $document_id]);
        $file = UploadedFile::getInstance($this, 'document[' . 0 . '][scan-u]');
        $time = time();
        $file->saveAs(Yii::getAlias('@frontend') .'/web/uploads/' . $student_id . '_' . $file->baseName . '_' . $time . '.' . $file->extension);
        $document->scan = $student_id . '_' . $file->baseName . '_' . $time . '.' . $file->extension;
        $document->name = $this->document[0]['name-u'];
        return $document->save();
    }
}