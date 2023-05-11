<?php

use common\helpers\AgeHelper;
use common\helpers\DateHelper;
use common\helpers\GroupHelper;
use common\helpers\SexHelper;
use frontend\models\Student;
use yii\grid\GridView;

/** @var \yii\data\ActiveDataProvider $dataProvider */

$updateIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
  <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
</svg>';

$deleteIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-person-fill-x" viewBox="0 0 16 16">
  <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm-9 8c0 1 1 1 1 1h5.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.544-3.393C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4Z"/>
  <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm-.646-4.854.646.647.646-.647a.5.5 0 0 1 .708.708l-.647.646.647.646a.5.5 0 0 1-.708.708l-.646-.647-.646.647a.5.5 0 0 1-.708-.708l.647-.646-.647-.646a.5.5 0 0 1 .708-.708Z"/>
</svg>';

$addIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-person-fill-add" viewBox="0 0 16 16">
  <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0Zm-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
  <path d="M2 13c0 1 1 1 1 1h5.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.544-3.393C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4Z"/>
</svg>';

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table table-bordered table-hover dataTable dtr-inline'],
    'layout' => "{items}\n{pager}",
    'rowOptions' => function ($model, $key, $index, $grid) {
        return ['class' => 'student-row', 'id' => $model->id . '-' . 'student-id', 'title' => 'Посмотреть подробно'];
    },
    'columns' => [
        [
            'header' => 'Имя',
            'content' => function ($model) {
                return $model->first_name;
            }
        ],
        [
            'header' => 'Фамилия',
            'content' => function ($model) {
                return $model->second_name;
            }
        ],
        [
            'header' => 'Отчество',
            'content' => function ($model) {
                return $model->patronymic ?: '';
            }
        ],
        [
            'header' => 'Оплата',
            'content' => function ($model) {
                return $model->payment == Student::CONTRACT_PAYMENT ? 'Контракт' : 'Бюджет';
            }
        ],
        [
            'header' => 'Дата рождения',
            'content' => function ($model) {
                return DateHelper::normalizeDate($model->birthdate) . ' (' . AgeHelper::getAge($model->birthdate) . ')';
            }
        ],
        [
            'header' => 'Пол',
            'content' => function ($model) {
                return SexHelper::getSex($model->sex);
            }
        ],
        [
            'header' => 'Телефон',
            'content' => function ($model) {
                return $model->phone;
            }
        ],
        [
            'header' => 'Поток',
            'content' => function ($model) {
                return $model->group->flow->name;
            }
        ],
        [
            'header' => 'Группа',
            'content' => function ($model) {
                return GroupHelper::getFullName($model->group);
            }
        ],
        [
            'header' => 'Дата поступления',
            'content' => function ($model) {
                return DateHelper::normalizeDate($model->created_at);
            }
        ],
        [
            'header' => 'Статус',
            'content' => function ($model) {
                return $model->closed_at ? 'Отчислен' : 'Обучается';
            }
        ],
        [
            'header' => 'Действия',
            'content' => function ($model) use ($deleteIcon, $updateIcon, $addIcon) {
                if ($model->closed_at) {
                    return '<div class="row none-margin">
                                <button id="' . $model->id . '-add-student-id" class="add-student-btn action-btn" title="Зачислить">' . $addIcon . '</button>
                                </div>';
                }
                return '<div class="row none-margin">
                                    <button id="' . $model->id . '-update-student-id" class="update-student-btn action-btn" title="Редактировать">' . $updateIcon . '</button>' . '<p class="col-1"></p>' .
                    '<button id="' . $model->id . '-delete-student-id" class="delete-student-btn action-btn" title="Отчислить">' . $deleteIcon . '</button>
                                </div>';
            }
        ],
    ]
]);