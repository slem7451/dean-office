<?php

namespace frontend\models;

use yii\base\Model;

class DirectionForm extends Model
{
    public $name;
    public $id;

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Обязательно для заполнения'],

            ['id', 'required', 'message' => 'Обязательно для заполнения']
        ];
    }

    public function saveDirection()
    {
        $direction = new Direction();
        $direction->id = $this->id;
        $direction->name = $this->name;
        return $direction->save();
    }

    public function loadFromDB($direction)
    {
        $this->id = $direction->id;
        $this->name = $direction->name;
    }

    public function updateDirection($id)
    {
        $success = true;
        $direction = Direction::findOne(['id' => $id]);
        if ($direction->id != $this->id) {
            $newDirection = new Direction();
            $newDirection->id = $this->id;
            $newDirection->name = $this->name;
            $success *= $newDirection->save();
            $groups = Group::findAll(['direction_id' => $direction->id]);
            foreach ($groups as $group) {
                $group->direction_id = $this->id;
                $success *= $group->save();
            }
            $success *= $direction->delete();
        } else {
            $direction->name = $this->name;
            $success *= $direction->save();
        }
        return $success;
    }
}