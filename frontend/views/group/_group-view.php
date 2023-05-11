<?php

use yii\grid\GridView;

/** @var \yii\data\ActiveDataProvider $dataProvider */

$updateIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
  <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
</svg>';

$closeIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
</svg>';

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table table-bordered table-hover dataTable dtr-inline'],
    'layout' => "{items}\n{pager}",
    'rowOptions' => function ($model, $key, $index, $grid) {
        return ['class' => 'group-row', 'id' => $model->id . '-' . 'group-id', 'title' => 'Посмотреть подробно'];
    },
    'columns' => [
        [
            'header' => 'Название',
            'content' => function ($model) {
                return $model->name;
            }
        ],
        [
            'contentOptions' => ['class' => 'width-25px'],
            'header' => 'Кол-во студентов',
            'content' => function ($model) {
                return count($model->students);
            }
        ],
        [
            'contentOptions' => ['class' => 'width-25px'],
            'header' => 'Поток',
            'content' => function ($model) {
                return $model->flow->name;
            }
        ],
        [
            'header' => 'Шифр направления',
            'content' => function ($model) {
                return $model->direction->id;
            }
        ],
        [
            'contentOptions' => ['class' => 'width-500px'],
            'header' => 'Название направления',
            'content' => function ($model) {
                return $model->direction->full_name;
            }
        ],
        [
            'header' => 'Квалификация',
            'content' => function ($model) {
                return $model->direction->academic_name;
            }
        ],
        [
            'header' => 'Дата начала',
            'content' => function ($model) {
                return date('d.m.Y', strtotime($model->created_at));
            }
        ],
        [
            'header' => 'Дата окончания',
            'content' => function ($model) {
                return $model->closed_at ? date('d.m.Y', strtotime($model->closed_at)) : '';
            }
        ],
        [
            'contentOptions' => ['class' => 'width-125px'],
            'header' => 'Статус',
            'content' => function ($model) {
                return $model->closed_at ? 'Закрыта' : 'На обучении';
            }
        ],
        [
            'contentOptions' => ['class' => 'width-100px'],
            'header' => 'Действия',
            'content' => function ($model) use ($updateIcon, $closeIcon) {
                if ($model->closed_at) {
                    return '';
                }
                return '<div class="row none-margin">
                                    <button id="' . $model->id . '-update-group-id" class="update-group-btn action-btn" title="Редактировать">' . $updateIcon . '</button>' . '<p class="width-1-p"></p>' .
                    '<button id="' . $model->id . '-close-group-id" class="close-group-btn action-btn" title="Закрыть">' . $closeIcon . '</button>' .
                    '</div>';
            }
        ],
    ]
]);