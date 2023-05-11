<?php

namespace frontend\models;

use yii\base\Model;

class GroupForm extends Model
{
    public $name;
    public $created_at;
    public $direction_id;
    public $flow_id;

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Обязательно для заполнения'],
            ['name', 'string'],
            ['name', 'trim'],

            ['created_at', 'required', 'message' => 'Обязательно для заполнения'],
            ['created_at', 'date', 'format' => 'php:Y-m-d'],

            ['direction_id', 'required', 'message' => 'Обязательно для заполнения'],

            ['flow_id', 'required', 'message' => 'Обязательно для заполнения']
        ];
    }

    public function saveGroup()
    {
        $success = true;
        $group = new Group();
        $group->name = $this->name;
        $group->created_at = $this->created_at;
        $group->direction_id = $this->direction_id;
        $success *= $group->save();
        $groupToFlow = new GroupToFlow();
        $groupToFlow->flow_id = $this->flow_id;
        $groupToFlow->group_id = $group->id;
        $success *= $groupToFlow->save();
        return $success;
    }

    public function loadFromDB($group)
    {
        $this->name = $group->name;
        $this->created_at = $group->created_at;
        $this->direction_id = $group->direction_id;
        $groupToFlow = GroupToFlow::findOne(['group_id' => $group->id]);
        $this->flow_id = $groupToFlow->flow_id;
    }

    public function updateGroup($id)
    {
        $success = true;
        $group = Group::findOne(['id' => $id]);
        $group->name = $this->name;
        $group->created_at = $this->created_at;
        $group->direction_id = $this->direction_id;
        $success *= $group->save();
        $groupToFlow = GroupToFlow::findOne(['group_id' => $group->id]);
        $groupToFlow->flow_id = $this->flow_id;
        $success *= $groupToFlow->save();
        return $success;
    }
}