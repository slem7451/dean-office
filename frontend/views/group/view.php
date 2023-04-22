<?php

/** @var \frontend\models\Group $group */

/** @var \yii\data\ActiveDataProvider $studentsDataProvider */

/** @var \frontend\models\StudentForm $selectedStudent */

/** @var \frontend\models\Group $groups */

/** @var \frontend\models\StudentView $allStudents */

use common\helpers\AgeHelper;
use common\helpers\SexHelper;
use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap4\Modal;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = $group->name . ' (' . date('y', strtotime($group->created_at)) . '-' . date('y', strtotime($group->closed_at)) . ')';

$updateIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
  <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
</svg>';

$deleteIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
</svg>';
?>
    <div class="view-group-container">
        <?php Pjax::begin(['id' => 'student-select-pjax']) ?>
        <div class="select-students row col-12">
            <div class="student-select col-6 mg-bottom-15px">
                <?= Select2::widget([
                    'name' => 'add-students-group-select',
                    'showToggleAll' => false,
                    'data' => ArrayHelper::map($allStudents, 'id', function ($model) {
                        return $model->second_name . ' ' . $model->first_name . ($model->patronymic ? ' ' . $model->patronymic : '');
                    }),
                    'options' => [
                        'placeholder' => 'Добавить студентов',
                        'multiple' => true,
                        'id' => 'add-students-group-select'
                    ],
                ]); ?>
            </div>
            <?= Html::button('Добавить', [
                'class' => 'btn btn-primary col-1 mg-bottom-15px',
                'id' => 'confirm-select-students',
                'disabled' => 'true',
                'title' => 'Добавить в группу'
            ]) ?>
        </div>
        <?php Pjax::end() ?>
        <?php
        Pjax::begin(['id' => 'student-table-pjax']);
        echo GridView::widget([
            'dataProvider' => $studentsDataProvider,
            'layout' => "{items}\n{pager}",
            'rowOptions' => function ($model, $key, $index, $grid) {
                return ['class' => 'student-row', 'id' => $model->id . '-' . 'student-id', 'title' => 'Посмотреть подробно'];
            },
            'columns' => [
                [
                    'header' => 'Имя',
                    'content' => function ($model) {
                        return '<p>' . $model->first_name . '</p>';
                    }
                ],
                [
                    'header' => 'Фамилия',
                    'content' => function ($model) {
                        return '<p>' . $model->second_name . '</p>';
                    }
                ],
                [
                    'header' => 'Отчество',
                    'content' => function ($model) {
                        return '<p>' . $model->patronymic ? $model->patronymic : '</p>';
                    }
                ],
                [
                    'header' => 'Пол',
                    'content' => function ($model) {
                        return '<p>' . SexHelper::getSex($model->sex) . '</p>';
                    }
                ],
                [
                    'header' => 'Телефон',
                    'content' => function ($model) {
                        return '<p>' . $model->phone . '</p>';
                    }
                ],
                [
                    'header' => 'Дата рождения',
                    'content' => function ($model) {
                        return '<p>' . date('d.m.Y', strtotime($model->birthdate)) . ' (' . AgeHelper::getAge($model->birthdate) . ')' . '</p>';
                    }
                ],
                [
                    'header' => 'Действия',
                    'content' => function ($model) use ($deleteIcon, $updateIcon) {
                        return '<div class="row none-margin">
                                    <button id="' . $model->id . '-update-student-id" class="update-student-btn" title="Редактировать">' . $updateIcon . '</button>' . '<p class="col-1"></p>' .
                            '<button id="' . $model->id . '-delete-student-id" class="delete-student-btn" title="Удалить">' . $deleteIcon . '</button>
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
Pjax::begin(['id' => 'update-student-pjax']);
$form = ActiveForm::begin(['id' => 'update-student-form']);
Modal::begin([
    'id' => 'update-student-modal',
    'title' => 'Редактирование студента',
    'footer' => Html::submitButton('Сохранить', ['class' => 'btn btn-success update-student-btn-modal']) . Html::button('Закрыть', [
            'class' => 'btn btn-danger',
            'data-dismiss' => 'modal'
        ])
]);
echo $this->render('/student/_student-form-modal', [
    'form' => $form,
    'model' => $selectedStudent,
    'groups' => $groups,
    'operation' => 'u'
]);
Modal::end();
ActiveForm::end();
Pjax::end();
?>
<?php
$this->registerJS(<<<JS
    $(document).on('hidden.bs.modal', '#update-student-modal', function () {
        $('#btn-clicked').html('0');
    })
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.student-row', function() {
        var id = this.id.split('-')[0];
        var isButton = $('#btn-clicked').html();
        if (isButton == '0') {
            window.location.href = 'http://localhost:20080/index.php?r=student%2Fview&id=' + id;
        }
    })
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.update-student-btn', function() {
        var idUS = this.id.split('-')[0];
        $.pjax.reload({container: '#update-student-pjax', data: {idUS: idUS}, replace: false});
        $('#btn-clicked').html(idUS);
    })
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.delete-student-btn', function() {
        $('#btn-clicked').html('1');
        if (confirm('Вы уверены, что хотите удалить данного студента?')) {
            var idDS = this.id.split('-')[0];
            $.ajax({
                url: '/index.php?r=student%2Findex',
                type: 'POST',
                data: {idDS: idDS},
                success: function(res) {
                    $.pjax.reload({container: '#student-table-pjax', replace: false});
                }
            });
        } else {
            $.pjax.reload({container: '#student-table-pjax', replace: false});
        }
    })
JS
);

$this->registerJs(<<<JS
    $('#student-table-pjax').on('pjax:success', function () {
        $('#btn-clicked').html('0');
    });
JS
);

$this->registerJs(<<<JS
    $('#update-student-pjax').on('pjax:success', function () {
        $('#update-student-modal').modal('show');
    });
JS
);

$this->registerJS(<<<JS
    $(document).on('beforeSubmit', '#update-student-form', function() {
        var data = $(this).serialize();
        data += '&idUS=' + $('#btn-clicked').html();
        $.ajax({
            url: '/index.php?r=student%2Findex',
            type: 'POST',
            data: data,
            success: function(res) {
                $.pjax.reload({container: '#student-table-pjax', replace: false});
                $('#update-student-modal').modal('hide');
            }
        });
        return false;
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('change', '#add-students-group-select', function() {
        if ($(this).val().length) {
            $('#confirm-select-students').prop('disabled', false);
        } else {
            $('#confirm-select-students').prop('disabled', true);
        }
    })
JS
);


$this->registerJS(<<<JS
    $(document).on('click', '#confirm-select-students', function() {
        var group_id = {$group->id};
        var students = $('#add-students-group-select').val();
        $.ajax({
            url: '/index.php?r=group%2Fview&id=' + group_id,
            type: 'POST',
            data: {students: students},
            success: function(res) {
                $.pjax.reload({container: '#student-select-pjax', replace: false});
            }
        });
    })
JS
);

$this->registerJs(<<<JS
    $(document).on('pjax:success', '#student-select-pjax', function () {
        $.pjax.reload({container: '#student-table-pjax', replace: false});
    })
JS
);
