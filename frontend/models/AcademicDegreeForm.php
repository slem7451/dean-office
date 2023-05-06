<?php

namespace frontend\models;

use yii\base\Model;

class AcademicDegreeForm extends Model
{
    public $name;

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Обязательно для заполнения']
        ];
    }

    public function saveAcademicDegree()
    {
        $academicDegree = new AcademicDegree();
        $academicDegree->name = $this->name;
        return $academicDegree->save();
    }

    public function loadFromDB($academicDegree)
    {
        $this->name = $academicDegree->name;
    }

    public function updateAcademicDegree($id)
    {
        $academicDegree = AcademicDegree::findOne(['id' => $id]);
        $academicDegree->name = $this->name;
        return $academicDegree->save();
    }
}