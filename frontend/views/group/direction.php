<?php

use yii\bootstrap4\Modal;
use yii\bootstrap5\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var \frontend\models\DirectionForm $model */
/** @var \yii\data\ActiveDataProvider $dataProvider*/
/** @var \frontend\models\DirectionForm $selectedDirection */

$this->title = 'Направления';

$updateIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
  <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
</svg>';

$closeIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
</svg>';
?>
    <div class="alert alert-danger alert-dismissible" style="display: none">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="icon fas fa-ban"></i>Невозможно удалить направление, так как оно привязано к какой-либо группе.
    </div>
    <div class="direction-container">
        <?php
        $form = ActiveForm::begin(['id' => 'direction-form']);
        Modal::begin([
            'id' => 'direction-modal',
            'toggleButton' => ['label' => 'Добавить направление', 'class' => 'btn btn-primary mg-bottom-15px'],
            'title' => 'Добавление направления',
            'footer' => Html::submitButton('Создать', ['class' => 'btn btn-success save-student-btn']) . Html::button('Закрыть', [
                    'class' => 'btn btn-danger',
                    'data-dismiss' => 'modal'
                ])
        ]);
        echo $this->render('_direction-form-modal', [
            'form' => $form,
            'model' => $model,
            'operation' => OPERATION_CREATE
        ]);
        Modal::end();
        ActiveForm::end();
        Pjax::begin(['id' => 'direction-table-pjax']);
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{pager}",
            'columns' => [
                [
                    'header' => 'Номер',
                    'content' => function ($model) {
                        return $model->id;
                    }
                ],
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
                                    <button id="' . $model->id . '-update-direction-id" class="update-direction-btn action-btn" title="Редактировать">' . $updateIcon . '</button>' . '<p class="width-1-p"></p>' .
                            '<button id="' . $model->id . '-delete-direction-id" class="delete-direction-btn action-btn" title="Удалить">' . $closeIcon . '</button>
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
Pjax::begin(['id' => 'update-direction-pjax']);
$form = ActiveForm::begin(['id' => 'update-direction-form']);
Modal::begin([
    'id' => 'update-direction-modal',
    'title' => 'Редактирование направления',
    'footer' => Html::submitButton('Сохранить', ['class' => 'btn btn-success update-student-btn-modal']) . Html::button('Закрыть', [
            'class' => 'btn btn-danger',
            'data-dismiss' => 'modal'
        ])
]);
echo $this->render('_direction-form-modal', [
    'form' => $form,
    'model' => $selectedDirection,
    'operation' => OPERATION_UPDATE
]);
Modal::end();
ActiveForm::end();
Pjax::end();
?>
<?php
$this->registerJS(<<<JS
    $(document).on('hidden.bs.modal', '#direction-modal', function () {
        $('#direction-form')[0].reset();
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('beforeSubmit', '#direction-form', function() {
        var data = $(this).serialize();
        $.ajax({
            url: '/index.php?r=group%2Fdirection',
            type: 'POST',
            data: data,
            success: function(res) {
                $.pjax.reload({container: '#direction-table-pjax', replace: false});
                $('#direction-modal').modal('hide');
            }
        });
        return false;
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('hidden.bs.modal', '#update-direction-modal', function () {
        $('#btn-clicked').html('0');
    })
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.update-direction-btn', function() {
        var idUD = this.id.split('-')[0];
        $.pjax.reload({container: '#update-direction-pjax', data: {idUD: idUD}, replace: false});
        $('#btn-clicked').html(idUD);
    })
JS
);

$this->registerJs(<<<JS
    $('#update-direction-pjax').on('pjax:success', function () {
        $('#update-direction-modal').modal('show');
    });
JS
);

$this->registerJs(<<<JS
    $('#direction-table-pjax').on('pjax:success', function () {
        $('#btn-clicked').html('0');
    });
JS
);

$this->registerJS(<<<JS
    $(document).on('beforeSubmit', '#update-direction-form', function() {
        var data = $(this).serialize();
        data += '&idUD=' + $('#btn-clicked').html();
        $.ajax({
            url: '/index.php?r=group%2Fdirection',
            type: 'POST',
            data: data,
            success: function(res) {
                $.pjax.reload({container: '#direction-table-pjax', replace: false});
                $('#update-direction-modal').modal('hide');
            }
        });
        return false;
    })
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.delete-direction-btn', function() {
        if (confirm('Вы уверены, что хотите удалить данное направление?')) {
            var idDD = this.id.split('-')[0];
            $.ajax({
                url: '/index.php?r=group%2Fdirection',
                type: 'POST',
                data: {idDD: idDD},
                success: function(res) {
                    if (res == '0') {
                        $('.alert-danger').css('display', '').delay(5000).fadeOut();
                    } else {
                        $.pjax.reload({container: '#direction-table-pjax', replace: false});
                    }
                }
            });
        }
    })
JS
);