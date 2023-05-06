<?php

namespace frontend\models;

use yii\base\Model;

class FlowForm extends Model
{
    public $name;
    public $created_at;

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Обязательно для заполнения'],

            ['created_at', 'required', 'message' => 'Обязательно для заполнения'],
            ['created_at', 'date', 'format' => 'php:Y-m-d', 'message' => 'Некорректный ввод даты'],
        ];
    }

    public function saveFlow()
    {
        $flow = new Flow();
        $flow->name = $this->name;
        $flow->created_at = $this->created_at;
        return $flow->save();
    }

    public function loadFromDB($flow)
    {
        $this->name = $flow->name;
        $this->created_at = $flow->created_at;
    }

    public function updateFlow($id)
    {
        $flow = Flow::findOne(['id' => $id]);
        $flow->name = $this->name;
        $flow->created_at = $this->created_at;
        return $flow->save();
    }
}