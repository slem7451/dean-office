<?php

use common\helpers\DateHelper;
use common\helpers\GroupHelper;
use common\helpers\SexHelper;
use frontend\models\CloseStudentForm;
use frontend\models\Student;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap4\Modal;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use common\helpers\AgeHelper;

/** @var \frontend\models\StudentForm $model */
/** @var \frontend\models\StudentForm $selectedStudent */
/** @var \frontend\models\Group $groups */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var array $documents */
/** @var \frontend\models\CloseStudentForm $closeStudentForm */
/** @var \frontend\models\DecreeTemplate $decrees */

$this->title = 'Студенты';

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
</svg>'
?>
    <div class="student-container">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-title">
                    <?php
                    $form = ActiveForm::begin(['id' => 'student-form', 'options' => ['enctype' => 'multipart/form-data']]);
                    Modal::begin([
                        'id' => 'student-modal',
                        'toggleButton' => ['label' => 'Создать студента', 'class' => 'btn btn-primary'],
                        'size' => 'modal-lg',
                        'title' => 'Создание студента',
                        'footer' => Html::submitButton('Создать', ['class' => 'btn btn-success mg-right-76-p']) . Html::button('Закрыть', [
                                'class' => 'btn btn-danger',
                                'data-dismiss' => 'modal'
                            ])
                    ]);
                    echo $this->render('_student-form-modal', [
                        'model' => $model,
                        'form' => $form,
                        'groups' => $groups,
                        'operation' => OPERATION_CREATE,
                        'documents' => $documents
                    ]);
                    Modal::end();
                    ActiveForm::end();
                    ?>
                </div>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php
                Pjax::begin(['id' => 'student-table-pjax']);
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
                Pjax::end();
                ?>
            </div>
        </div>
    </div>
    <div id="btn-clicked" class="none-display">0</div>
<?php
Pjax::begin(['id' => 'update-student-pjax']);
$form = ActiveForm::begin(['id' => 'update-student-form', 'options' => ['enctype' => 'multipart/form-data']]);
Modal::begin([
    'id' => 'update-student-modal',
    'title' => 'Редактирование студента',
    'size' => 'modal-lg',
    'footer' => Html::submitButton('Сохранить', ['class' => 'btn btn-success mg-right-74-p']) . Html::button('Закрыть', [
            'class' => 'btn btn-danger',
            'data-dismiss' => 'modal'
        ])
]);
echo $this->render('_student-form-modal', [
    'form' => $form,
    'model' => $selectedStudent,
    'groups' => $groups,
    'operation' => OPERATION_UPDATE,
    'documents' => $documents
]);
Modal::end();
ActiveForm::end();
Pjax::end();
?>
<?php
$form = ActiveForm::begin(['id' => 'close-student-form']);
Modal::begin([
    'id' => 'close-student-modal',
    'title' => 'Отчисление студента',
    'size' => 'modal-lg',
    'footer' => Html::submitButton('Отчислить', ['class' => 'btn btn-success mg-right-74-p']) . Html::button('Закрыть', [
            'class' => 'btn btn-danger',
            'data-dismiss' => 'modal'
        ])
]);
echo $this->render('_close-student-form-modal', [
    'form' => $form,
    'model' => $closeStudentForm,
    'decrees' => $decrees,
    'operation' => CloseStudentForm::CLOSE_STUDENT
]);
Modal::end();
ActiveForm::end();
?>
<?php
$form = ActiveForm::begin(['id' => 'open-student-form']);
Modal::begin([
    'id' => 'open-student-modal',
    'title' => 'Зачисление студента',
    'size' => 'modal-lg',
    'footer' => Html::submitButton('Зачислить', ['class' => 'btn btn-success mg-right-74-p']) . Html::button('Закрыть', [
            'class' => 'btn btn-danger',
            'data-dismiss' => 'modal'
        ])
]);
echo $this->render('_close-student-form-modal', [
    'form' => $form,
    'model' => $closeStudentForm,
    'decrees' => $decrees,
    'operation' => CloseStudentForm::OPEN_STUDENT
]);
Modal::end();
ActiveForm::end();
?>
<?php
$this->registerJS(<<<JS
    $('#student-modal').on('hidden.bs.modal', function () {
        $('#student-form')[0].reset();
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('hidden.bs.modal', '#update-student-modal', function () {
        $('#btn-clicked').html('0');
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('hidden.bs.modal', '#close-student-modal', function () {
        $('#close-student-form')[0].reset();
        $('#btn-clicked').html('0');
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('hidden.bs.modal', '#open-student-modal', function () {
        $('#open-student-form')[0].reset();
        $('#btn-clicked').html('0');
    })
JS
);

$this->registerJS(<<<JS
    $('#student-form').on('beforeSubmit', function() {
        var data = new FormData($(this)[0]);
        $.ajax({
            url: '/index.php?r=student%2Findex',
            type: 'POST',
            contentType: false,
            processData: false,
            data: data,
            success: function(res) {
                $.pjax.reload({container: '#student-table-pjax', replace: false});
                $('#student-modal').modal('hide');
            }
        });
        return false;
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
        var idDS = this.id.split('-')[0];
        $('#btn-clicked').html(idDS);
        $('#close-student-modal').modal('show');
    })
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.add-student-btn', function() {
        var idAS = this.id.split('-')[0];
        $('#btn-clicked').html(idAS);
        $('#open-student-modal').modal('show');
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
        var data = new FormData($(this)[0]);
        data.append('idUS', $('#btn-clicked').html());
        $.ajax({
            url: '/index.php?r=student%2Findex',
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
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
    $(document).on('beforeSubmit', '#close-student-form', function() {
        var data = new FormData($(this)[0]);
        data.append('idDS', $('#btn-clicked').html());
        $.ajax({
            url: '/index.php?r=student%2Findex',
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            success: function(res) {
                $.pjax.reload({container: '#student-table-pjax', replace: false});
                $('#close-student-modal').modal('hide');
            }
        });
        return false;
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('beforeSubmit', '#open-student-form', function() {
        var data = new FormData($(this)[0]);
        data.append('idAS', $('#btn-clicked').html());
        $.ajax({
            url: '/index.php?r=student%2Findex',
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            success: function(res) {
                $.pjax.reload({container: '#student-table-pjax', replace: false});
                $('#open-student-modal').modal('hide');
            }
        });
        return false;
    })
JS
);