<?php
/** @var \frontend\models\GroupForm $model */

/** @var \frontend\models\GroupForm $selectedGroup */

/** @var \yii\data\ActiveDataProvider $dataProvider */

/** @var \frontend\models\Direction $directions */

/** @var \frontend\models\AcademicDegree $academicDegrees */

/** @var \frontend\models\Flow $flows */

use yii\bootstrap4\Modal;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\widgets\Pjax;

$this->title = 'Группы';

$updateIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
  <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
</svg>';

$closeIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
</svg>';
?>

    <div class="group-container">
        <?php
        $form = ActiveForm::begin(['id' => 'group-form']);
        Modal::begin([
            'id' => 'group-modal',
            'toggleButton' => ['label' => 'Создать группу', 'class' => 'btn btn-primary mg-bottom-15px'],
            'title' => 'Создание группы',
            'footer' => Html::submitButton('Создать', ['class' => 'btn btn-success save-group-btn']) . Html::button('Закрыть', [
                    'class' => 'btn btn-danger',
                    'data-dismiss' => 'modal'
                ])
        ]);
        echo $this->render('_group-form-modal', [
            'form' => $form,
            'model' => $model,
            'operation' => OPERATION_CREATE,
            'directions' => $directions,
            'academicDegrees' => $academicDegrees,
            'flows' => $flows
        ]);
        Modal::end();
        ActiveForm::end();
        Pjax::begin(['id' => 'group-table-pjax']);
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-bordered table-hover dataTable dtr-inline'],
            'layout' => "{items}\n{pager}",
            'rowOptions' => function ($model, $key, $index, $grid) {
                return ['class' => 'group-row', 'id' => $model->id . '-' . 'group-id', 'title' => 'Посмотреть подробно'];
            },
            'columns' => [
                [
                    'header' => 'Название',
                    'content' => function ($model) {
                        return $model->name;
                    }
                ],
                [
                    'header' => 'Поток',
                    'content' => function ($model) {
                        return $model->flow->name;
                    }
                ],
                [
                    'header' => 'Кол-во студентов',
                    'content' => function ($model) {
                        return count($model->students);
                    }
                ],
                [
                    'header' => 'Направление',
                    'content' => function ($model) {
                        return $model->direction->name;
                    }
                ],
                [
                    'header' => 'Академическая степень',
                    'content' => function ($model) {
                        return $model->academicDegree->name;
                    }
                ],
                [
                    'header' => 'Дата начала',
                    'content' => function ($model) {
                        return date('d.m.Y', strtotime($model->created_at));
                    }
                ],
                [
                    'header' => 'Дата окончания',
                    'content' => function ($model) {
                        return $model->closed_at ? date('d.m.Y', strtotime($model->closed_at)) : '';
                    }
                ],
                [
                    'header' => 'Статус',
                    'content' => function ($model) {
                        return $model->closed_at ? 'Закрыта' : 'На обучении';
                    }
                ],
                [
                    'header' => 'Действия',
                    'content' => function ($model) use ($updateIcon, $closeIcon) {
                        if ($model->closed_at) {
                            return '';
                        }
                        return '<div class="row none-margin">
                                    <button id="' . $model->id . '-update-group-id" class="update-group-btn action-btn" title="Редактировать">' . $updateIcon . '</button>' . '<p class="width-1-p"></p>' .
                                    '<button id="' . $model->id . '-close-group-id" class="close-group-btn action-btn" title="Закрыть">' . $closeIcon . '</button>' .
                                '</div>';
                    }
                ],
            ]
        ]);
        Pjax::end();
        ?>
    </div>
    <div id="btn-clicked" class="none-display">0</div>
<?php
Pjax::begin(['id' => 'update-group-pjax']);
$form = ActiveForm::begin(['id' => 'update-group-form']);
Modal::begin([
    'id' => 'update-group-modal',
    'title' => 'Редактирование группы',
    'footer' => Html::submitButton('Сохранить', ['class' => 'btn btn-success update-group-btn-modal']) . Html::button('Закрыть', [
            'class' => 'btn btn-danger',
            'data-dismiss' => 'modal'
        ])
]);
echo $this->render('_group-form-modal', [
    'form' => $form,
    'model' => $selectedGroup,
    'operation' => OPERATION_UPDATE,
    'directions' => $directions,
    'academicDegrees' => $academicDegrees,
    'flows' => $flows
]);
Modal::end();
ActiveForm::end();
Pjax::end();
?>
<?php
$this->registerJS(<<<JS
    $('#group-modal').on('hidden.bs.modal', function () {
        $('#group-form')[0].reset();
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('hidden.bs.modal', '#update-group-modal', function () {
        $('#btn-clicked').html('0');
    })
JS
);

$this->registerJS(<<<JS
    $('#group-form').on('beforeSubmit', function() {
        var data = $(this).serialize();
        $.ajax({
            url: '/index.php?r=group%2Findex',
            type: 'POST',
            data: data,
            success: function(res) {
                $.pjax.reload({container: '#group-table-pjax', replace: false});
                $('#group-modal').modal('hide');
            }
        });
        return false;
    })
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.group-row', function() {
        var id = this.id.split('-')[0];
        var isButton = $('#btn-clicked').html();
        if (isButton == '0') {
            window.location.href = 'http://localhost:20080/index.php?r=group%2Fview&id=' + id;
        }
    })
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.update-group-btn', function() {
        var idUG = this.id.split('-')[0];
        $.pjax.reload({container: '#update-group-pjax', data: {idUG: idUG}, replace: false});
        $('#btn-clicked').html(idUG);
    })
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.close-group-btn', function() {
        $('#btn-clicked').html('1');
        if (confirm('Вы уверены, что хотите закрыть данную группу?')) {
            var idCG = this.id.split('-')[0];
            $.ajax({
                url: '/index.php?r=group%2Findex',
                type: 'POST',
                data: {idCG: idCG},
                success: function(res) {
                    $.pjax.reload({container: '#group-table-pjax', replace: false});
                }
            });
        } else {
            $.pjax.reload({container: '#group-table-pjax', replace: false});
        }
    })
JS
);

$this->registerJs(<<<JS
    $('#group-table-pjax').on('pjax:success', function () {
        $('#btn-clicked').html('0');
    });
JS
);

$this->registerJs(<<<JS
    $('#update-group-pjax').on('pjax:success', function () {
        $('#update-group-modal').modal('show');
    });
JS
);

$this->registerJS(<<<JS
    $(document).on('beforeSubmit', '#update-group-form', function() {
        var data = $(this).serialize();
        data += '&idUG=' + $('#btn-clicked').html();
        $.ajax({
            url: '/index.php?r=group%2Findex',
            type: 'POST',
            data: data,
            success: function(res) {
                $.pjax.reload({container: '#group-table-pjax', replace: false});
                $('#update-group-modal').modal('hide');
            }
        });
        return false;
    })
JS
);