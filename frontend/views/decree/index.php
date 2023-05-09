<?php
/** @var \frontend\models\DecreeForm $model */
/** @var \frontend\models\DecreeForm $selectedDecree */
/** @var \frontend\models\DecreeTemplate $templates */
/** @var \frontend\models\Student $students */
/** @var \yii\data\ActiveDataProvider $dataProvider */

use yii\bootstrap4\Modal;
use yii\bootstrap5\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = 'Приказы';

$updateIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
  <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
</svg>';

$deleteIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
</svg>';
?>
    <div class="decree-container">
        <?php
        $form = ActiveForm::begin(['id' => 'decree-form']);
        Modal::begin([
            'id' => 'decree-modal',
            'toggleButton' => ['label' => 'Сделать приказ', 'class' => 'btn btn-primary mg-bottom-15px'],
            'title' => 'Создание приказа',
            'footer' => Html::submitButton('Создать', ['class' => 'btn btn-success mg-right-61-p']) . Html::button('Закрыть', [
                    'class' => 'btn btn-danger',
                    'data-dismiss' => 'modal'
                ])
        ]);
        echo $this->render('_decree-form-modal', [
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
        <?php Pjax::begin(['id' => 'decree-table-pjax']); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-bordered table-hover dataTable dtr-inline'],
            'layout' => "{items}\n{pager}",
            'rowOptions' => function ($model, $key, $index, $grid) {
                return ['class' => 'decree-row cursor-pointer', 'id' => $model->id . '-decree-id', 'title' => 'Посмотреть подробно'];
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
                    'header' => 'Дата приказа',
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
                                    <button id="' . $model->id . '-update-decree-id" class="update-decree-btn action-btn" title="Редактировать">' . $updateIcon . '</button>' . '<p class="col-1"></p>' .
                            '<button id="' . $model->id . '-delete-decree-id" class="delete-decree-btn action-btn" title="Удалить">' . $deleteIcon . '</button>
                                </div>';
                    }
                ],
            ]
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
    <div id="btn-clicked" class="none-display">0</div>
<?php
Pjax::begin(['id' => 'update-decree-pjax']);
$form = ActiveForm::begin(['id' => 'update-decree-form']);
Modal::begin([
    'id' => 'update-decree-modal',
    'title' => 'Редактирование приказа',
    'footer' => Html::submitButton('Сохранить', ['class' => 'btn btn-success mg-right-58-p']) . Html::button('Закрыть', [
            'class' => 'btn btn-danger',
            'data-dismiss' => 'modal'
        ])
]);
echo $this->render('_decree-form-modal', [
    'model' => $selectedDecree,
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
Pjax::begin(['id' => 'view-decree-pjax']);
$form = ActiveForm::begin(['id' => 'view-decree-form']);
Modal::begin([
    'id' => 'view-decree-modal',
    'title' => 'Просмотр приказа',
    'footer' => Html::button('Закрыть', [
        'class' => 'btn btn-danger',
        'data-dismiss' => 'modal'
    ])
]);
echo $this->render('_decree-form-modal', [
    'model' => $selectedDecree,
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
    $(document).on('hidden.bs.modal', '#decree-modal', function () {
        $('#decree-form')[0].reset();
    });
JS
);

$this->registerJS(<<<JS
    $(document).on('hidden.bs.modal', '#update-decree-modal', function () {
        $('#btn-clicked').html('0');
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('beforeSubmit', '#decree-form', function() {
        var data = $(this).serialize();
        $.ajax({
            url: '/index.php?r=decree%2Findex',
            type: 'POST',
            data: data,
            success: function(res) {
                $.pjax.reload({container: '#decree-table-pjax', replace: false});
                $('#decree-modal').modal('hide');
            }
        });
        return false;
    });
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.decree-row', function() {
        var isButton = $('#btn-clicked').html();
        if (isButton == '0') {
            var idUD = this.id.split('-')[0];
            $.pjax.reload({container: '#view-decree-pjax', data: {idUD: idUD}, replace: false});
        }
    })
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.update-decree-btn', function() {
        var idUD = this.id.split('-')[0];
        $.pjax.reload({container: '#update-decree-pjax', data: {idUD: idUD}, replace: false});
        $('#btn-clicked').html(idUD);
    })
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.delete-decree-btn', function() {
        $('#btn-clicked').html('1');
        if (confirm('Вы уверены, что хотите удалить данный приказ?')) {
            var idDD = this.id.split('-')[0];
            $.ajax({
                url: '/index.php?r=decree%2Findex',
                type: 'POST',
                data: {idDD: idDD},
                success: function(res) {
                    $.pjax.reload({container: '#decree-table-pjax', replace: false});
                }
            });
        } else {
            $.pjax.reload({container: '#decree-table-pjax', replace: false});
        }
    })
JS
);

$this->registerJs(<<<JS
    $('#decree-table-pjax').on('pjax:success', function () {
        $('#btn-clicked').html('0');
    });
JS
);

$this->registerJs(<<<JS
    $('#update-decree-pjax').on('pjax:success', function () {
        $('#update-decree-modal').modal('show');
    });
JS
);

$this->registerJs(<<<JS
    $('#view-decree-pjax').on('pjax:success', function () {
        $('#view-decree-modal').modal('show');
    });
JS
);

$this->registerJS(<<<JS
    $(document).on('beforeSubmit', '#update-decree-form', function() {
        var data = new FormData($(this)[0]);
        data.append('idUD', $('#btn-clicked').html());
        $.ajax({
            url: '/index.php?r=decree%2Findex',
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            success: function(res) {
                $.pjax.reload({container: '#decree-table-pjax', replace: false});
                $('#update-decree-modal').modal('hide');
            }
        });
        return false;
    })
JS
);