<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var \frontend\models\Flow $flows */
/** @var \frontend\models\Direction $directions */

echo Html::input('string', 'group-name', null, [
    'placeholder' => 'Название группы',
    'class' => 'form-control col-2 mg-right-20-px',
    'id' => 'group-search-name'
]);
if ($flows) {
    echo Html::dropDownList('flow-select', null, ArrayHelper::map($flows, 'id', 'name'), [
        'class' => 'form-select col-2 mg-right-20-px',
        'prompt' => 'Все потоки',
        'id' => 'group-search-flow'
    ]);
}
echo Html::dropDownList('direction-select', null, ArrayHelper::map($directions, 'id', function($model) {
    return $model->id . ' ' . $model->short_name;
}), [
    'class' => 'form-select col-2 mg-right-20-px',
    'prompt' => 'Все направления',
    'id' => 'group-search-direction'
]);
echo Html::dropDownList('close-select', null, [
    0 => 'Все группы',
    1 => 'На обучении',
    2 => 'Закрыта'
], [
    'class' => 'form-select col-2 mg-right-20-px',
    'id' => 'group-search-closed_at'
]);