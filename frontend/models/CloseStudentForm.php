<?php

namespace frontend\models;

use yii\base\Model;

class CloseStudentForm extends Model
{
    const OPEN_STUDENT = 'o';
    const CLOSE_STUDENT = 'd';

    public $decree;
    public $added_at;
    public $created_at;

    public function rules()
    {
        return [
            ['decree', 'string'],
            ['decree', 'required', 'when' => function ($model) {
                return $model->created_at || $model->added_at;
            }, 'whenClient' => "function (attribute, value) {
                return $('#dp_added_at-o').val() || $('#dp_created_at-o').val() || $('#dp_added_at-d').val() || $('#dp_created_at-d').val();
            }", 'message' => 'Обязательно для заполнения'],

            ['created_at', 'date', 'format' => 'php:Y-m-d'],
            ['created_at', 'required', 'when' => function ($model) {
                return $model->decree || $model->added_at;
            }, 'whenClient' => "function (attribute, value) {
                return $('#dp_added_at-o').val() || $('#decree-select-o').val() || $('#dp_added_at-d').val() || $('#decree-select-d').val();
            }", 'message' => 'Обязательно для заполнения'],

            ['added_at', 'date', 'format' => 'php:Y-m-d'],
            ['added_at', 'required', 'when' => function ($model) {
                return $model->decree || $model->created_at;
            }, 'whenClient' => "function (attribute, value) {
                return $('#dp_created_at-o').val() || $('#decree-select-o').val() || $('#dp_created_at-d').val() || $('#decree-select-d').val();
            }", 'message' => 'Обязательно для заполнения']
        ];
    }

    public function closeStudent($id)
    {
        $success = true;
        if ($this->decree) {
            $decree = new Decree();
            $decree->template_id = $this->decree;
            $decree->created_at = $this->added_at;
            $success *= $decree->save();
            $decreeToStudent = new DecreeToStudent();
            $decreeToStudent->student_id = $id;
            $decreeToStudent->created_at = $this->created_at;
            $decreeToStudent->decree_id = $decree->id;
            $success *= $decreeToStudent->save();
        }
        $success *= Student::closeStudent($id);
        return $success;
    }

    public function openStudent($id)
    {
        $success = true;
        if ($this->decree) {
            $decree = new Decree();
            $decree->template_id = $this->decree;
            $decree->created_at = $this->added_at;
            $success *= $decree->save();
            $decreeToStudent = new DecreeToStudent();
            $decreeToStudent->student_id = $id;
            $decreeToStudent->created_at = $this->created_at;
            $decreeToStudent->decree_id = $decree->id;
            $success *= $decreeToStudent->save();
        }
        $success *= Student::openStudent($id);
        return $success;
    }

    public function closeStudents($id)
    {
        $success = true;
        $decree = new Decree();
        $decree->template_id = $this->decree;
        $decree->created_at = $this->added_at;
        $success *= $decree->save();
        $students = Student::findStudentsInFlow($id);
        foreach ($students as $student) {
            $decreeToStudent = new DecreeToStudent();
            $decreeToStudent->student_id = $student->id;
            $decreeToStudent->created_at = $this->created_at;
            $decreeToStudent->decree_id = $decree->id;
            $success *= $decreeToStudent->save();
            $success *= Student::closeStudent($student->id);
        }
        return $success;
    }
}