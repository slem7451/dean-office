<?php

/** @var \frontend\models\Group $group */

/** @var \yii\data\ActiveDataProvider $studentsDataProvider */

/** @var \frontend\models\StudentForm $selectedStudent */

/** @var \frontend\models\Group $groups */

/** @var \frontend\models\Student $allStudents */

/** @var array $documents */

/** @var \frontend\models\CloseStudentForm $closeStudentForm */

/** @var \frontend\models\DecreeTemplate $decrees */

use common\helpers\GroupHelper;
use frontend\models\CloseStudentForm;
use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap4\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = GroupHelper::getFullName($group);
?>
    <div class="view-group-container">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-title col-11">
                    <div class="row">
                    <?php Pjax::begin(['id' => 'student-select-pjax']) ?>
                            <?= Select2::widget([
                                'name' => 'add-students-group-select',
                                'showToggleAll' => false,
                                'data' => ArrayHelper::map($allStudents, 'id', function ($model) {
                                    return $model->second_name . ' ' . $model->first_name . ($model->patronymic ? ' ' . $model->patronymic : '') . ' (' . $model->id . ')';
                                }),
                                'theme' => Select2::THEME_DEFAULT,
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'width' => '75%'
                                ],
                                'options' => [
                                    'placeholder' => 'Добавить студентов',
                                    'multiple' => true,
                                    'id' => 'add-students-group-select'
                                ],
                            ]); ?>
                            <?= Html::button('Добавить', [
                                'class' => 'btn btn-primary',
                                'id' => 'confirm-select-students',
                                'disabled' => 'true',
                                'title' => 'Добавить в группу'
                            ]) ?>
                    <?php Pjax::end() ?>
                    </div>
                </div>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php
                Pjax::begin(['id' => 'student-table-pjax']);
                echo $this->render('/student/_student-view', ['dataProvider' => $studentsDataProvider]);
                Pjax::end();
                ?>
            </div>
        </div>
    </div>
    <div id="btn-clicked" class="none-display">0</div>
<?php
Pjax::begin(['id' => 'update-student-pjax']);
$form = ActiveForm::begin(['id' => 'update-student-form']);
Modal::begin([
    'id' => 'update-student-modal',
    'title' => 'Редактирование студента',
    'size' => 'modal-lg',
    'footer' => Html::submitButton('Сохранить', ['class' => 'btn btn-success mg-right-74-p']) . Html::button('Закрыть', [
            'class' => 'btn btn-danger',
            'data-dismiss' => 'modal'
        ])
]);
echo $this->render('/student/_student-form-modal', [
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
echo $this->render('/student/_close-student-form-modal', [
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
echo $this->render('/student/_close-student-form-modal', [
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
