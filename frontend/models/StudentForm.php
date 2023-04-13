<?php

namespace frontend\models;

use yii\base\Model;

class StudentForm extends Model
{
    const MALE = 'm';
    const FEMALE = 'f';

    const OPERATION_CREATE = 'c';
    const OPERATION_UPDATE = 'u';

    public $first_name;
    public $second_name;
    public $patronymic;
    public $group;
    public $birthdate;
    public $sex;
    public $phone;


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
            ['sex', 'in', 'range' => [self::FEMALE, self::MALE], 'message' => 'Некорректный пол.'],

            ['phone', 'required', 'message' => 'Обязательно для заполнения'],
            ['phone', 'match', 'pattern' => '/^\+7\([0-9]{3}\)[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/', 'message' => 'Некорректный телефон.']

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

    public function loadFromDB($student)
    {
        $this->first_name = $student->first_name;
        $this->second_name = $student->second_name;
        $this->patronymic = $student->patronymic;
        $this->group = $student->group->id;
        $this->birthdate = $student->birthdate;
        $this->sex = $student->sex;
        $this->phone = $student->phone;
    }

    public function updateStudent($id)
    {
        $group = Group::findOne(['id' => $this->group]);
        $student = StudentView::findOne(['id' => $id]);
        $studentToGroup = StudentToGroup::findOne(['student_id' => $student->id]);
        $success = true;
        if ($group && $student && $studentToGroup) {
            $student->first_name = $this->first_name;
            $student->second_name = $this->second_name;
            $student->patronymic = $this->patronymic;
            $student->sex = $this->sex;
            $student->phone = $this->phone;
            $student->birthdate = $this->birthdate;
            $success *= $student->save();

            $studentToGroup->group_id = $this->group;
            $studentToGroup->student_id = $student->id;
            $studentToGroup->created_at = $student->created_at;
            $studentToGroup->closed_at = $student->closed_at;
            $success *= $studentToGroup->save();
        }
        return $success;
    }
}