<?php

namespace frontend\models;

use yii\base\Model;

class GroupForm extends Model
{
    public $name;
    public $created_at;
    public $closed_at;

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Обязательно для заполнения'],
            ['name', 'string'],
            ['name', 'trim'],

            ['created_at', 'required'],

            ['closed_at', 'required']
        ];
    }

    public function saveGroup()
    {
        $group = new Group();
        $group->name = $this->name;
        $group->created_at = $this->created_at;
        $group->closed_at = $this->closed_at;
        return $group->save();
    }
}