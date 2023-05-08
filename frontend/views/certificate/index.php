<?php
/** @var \frontend\models\CertificateForm $model */
/** @var \frontend\models\CertificateForm $selectedCertificate */
/** @var \frontend\models\CertificateTemplate $templates */
/** @var \frontend\models\Student $students */
/** @var \yii\data\ActiveDataProvider $dataProvider */

use yii\bootstrap4\Modal;
use yii\bootstrap5\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = 'Справки';

$updateIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
  <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
</svg>';

$deleteIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
</svg>';
?>
<div class="certificate-container">
    <?php
        $form = ActiveForm::begin(['id' => 'certificate-form']);
        Modal::begin([
            'id' => 'certificate-modal',
            'toggleButton' => ['label' => 'Сделать справку', 'class' => 'btn btn-primary mg-bottom-15px'],
            'title' => 'Создание справки',
            'footer' => Html::submitButton('Создать', ['class' => 'btn btn-success mg-right-61-p']) . Html::button('Закрыть', [
                    'class' => 'btn btn-danger',
                    'data-dismiss' => 'modal'
                ])
        ]);
        echo $this->render('_certificate-form-modal', [
            'model' => $model,
            'form' => $form,
            'operation' => OPERATION_CREATE,
            'templates' => $templates,
            'students' => $students,
            'view' => false
        ]);
        Modal::end();
        ActiveForm::end();
        ?>
    <?php Pjax::begin(['id' => 'certificate-table-pjax']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-bordered table-hover dataTable dtr-inline'],
        'layout' => "{items}\n{pager}",
        'rowOptions' => function ($model, $key, $index, $grid) {
            return ['class' => 'certificate-row cursor-pointer', 'id' => $model->id . '-certificate-id', 'title' => 'Посмотреть подробно'];
        },
        'columns' => [
            [
                'header' => 'Номер шаблона',
                'content' => function ($model) {
                    return $model->template_id;
                }
            ],
            [
                'header' => 'Название шаблона',
                'content' => function ($model) {
                    return $model->template->name;
                }
            ],
            [
                'header' => 'Дата справки',
                'content' => function ($model) {
                    return $model->created_at;
                }
            ],
            [
                'header' => 'Кол-во студентов',
                'content' => function ($model) {
                    return count($model->students);
                }
            ],
            [
                'header' => 'Действия',
                'content' => function ($model) use ($deleteIcon, $updateIcon) {
                    return '<div class="row none-margin">
                                    <button id="' . $model->id . '-update-certificate-id" class="update-certificate-btn action-btn" title="Редактировать">' . $updateIcon . '</button>' . '<p class="col-1"></p>' .
                        '<button id="' . $model->id . '-delete-certificate-id" class="delete-certificate-btn action-btn" title="Удалить">' . $deleteIcon . '</button>
                                </div>';
                }
            ],
        ]
    ]); ?>
    <?php Pjax::end(); ?>
</div>
    <div id="btn-clicked" class="none-display">0</div>
<?php
Pjax::begin(['id' => 'update-certificate-pjax']);
$form = ActiveForm::begin(['id' => 'update-certificate-form']);
Modal::begin([
    'id' => 'update-certificate-modal',
    'title' => 'Редактирование справки',
    'footer' => Html::submitButton('Сохранить', ['class' => 'btn btn-success mg-right-58-p']) . Html::button('Закрыть', [
            'class' => 'btn btn-danger',
            'data-dismiss' => 'modal'
        ])
]);
echo $this->render('_certificate-form-modal', [
    'model' => $selectedCertificate,
    'form' => $form,
    'operation' => OPERATION_UPDATE,
    'templates' => $templates,
    'students' => $students,
    'view' => false
]);
Modal::end();
ActiveForm::end();
Pjax::end();
?>
<?php
Pjax::begin(['id' => 'view-certificate-pjax']);
$form = ActiveForm::begin(['id' => 'view-certificate-form']);
Modal::begin([
    'id' => 'view-certificate-modal',
    'title' => 'Просмотр справки',
    'footer' => Html::button('Закрыть', [
            'class' => 'btn btn-danger',
            'data-dismiss' => 'modal'
        ])
]);
echo $this->render('_certificate-form-modal', [
    'model' => $selectedCertificate,
    'form' => $form,
    'operation' => 'v',
    'templates' => $templates,
    'students' => $students,
    'view' => true
]);
Modal::end();
ActiveForm::end();
Pjax::end();
?>
<?php
$this->registerJS(<<<JS
    $(document).on('hidden.bs.modal', '#certificate-modal', function () {
        $('#certificate-form')[0].reset();
    });
JS
);

$this->registerJS(<<<JS
    $(document).on('hidden.bs.modal', '#update-certificate-modal', function () {
        $('#btn-clicked').html('0');
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('beforeSubmit', '#certificate-form', function() {
        var data = $(this).serialize();
        $.ajax({
            url: '/index.php?r=certificate%2Findex',
            type: 'POST',
            data: data,
            success: function(res) {
                $.pjax.reload({container: '#certificate-table-pjax', replace: false});
                $('#certificate-modal').modal('hide');
            }
        });
        return false;
    });
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.certificate-row', function() {
        var isButton = $('#btn-clicked').html();
        if (isButton == '0') {
            var idUC = this.id.split('-')[0];
            $.pjax.reload({container: '#view-certificate-pjax', data: {idUC: idUC}, replace: false});
        }
    })
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.update-certificate-btn', function() {
        var idUC = this.id.split('-')[0];
        $.pjax.reload({container: '#update-certificate-pjax', data: {idUC: idUC}, replace: false});
        $('#btn-clicked').html(idUC);
    })
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.delete-certificate-btn', function() {
        $('#btn-clicked').html('1');
        if (confirm('Вы уверены, что хотите удалить данную справку?')) {
            var idDC = this.id.split('-')[0];
            $.ajax({
                url: '/index.php?r=certificate%2Findex',
                type: 'POST',
                data: {idDC: idDC},
                success: function(res) {
                    $.pjax.reload({container: '#certificate-table-pjax', replace: false});
                }
            });
        } else {
            $.pjax.reload({container: '#certificate-table-pjax', replace: false});
        }
    })
JS
);

$this->registerJs(<<<JS
    $('#certificate-table-pjax').on('pjax:success', function () {
        $('#btn-clicked').html('0');
    });
JS
);

$this->registerJs(<<<JS
    $('#update-certificate-pjax').on('pjax:success', function () {
        $('#update-certificate-modal').modal('show');
    });
JS
);

$this->registerJs(<<<JS
    $('#view-certificate-pjax').on('pjax:success', function () {
        $('#view-certificate-modal').modal('show');
    });
JS
);

$this->registerJS(<<<JS
    $(document).on('beforeSubmit', '#update-certificate-form', function() {
        var data = new FormData($(this)[0]);
        data.append('idUC', $('#btn-clicked').html());
        $.ajax({
            url: '/index.php?r=certificate%2Findex',
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            success: function(res) {
                $.pjax.reload({container: '#certificate-table-pjax', replace: false});
                $('#update-certificate-modal').modal('hide');
            }
        });
        return false;
    })
JS
);