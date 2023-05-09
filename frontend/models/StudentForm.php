<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\db\Expression;
use yii\web\UploadedFile;

class StudentForm extends Model
{
    const MALE = 'm';
    const FEMALE = 'f';

    public $first_name;
    public $second_name;
    public $patronymic;
    public $group;
    public $birthdate;
    public $sex;
    public $phone;
    public $payment_type;
    public $created_at;
    public $document;

    public function rules()
    {
        return [
            ['first_name', 'required', 'message' => 'Обязательно для заполнения'],
            ['first_name', 'string'],
            ['first_name', 'trim'],

            ['second_name', 'required', 'message' => 'Обязательно для заполнения'],
            ['second_name', 'string'],
            ['second_name', 'trim'],

            ['patronymic', 'string'],
            ['patronymic', 'trim'],

            ['group', 'required', 'message' => 'Обязательно для заполнения'],
            ['group', 'string'],
            ['group', 'trim'],

            ['birthdate', 'required', 'message' => 'Обязательно для заполнения'],
            ['birthdate', 'date', 'format' => 'php:Y-m-d'],

            ['sex', 'required', 'message' => 'Обязательно для заполнения'],
            ['sex', 'in', 'range' => [self::FEMALE, self::MALE], 'message' => 'Некорректный пол'],

            ['phone', 'required', 'message' => 'Обязательно для заполнения'],
            ['phone', 'match', 'pattern' => '/^\+7\([0-9]{3}\)[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/', 'message' => 'Некорректный телефон'],

            ['payment_type', 'required', 'message' => 'Обязательно для заполнения'],
            ['payment_type', 'in', 'range' => [Student::BUDGET_PAYMENT, Student::CONTRACT_PAYMENT], 'message' => 'Некорректный ввод'],

            ['created_at', 'required', 'message' => 'Обязательно для заполнения'],
            ['created_at', 'date', 'format' => 'php:Y-m-d'],

            ['document', 'required', 'whenClient' => "function (attribute, value) {
                return $('#btn-clicked').html() == '0';
            }", 'message' => 'Обязательно для заполнения'],
        ];
    }

    public function saveStudent()
    {
        $group = Group::findOne(['id' => $this->group]);
        $success = true;
        if ($group) {
            $student = new Student();
            $student->first_name = $this->first_name;
            $student->second_name = $this->second_name;
            $student->patronymic = $this->patronymic;
            $student->sex = $this->sex;
            $student->phone = $this->phone;
            $student->birthdate = $this->birthdate;
            $student->created_at = $this->created_at;
            $student->payment = $this->payment_type;
            $success *= $student->save();

            $studentToGroup = new StudentToGroup();
            $studentToGroup->group_id = $this->group;
            $studentToGroup->student_id = $student->id;
            $studentToGroup->created_at = $student->created_at;
            $success *= $studentToGroup->save();

            foreach ($this->document as $num => $item) {
                $file = UploadedFile::getInstance($this, 'document[' . $num . '][scan-c]');
                $time = time();
                $file->saveAs(Yii::getAlias('@frontend') .'/web/uploads/' . $student->id . '_' . $file->baseName . '_' . $time . '.' . $file->extension);
                $document = new Document();
                $document->name = $item['name-c'];
                $document->scan = $student->id . '_' . $file->baseName . '_' . $time . '.' . $file->extension;
                $success *= $document->save();

                $documentToStudent = new DocumentToStudent();
                $documentToStudent->student_id = $student->id;
                $documentToStudent->document_id = $document->id;
                $success *= $documentToStudent->save();
            }
        }
        return $success;
    }

    public function loadFromDB($student)
    {
        $this->first_name = $student->first_name;
        $this->second_name = $student->second_name;
        $this->patronymic = $student->patronymic;
        $this->group = $student->group->id;
        $this->birthdate = $student->birthdate;
        $this->sex = $student->sex;
        $this->phone = $student->phone;
        $this->payment_type = $student->payment;
        $this->created_at = $student->created_at;
        $documents = [];
        $documentToStudent = DocumentToStudent::findAll(['student_id' => $student->id]);
        foreach ($documentToStudent as $item) {
            $document = Document::findOne(['id' => $item->document_id]);
            $documents[] = $document->scan;
        }
        return $documents;
    }

    public function updateStudent($id)
    {
        $group = Group::findOne(['id' => $this->group]);
        $student = Student::findOne(['id' => $id]);
        $studentToGroup = StudentToGroup::find()
            ->where(['student_id' => $student->id])
            ->andWhere(['is', 'closed_at', new Expression('null')])
            ->one();
        $success = true;
        if ($group && $student && $studentToGroup) {
            $student->first_name = $this->first_name;
            $student->second_name = $this->second_name;
            $student->patronymic = $this->patronymic;
            $student->sex = $this->sex;
            $student->phone = $this->phone;
            $student->birthdate = $this->birthdate;
            $student->created_at = $this->created_at;
            $student->payment = $this->payment_type;
            $success *= $student->save();

            if ($student->group->id != $this->group) {
                $studentToGroup->closed_at = new Expression('NOW()');
                $success *= $studentToGroup->save();

                $studentToGroup = new StudentToGroup();
                $studentToGroup->group_id = $this->group;
                $studentToGroup->student_id = $student->id;
                $studentToGroup->created_at = new Expression('NOW()');
                $success *= $studentToGroup->save();
            }

            if($this->document[0]['name-u'] != '' && UploadedFile::getInstance($this, 'document[0][scan-u]')) {
                DocumentToStudent::deleteAll(['student_id' => $student->id]);
                foreach ($this->document as $num => $item) {
                    $file = UploadedFile::getInstance($this, 'document[' . $num . '][scan-u]');
                    if ($file && $item['name-u'] != '') {
                        $time = time();
                        $file->saveAs(Yii::getAlias('@frontend') . '/web/uploads/' . $student->id . '_' . $file->baseName . '_' . $time . '.' . $file->extension);
                        $document = new Document();
                        $document->name = $item['name-u'];
                        $document->scan = $student->id . '_' . $file->baseName . '_' . $time . '.' . $file->extension;
                        $success *= $document->save();

                        $documentToStudent = new DocumentToStudent();
                        $documentToStudent->student_id = $student->id;
                        $documentToStudent->document_id = $document->id;
                        $success *= $documentToStudent->save();
                    }
                }
            }
        }
        return $success;
    }
}