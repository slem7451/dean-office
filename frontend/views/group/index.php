<?php
/** @var \frontend\models\GroupForm $model */

/** @var \frontend\models\GroupForm $selectedGroup */

/** @var \yii\data\ActiveDataProvider $dataProvider */

use yii\bootstrap4\Modal;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\widgets\Pjax;

$this->title = 'Группы';

$updateIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
  <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
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
            'operation' => 'c'
        ]);
        Modal::end();
        ActiveForm::end();
        Pjax::begin(['id' => 'group-table-pjax']);
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{pager}",
            'rowOptions' => function ($model, $key, $index, $grid) {
                return ['class' => 'group-row', 'id' => $model->id . '-' . 'group-id', 'title' => 'Посмотреть подробно'];
            },
            'columns' => [
                [
                    'header' => 'Название группы',
                    'content' => function ($model) {
                        return '<p>' . $model->name . '</p>';
                    }
                ],
                [
                    'header' => 'Дата начала',
                    'content' => function ($model) {
                        return '<p>' . date('d.m.Y', strtotime($model->created_at)) . '</p>';
                    }
                ],
                [
                    'header' => 'Дата окончания',
                    'content' => function ($model) {
                        return '<p>' . date('d.m.Y', strtotime($model->closed_at)) . '</p>';
                    }
                ],
                [
                    'header' => 'Действия',
                    'content' => function ($model) use ($updateIcon) {
                        return '<div class="row none-margin">
                                    <button id="' . $model->id . '-update-group-id" class="update-group-btn" title="Редактировать">' . $updateIcon . '</button>' .
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
    'operation' => 'u'
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