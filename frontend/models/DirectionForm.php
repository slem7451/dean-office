<?php

namespace frontend\models;

use yii\base\Model;

class DirectionForm extends Model
{
    public $full_name;
    public $short_name;
    public $academic_name;
    public $profile;
    public $id;

    public function rules()
    {
        return [
            ['full_name', 'required', 'message' => 'Обязательно для заполнения'],

            ['short_name', 'required', 'message' => 'Обязательно для заполнения'],

            ['academic_name', 'required', 'message' => 'Обязательно для заполнения'],

            ['profile', 'required', 'message' => 'Обязательно для заполнения'],

            ['id', 'required', 'message' => 'Обязательно для заполнения']
        ];
    }

    public function saveDirection()
    {
        $direction = new Direction();
        $direction->id = $this->id;
        $direction->full_name = $this->full_name;
        $direction->short_name = $this->short_name;
        $direction->academic_name = $this->academic_name;
        $direction->profile = $this->profile;
        return $direction->save();
    }

    public function loadFromDB($direction)
    {
        $this->id = $direction->id;
        $this->full_name = $direction->full_name;
        $this->short_name = $direction->short_name;
        $this->academic_name = $direction->academic_name;
        $this->profile = $direction->profile;
    }

    public function updateDirection($id)
    {
        $success = true;
        $direction = Direction::findOne(['id' => $id]);
        if ($direction->id != $this->id) {
            $newDirection = new Direction();
            $newDirection->id = $this->id;
            $newDirection->full_name = $this->full_name;
            $newDirection->short_name = $this->short_name;
            $newDirection->academic_name = $this->academic_name;
            $newDirection->profile = $this->profile;
            $success *= $newDirection->save();
            $groups = Group::findAll(['direction_id' => $direction->id]);
            foreach ($groups as $group) {
                $group->direction_id = $this->id;
                $success *= $group->save();
            }
            $success *= $direction->delete();
        } else {
            $direction->full_name = $this->full_name;
            $direction->short_name = $this->short_name;
            $direction->academic_name = $this->academic_name;
            $direction->profile = $this->profile;
            $success *= $direction->save();
        }
        return $success;
    }
}