<?php

namespace frontend\models;

use yii\base\Model;

class StudentForm extends Model
{
    public $first_name;
    public $second_name;
    public $patronymic;
    public $group;
    public $birthdate;


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
            ['birthdate', 'date', 'format' => 'php:Y-m-d']

        ];
    }

    public function saveStudent()
    {
        $group = Group::findOne(['id' => $this->group]);
        $success = false;
        if ($group) {
            $student = new Student();
            $student->first_name = $this->first_name;
            $student->second_name = $this->second_name;
            $student->patronymic = $this->patronymic;
            $student->birthdate = $this->birthdate;
            $success *= $student->save();

            $studentToGroup = new StudentToGroup();
            $studentToGroup->group_id = $this->group;
            $studentToGroup->student_id = $student->id;
            $studentToGroup->created_at = $student->created_at;
            $studentToGroup->closed_at = $student->closed_at;
            $success *= $studentToGroup->save();
        }
        return $success;
    }
}