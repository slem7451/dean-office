<?php

namespace frontend\models;

use yii\base\Model;

class DecreeForm extends Model
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

    public function saveDecree()
    {
        $success = true;
        $decree = new Decree();
        $decree->template_id = $this->template_id;
        $decree->created_at = $this->added_at;
        if ($decree->save()) {
            foreach ($this->students as $student) {
                $decreeToStudent = new DecreeToStudent();
                $decreeToStudent->student_id = $student;
                $decreeToStudent->decree_id = $decree->id;
                $decreeToStudent->created_at = $this->created_at;
                $decreeToStudent->closed_at = $this->closed_at;
                $success *= $decreeToStudent->save();
            }
            return $success;
        } else {
            return false;
        }
    }

    public function loadFromDB($decree)
    {
        $this->template_id = $decree->template_id;
        $this->added_at = $decree->created_at;
        $decreeToStudents = DecreeToStudent::findAll(['decree_id' => $decree->id]);
        foreach ($decreeToStudents as $decreeToStudent) {
            $this->students[] = $decreeToStudent->student_id;
        }
        $this->closed_at = $decreeToStudents[0]->closed_at;
        $this->created_at = $decreeToStudents[0]->created_at;
    }

    public function updateDecree($id)
    {
        $success = true;
        $decree = Decree::findOne(['id' => $id]);
        $decree->created_at = $this->added_at;
        $decree->template_id = $this->template_id;
        if ($decree->save()) {
            $success *= DecreeToStudent::deleteAll(['decree_id' => $decree->id]);
            foreach ($this->students as $student) {
                $decreeToStudent = new DecreeToStudent();
                $decreeToStudent->student_id = $student;
                $decreeToStudent->decree_id = $decree->id;
                $decreeToStudent->created_at = $this->created_at;
                $decreeToStudent->closed_at = $this->closed_at;
                $success *= $decreeToStudent->save();
            }
            return $success;
        } else {
            return false;
        }
    }
}