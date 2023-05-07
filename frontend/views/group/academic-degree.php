<?php

use yii\bootstrap4\Modal;
use yii\bootstrap5\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var \frontend\models\AcademicDegreeForm $model */
/** @var \yii\data\ActiveDataProvider $dataProvider*/
/** @var \frontend\models\AcademicDegreeForm $selectedAcademicDegree */

$this->title = 'Академические степени';

$updateIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
  <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
</svg>';

$closeIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
</svg>';
?>
    <div class="alert alert-danger alert-dismissible" style="display: none">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="icon fas fa-ban"></i>Невозможно удалить степень, так как она привязана к какой-либо группе.
    </div>
    <div class="academic-degree-container">
        <?php
        $form = ActiveForm::begin(['id' => 'academic-form']);
        Modal::begin([
            'id' => 'academic-modal',
            'toggleButton' => ['label' => 'Добавить академическую степень', 'class' => 'btn btn-primary mg-bottom-15px'],
            'title' => 'Добавление академической степени',
            'footer' => Html::submitButton('Создать', ['class' => 'btn btn-success save-student-btn']) . Html::button('Закрыть', [
                    'class' => 'btn btn-danger',
                    'data-dismiss' => 'modal'
                ])
        ]);
        echo $this->render('_academic-form-modal', [
            'form' => $form,
            'model' => $model,
            'operation' => OPERATION_CREATE
        ]);
        Modal::end();
        ActiveForm::end();
        Pjax::begin(['id' => 'academic-table-pjax']);
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
                    'header' => 'Действия',
                    'content' => function ($model) use ($updateIcon, $closeIcon) {
                        return '<div class="row none-margin">
                                    <button id="' . $model->id . '-update-academic-id" class="update-academic-btn action-btn" title="Редактировать">' . $updateIcon . '</button>' . '<p class="width-1-p"></p>' .
                            '<button id="' . $model->id . '-delete-academic-id" class="delete-academic-btn action-btn" title="Удалить">' . $closeIcon . '</button>
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
Pjax::begin(['id' => 'update-academic-pjax']);
$form = ActiveForm::begin(['id' => 'update-academic-form']);
Modal::begin([
    'id' => 'update-academic-modal',
    'title' => 'Редактирование академической степени',
    'footer' => Html::submitButton('Сохранить', ['class' => 'btn btn-success update-student-btn-modal']) . Html::button('Закрыть', [
            'class' => 'btn btn-danger',
            'data-dismiss' => 'modal'
        ])
]);
echo $this->render('_academic-form-modal', [
    'form' => $form,
    'model' => $selectedAcademicDegree,
    'operation' => OPERATION_UPDATE
]);
Modal::end();
ActiveForm::end();
Pjax::end();
?>
<?php
$this->registerJS(<<<JS
    $(document).on('hidden.bs.modal', '#academic-modal', function () {
        $('#academic-form')[0].reset();
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('beforeSubmit', '#academic-form', function() {
        var data = $(this).serialize();
        $.ajax({
            url: '/index.php?r=group%2Facademic-degree',
            type: 'POST',
            data: data,
            success: function(res) {
                $.pjax.reload({container: '#academic-table-pjax', replace: false});
                $('#academic-modal').modal('hide');
            }
        });
        return false;
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('hidden.bs.modal', '#update-academic-modal', function () {
        $('#btn-clicked').html('0');
    })
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.update-academic-btn', function() {
        var idUA = this.id.split('-')[0];
        $.pjax.reload({container: '#update-academic-pjax', data: {idUA: idUA}, replace: false});
        $('#btn-clicked').html(idUA);
    })
JS
);

$this->registerJs(<<<JS
    $('#update-academic-pjax').on('pjax:success', function () {
        $('#update-academic-modal').modal('show');
    });
JS
);

$this->registerJs(<<<JS
    $('#academic-table-pjax').on('pjax:success', function () {
        $('#btn-clicked').html('0');
    });
JS
);

$this->registerJS(<<<JS
    $(document).on('beforeSubmit', '#update-academic-form', function() {
        var data = $(this).serialize();
        data += '&idUA=' + $('#btn-clicked').html();
        $.ajax({
            url: '/index.php?r=group%2Facademic-degree',
            type: 'POST',
            data: data,
            success: function(res) {
                $.pjax.reload({container: '#academic-table-pjax', replace: false});
                $('#update-academic-modal').modal('hide');
            }
        });
        return false;
    })
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.delete-academic-btn', function() {
        if (confirm('Вы уверены, что хотите удалить данную степень?')) {
            var idDA = this.id.split('-')[0];
            $.ajax({
                url: '/index.php?r=group%2Facademic-degree',
                type: 'POST',
                data: {idDA: idDA},
                success: function(res) {
                    if (res == '0') {
                        $('.alert-danger').css('display', '').delay(5000).fadeOut();
                    } else {
                        $.pjax.reload({container: '#academic-table-pjax', replace: false});
                    }
                }
            });
        }
    })
JS
);