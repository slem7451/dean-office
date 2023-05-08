<?php

namespace frontend\models;

use yii\base\Model;

class CertificateForm extends Model
{
    public $template_id;
    public $created_at;
    public $closed_at;
    public $students;
    public $added_at;

    public function rules()
    {
        return [
            ['template_id', 'required', 'message' => 'Обязательно для заполнения'],

            ['created_at', 'required', 'message' => 'Обязательно для заполнения'],
            ['created_at', 'date', 'format' => 'php:Y-m-d'],

            ['closed_at', 'date', 'format' => 'php:Y-m-d'],

            ['students', 'required', 'message' => 'Обязательно для заполнения'],

            ['added_at', 'required', 'message' => 'Обязательно для заполнения'],
            ['added_at', 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    public function saveCertificate()
    {
        $success = true;
        $certificate = new Certificate();
        $certificate->template_id = $this->template_id;
        $certificate->created_at = $this->added_at;
        if ($certificate->save()) {
            foreach ($this->students as $student) {
                $certificateToStudent = new CertificateToStudent();
                $certificateToStudent->student_id = $student;
                $certificateToStudent->certificate_id = $certificate->id;
                $certificateToStudent->created_at = $this->created_at;
                $certificateToStudent->closed_at = $this->closed_at;
                $success *= $certificateToStudent->save();
            }
            return $success;
        } else {
            return false;
        }
    }

    public function loadFromDB($certificate)
    {
        $this->template_id = $certificate->template_id;
        $this->added_at = $certificate->created_at;
        $certificateToStudents = CertificateToStudent::findAll(['certificate_id' => $certificate->id]);
        foreach ($certificateToStudents as $certificateToStudent) {
            $this->students[] = $certificateToStudent->student_id;
        }
        $this->closed_at = $certificateToStudents[0]->closed_at;
        $this->created_at = $certificateToStudents[0]->created_at;
    }

    public function updateCertificate($id)
    {
        $success = true;
        $certificate = Certificate::findOne(['id' => $id]);
        $certificate->created_at = $this->added_at;
        $certificate->template_id = $this->template_id;
        if ($certificate->save()) {
            $success *= CertificateToStudent::deleteAll(['certificate_id' => $certificate->id]);
            foreach ($this->students as $student) {
                $certificateToStudent = new CertificateToStudent();
                $certificateToStudent->student_id = $student;
                $certificateToStudent->certificate_id = $certificate->id;
                $certificateToStudent->created_at = $this->created_at;
                $certificateToStudent->closed_at = $this->closed_at;
                $success *= $certificateToStudent->save();
            }
            return $success;
        } else {
            return false;
        }
    }
}