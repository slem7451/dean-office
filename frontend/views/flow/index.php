<?php

use common\helpers\DateHelper;
use yii\bootstrap4\Modal;
use yii\bootstrap5\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var \frontend\models\FlowForm $model */
/** @var \frontend\models\FlowForm $selectedFlow */
/** @var \yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Потоки';

$updateIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
  <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
</svg>';

$updateGroupsIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
  <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7Zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216ZM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
</svg>';

$closeIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
</svg>';
?>

    <div class="flow-container">
        <?php
        $form = ActiveForm::begin(['id' => 'flow-form']);
        Modal::begin([
            'id' => 'flow-modal',
            'toggleButton' => ['label' => 'Создать поток', 'class' => 'btn btn-primary mg-bottom-15px'],
            'title' => 'Создание потока',
            'footer' => Html::submitButton('Создать', ['class' => 'btn btn-success save-student-btn']) . Html::button('Закрыть', [
                    'class' => 'btn btn-danger',
                    'data-dismiss' => 'modal'
                ])
        ]);
        echo $this->render('_flow-form-modal', [
            'form' => $form,
            'model' => $model,
            'operation' => OPERATION_CREATE
        ]);
        Modal::end();
        ActiveForm::end();
        Pjax::begin(['id' => 'flow-table-pjax']);
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{pager}",
            'columns' => [
                [
                    'header' => 'Название',
                    'content' => function ($model) {
                        return $model->name;
                    }
                ],
                [
                    'header' => 'Группы',
                    'content' => function ($model) {
                        return implode(', ', $model->groups);
                    }
                ],
                [
                    'header' => 'Дата поступления',
                    'content' => function ($model) {
                        return DateHelper::normalizeDate($model->created_at);
                    }
                ],
                [
                    'header' => 'Дата окончания',
                    'content' => function ($model) {
                        return $model->closed_at ? DateHelper::normalizeDate($model->closed_at) : '';
                    }
                ],
                [
                    'header' => 'Статус',
                    'content' => function ($model) {
                        return $model->closed_at ? 'Выпущен' : 'На обучении';
                    }
                ],
                [
                    'header' => 'Действия',
                    'content' => function ($model) use ($updateIcon, $updateGroupsIcon, $closeIcon) {
                        if ($model->closed_at) {
                            return '';
                        }
                        return '<div class="row none-margin">
                                    <button id="' . $model->id . '-update-flow-id" class="update-flow-btn action-btn" title="Редактировать">' . $updateIcon . '</button>' . '<p class="col-1"></p>' .
                            //'<button id="' . $model->id . '-update-flow-groups-id" class="update-flow-groups-btn action-btn" title="Убрать/добавить группы">' . $updateGroupsIcon . '</button>'  . '<p class="col-1"></p>' .
                            '<button id="' . $model->id . '-close-flow-id" class="close-flow-btn action-btn" title="Выпустить поток">' . $closeIcon . '</button>
                                </div>';
                    }
                ],
            ]
        ]);
        Pjax::end();
        ?>
    </div>
    <div id="btn-clicked" class="none-display">0</div>
<?php
Pjax::begin(['id' => 'update-flow-pjax']);
$form = ActiveForm::begin(['id' => 'update-flow-form']);
Modal::begin([
    'id' => 'update-flow-modal',
    'title' => 'Редактирование потока',
    'footer' => Html::submitButton('Сохранить', ['class' => 'btn btn-success update-student-btn-modal']) . Html::button('Закрыть', [
            'class' => 'btn btn-danger',
            'data-dismiss' => 'modal'
        ])
]);
echo $this->render('_flow-form-modal', [
    'form' => $form,
    'model' => $selectedFlow,
    'operation' => OPERATION_UPDATE
]);
Modal::end();
ActiveForm::end();
Pjax::end();
?>
<?php
$this->registerJS(<<<JS
    $(document).on('hidden.bs.modal', '#flow-modal', function () {
        $('#flow-form')[0].reset();
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('beforeSubmit', '#flow-form', function() {
        var data = $(this).serialize();
        $.ajax({
            url: '/index.php?r=flow%2Findex',
            type: 'POST',
            data: data,
            success: function(res) {
                $.pjax.reload({container: '#flow-table-pjax', replace: false});
                $('#flow-modal').modal('hide');
            }
        });
        return false;
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('hidden.bs.modal', '#update-flow-modal', function () {
        $('#btn-clicked').html('0');
    })
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.update-flow-btn', function() {
        var idUF = this.id.split('-')[0];
        $.pjax.reload({container: '#update-flow-pjax', data: {idUF: idUF}, replace: false});
        $('#btn-clicked').html(idUF);
    })
JS
);

$this->registerJs(<<<JS
    $('#update-flow-pjax').on('pjax:success', function () {
        $('#update-flow-modal').modal('show');
    });
JS
);

$this->registerJs(<<<JS
    $('#flow-table-pjax').on('pjax:success', function () {
        $('#btn-clicked').html('0');
    });
JS
);

$this->registerJS(<<<JS
    $(document).on('beforeSubmit', '#update-flow-form', function() {
        var data = $(this).serialize();
        data += '&idUF=' + $('#btn-clicked').html();
        $.ajax({
            url: '/index.php?r=flow%2Findex',
            type: 'POST',
            data: data,
            success: function(res) {
                $.pjax.reload({container: '#flow-table-pjax', replace: false});
                $('#update-flow-modal').modal('hide');
            }
        });
        return false;
    })
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.close-flow-btn', function() {
        if (confirm('Вы уверены, что хотите выпустить данный поток?')) {
            var idCF = this.id.split('-')[0];
            $.ajax({
                url: '/index.php?r=flow%2Findex',
                type: 'POST',
                data: {idCF: idCF},
                success: function(res) {
                    $.pjax.reload({container: '#flow-table-pjax', replace: false});
                }
            });
        }
    })
JS
);