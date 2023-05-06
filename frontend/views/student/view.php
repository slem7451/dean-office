<?php

/** @var \frontend\models\StudentView $student */

use common\helpers\AgeHelper;
use common\helpers\GroupHelper;
use common\helpers\SexHelper;
use yii\widgets\DetailView;

$this->title = $student->second_name . ' ' . $student->first_name;
?>

<div class="view-student-container">
    <?= DetailView::widget([
        'model' => $student,
        'attributes' => [
            [
                'label' => 'Имя',
                'attribute' => function ($model) {
                    return $model->first_name;
                }
            ],
            [
                'label' => 'Фамилия',
                'attribute' => function ($model) {
                    return $model->second_name;
                }
            ],
            [
                'label' => 'Отчество (при наличии)',
                'attribute' => function ($model) {
                    return $model->patronymic;
                }
            ],
            [
                'label' => 'Телефон',
                'attribute' => function ($model) {
                    return $model->phone;
                }
            ],
            [
                'label' => 'Пол',
                'attribute' => function ($model) {
                    return SexHelper::getDetailSex($model->sex);
                }
            ],
            [
                'label' => 'Дата рождения',
                'attribute' => function ($model) {
                    return date('d.m.Y', strtotime($model->birthdate));
                }
            ],
            [
                'label' => 'Возраст',
                'attribute' => function ($model) {
                    return AgeHelper::getAge($model->birthdate);
                }
            ],
            [
                'label' => 'Группа',
                'attribute' => function ($model) {
                    return GroupHelper::getFullName($model->group);
                }
            ]
        ],
    ]) ?>
</div>
