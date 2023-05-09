<?php

use common\helpers\AgeHelper;
use common\helpers\DateHelper;
use common\helpers\GroupHelper;
use common\helpers\SexHelper;
use common\helpers\TemplateHelper;
use frontend\models\Student;
use frontend\models\StudentForm;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap4\Modal;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/** @var \frontend\models\Student $student */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var \yii\data\ArrayDataProvider $decreeDataProvider */
/** @var \yii\data\ArrayDataProvider $certificateDataProvider */
/** @var \yii\data\ArrayDataProvider $documentDataProvider */
/** @var \frontend\models\DocumentForm $model */

$this->title = $student->second_name . ' ' . $student->first_name;

$updateIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-pencil-fill mg-right-15-px" viewBox="0 0 16 16">
  <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
</svg>';

$deleteIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
</svg>';
?>
<?php
$form = ActiveForm::begin(['id' => 'student-form', 'options' => ['enctype' => 'multipart/form-data']]);
Modal::begin([
    'id' => 'student-modal',
    'size' => 'modal-lg',
    'title' => 'Создание студента',
    'footer' => Html::submitButton('Создать', ['class' => 'btn btn-success mg-right-76-p']) . Html::button('Закрыть', [
            'class' => 'btn btn-danger',
            'data-dismiss' => 'modal'
        ])
]);
echo $this->render('_student-form-modal', [
    'model' => new StudentForm(),
    'form' => $form,
    'groups' => [],
    'operation' => OPERATION_CREATE,
    'documents' => []
]);
Modal::end();
ActiveForm::end();
?>
    <div class="view-student-container">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    Информация
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
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
                        ],
                        [
                            'label' => 'Направление',
                            'attribute' => function ($model) {
                                return $model->group->direction->id . ', ' . $model->group->direction->name;
                            }
                        ],
                        [
                            'label' => 'Статус',
                            'attribute' => function ($model) {
                                return $model->closed_at ? 'Отчислен' : 'Обучается';
                            }
                        ],
                    ],
                ]) ?>
            </div>
        </div>
        <div class="card card-outline card-secondary collapsed-card">
            <div class="card-header">
                <h3 class="card-title">
                    История изменений
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => "{items}\n{pager}",
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
                            'header' => 'Дата поступления',
                            'content' => function ($model) {
                                return DateHelper::normalizeDate($model->created_at);
                            }
                        ],
                        [
                            'header' => 'Дата отчисления',
                            'content' => function ($model) {
                                return $model->closed_at ? DateHelper::normalizeDate($model->closed_at) : '';
                            }
                        ],
                        [
                            'header' => 'Дата изменения',
                            'content' => function ($model) {
                                return DateHelper::normalizeDate($model->updated_at);
                            }
                        ],
                        [
                            'header' => 'Операция',
                            'content' => function ($model) {
                                switch ($model->operation) {
                                    case DB_INSERT:
                                        return 'Зачисление';
                                    case DB_UPDATE:
                                        return 'Изменение';
                                    case DB_DELETE:
                                        return 'Отчисление';
                                    default:
                                        return '';
                                }
                            }
                        ]
                    ]
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="card card-outline card-primary collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Приказы
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <?= GridView::widget([
                            'dataProvider' => $decreeDataProvider,
                            'layout' => "{items}\n{pager}",
                            'columns' => [
                                [
                                    'header' => 'Номер',
                                    'content' => function ($model) {
                                        return $model['decree']->template->id;
                                    }
                                ],
                                [
                                    'header' => 'Название',
                                    'content' => function ($model) {
                                        return $model['decree']->template->name;
                                    }
                                ],
                                [
                                    'header' => 'Текст',
                                    'content' => function ($model) use ($student) {
                                        return TemplateHelper::parseTemplate($model['decree']->template->template, $student, $model['decreeToStudent']);
                                    }
                                ],
                                [
                                    'header' => 'Дата приказа',
                                    'content' => function ($model) {
                                        return DateHelper::normalizeDate($model['decree']->created_at);
                                    }
                                ]
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card card-outline card-primary collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Справки
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <?= GridView::widget([
                            'dataProvider' => $certificateDataProvider,
                            'layout' => "{items}\n{pager}",
                            'columns' => [
                                [
                                    'header' => 'Номер',
                                    'content' => function ($model) {
                                        return $model['certificate']->template->id;
                                    }
                                ],
                                [
                                    'header' => 'Название',
                                    'content' => function ($model) {
                                        return $model['certificate']->template->name;
                                    }
                                ],
                                [
                                    'header' => 'Текст',
                                    'content' => function ($model) use ($student) {
                                        return TemplateHelper::parseTemplate($model['certificate']->template->template, $student, $model['certificateToStudent']);
                                    }
                                ],
                                [
                                    'header' => 'Дата справки',
                                    'content' => function ($model) {
                                        return DateHelper::normalizeDate($model['certificate']->created_at);
                                    }
                                ]
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-outline card-primary collapsed-card">
            <div class="card-header">
                <h3 class="card-title">
                    Документы
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php
                $form = ActiveForm::begin(['id' => 'document-form', 'options' => ['enctype' => 'multipart/form-data']]);
                Modal::begin([
                    'id' => 'document-modal',
                    'size' => 'modal-lg',
                    'toggleButton' => ['label' => 'Добавить документы', 'class' => 'btn btn-primary mg-bottom-15px'],
                    'title' => 'Добавление документов',
                    'footer' => Html::submitButton('Добавить', ['class' => 'btn btn-success mg-right-75-p']) . Html::button('Закрыть', [
                            'class' => 'btn btn-danger',
                            'data-dismiss' => 'modal'
                        ])
                ]);
                echo $this->render('_document-form-modal', [
                    'model' => $model,
                    'form' => $form,
                    'operation' => OPERATION_CREATE
                ]);
                Modal::end();
                ActiveForm::end();
                Pjax::begin(['id' => 'document-table-pjax']);
                ?>
                <?= GridView::widget([
                    'dataProvider' => $documentDataProvider,
                    'layout' => "{items}\n{pager}",
                    'columns' => [
                        [
                            'header' => 'Название',
                            'content' => function ($model) {
                                return $model->name;
                            }
                        ],
                        [
                            'header' => 'Скан',
                            'content' => function ($model) {
                                return '<img src="uploads/' . $model->scan . '" class="document-img" alt="Document"/>';
                            }
                        ],
                        [
                            'header' => 'Действия',
                            'content' => function ($model) use ($updateIcon, $deleteIcon) {
                                return '<div class="row none-margin">
                                    <button id="' . $model->id . '-update-document-id" class="update-document-btn action-btn" title="Редактировать">' . $updateIcon . '</button>' .
                                    '<button id="' . $model->id . '-delete-document-id" class="delete-document-btn action-btn" title="Удалить">' . $deleteIcon . '</button>' .
                                    '</div>';
                            }
                        ]
                    ]
                ]) ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
    <div id="document-id" class="none-display">%</div>
<?php
$form = ActiveForm::begin(['id' => 'update-document-form', 'options' => ['enctype' => 'multipart/form-data']]);
Modal::begin([
    'id' => 'update-document-modal',
    'size' => 'modal-lg',
    'title' => 'Редактирование документа',
    'footer' => Html::submitButton('Сохранить', ['class' => 'btn btn-success mg-right-74-p']) . Html::button('Закрыть', [
            'class' => 'btn btn-danger',
            'data-dismiss' => 'modal'
        ])
]);
echo $this->render('_document-form-modal', [
    'model' => $model,
    'form' => $form,
    'operation' => OPERATION_UPDATE
]);
Modal::end();
ActiveForm::end();
?>
<?php
$this->registerJS(<<<JS
    $('#document-modal').on('hidden.bs.modal', function () {
        $('#document-form')[0].reset();
    })
JS
);

$this->registerJS(<<<JS
    $('#update-document-modal').on('hidden.bs.modal', function () {
        $('#document-id').html('%');
        $('#update-document-form')[0].reset();
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('beforeSubmit', '#document-form', function() {
        var id = {$student->id};
        var data = new FormData($(this)[0]);
        $.ajax({
            url: '/index.php?r=student%2Fview&id=' + id,
            type: 'POST',
            contentType: false,
            processData: false,
            data: data,
            success: function(res) {
                $.pjax.reload({container: '#document-table-pjax', replace: false});
                $('#document-modal').modal('hide');
            }
        });
        return false;
    });
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.delete-document-btn', function() {
        if (confirm('Вы уверены, что хотите удалить данный документ?')) {
            var idDD = this.id.split('-')[0];
            var id = {$student->id};
            $.ajax({
                url: '/index.php?r=student%2Fview&id=' + id,
                type: 'POST',
                data: {idDD: idDD},
                success: function(res) {
                    $.pjax.reload({container: '#document-table-pjax', replace: false});
                }
            });
        }
    })
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.update-document-btn', function() {
        $('#document-id').html(this.id.split('-')[0]);
        $('#update-document-modal').modal('show');
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('beforeSubmit', '#update-document-form', function() {
        var id = {$student->id};
        var data = new FormData($(this)[0]);
        var idUD = $('#document-id').html();
        data.append('idUD', idUD);
        $.ajax({
            url: '/index.php?r=student%2Fview&id=' + id,
            type: 'POST',
            contentType: false,
            processData: false,
            data: data,
            success: function(res) {
                $.pjax.reload({container: '#document-table-pjax', replace: false});
                $('#update-document-modal').modal('hide');
            }
        });
        return false;
    });
JS
);
