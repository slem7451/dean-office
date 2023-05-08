<?php

use frontend\models\TemplateForm;
use yii\bootstrap4\Modal;
use yii\bootstrap5\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var \frontend\models\TemplateForm $model */
/** @var \yii\data\ArrayDataProvider $dataProvider */
/** @var \frontend\models\TemplateForm $selectedTemplate */
/** @var string $templateExample */

$this->title = 'Шаблоны';

$questionIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247zm2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"/>
</svg>';

$updateIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
  <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
</svg>';

$deleteIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
</svg>';
?>
    <div class="template-container">
        <div class="alert alert-danger alert-dismissible" style="display: none">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <i class="icon fas fa-ban"></i>Невозможно удалить шаблон, так как он привязан к приказу/справке.
        </div>
        <div class="row">
            <div class="col-2">
                <?php
                $form = ActiveForm::begin(['id' => 'template-form']);
                Modal::begin([
                    'id' => 'template-modal',
                    'toggleButton' => ['label' => 'Создать шаблон', 'class' => 'btn btn-primary mg-bottom-15px'],
                    'size' => 'modal-lg',
                    'title' => 'Создание шаблона <button class="question-btn" type="button" title="Инструкция" data-toggle="modal" data-target="#template-instruction-modal">' . $questionIcon . '</button>',
                    'footer' => Html::submitButton('Создать', ['class' => 'btn btn-success mg-right-76-p']) . Html::button('Закрыть', [
                            'class' => 'btn btn-danger',
                            'data-dismiss' => 'modal'
                        ])
                ]);
                echo $this->render('_template-form-modal', [
                    'model' => $model,
                    'form' => $form,
                    'operation' => OPERATION_CREATE,
                    'templateExample' => $templateExample,
                    'view' => false
                ]);
                Modal::end();
                ActiveForm::end();
                ?>
            </div>
            <div class="col-10">
                <?php
                Modal::begin([
                    'id' => 'template-instruction-modal',
                    'toggleButton' => ['label' => 'Инструкция', 'class' => 'btn btn-primary mg-bottom-15px'],
                    'size' => 'modal-lg',
                    'title' => 'Инструкция',
                    'footer' => Html::button('Закрыть', [
                        'class' => 'btn btn-danger',
                        'data-dismiss' => 'modal'
                    ])
                ]);
                echo $this->render('_instruction');
                Modal::end();
                ?>
            </div>
        </div>
        <?php Pjax::begin(['id' => 'template-table-pjax']); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-bordered table-hover dataTable dtr-inline'],
            'layout' => "{items}\n{pager}",
            'rowOptions' => function ($model, $key, $index, $grid) {
                return ['class' => 'template-row cursor-pointer', 'id' => $model['template']['id'] . '%' . $model['type'] . '-template-id', 'title' => 'Посмотреть подробно'];
            },
            'columns' => [
                [
                    'header' => 'Номер',
                    'content' => function ($model) {
                        return $model['template']['id'];
                    }
                ],
                [
                    'header' => 'Тип',
                    'content' => function ($model) {
                        return $model['type'] == TemplateForm::TYPE_DECREE ? 'Приказ' : 'Справка';
                    }
                ],
                [
                    'header' => 'Название',
                    'content' => function ($model) {
                        return $model['template']['name'];
                    }
                ],
                [
                    'header' => 'Действия',
                    'content' => function ($model) use ($deleteIcon, $updateIcon) {
                        return '<div class="row none-margin">
                                    <button id="' . $model['template']['id'] . '%' . $model['type'] . '-update-template-id" class="update-template-btn action-btn" title="Редактировать">' . $updateIcon . '</button>' . '<p class="col-1"></p>' .
                            '<button id="' . $model['template']['id'] . '%' . $model['type'] . '-delete-template-id" class="delete-template-btn action-btn" title="Удалить">' . $deleteIcon . '</button>
                                </div>';
                    }
                ],
            ]
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
    <div id="btn-clicked" class="none-display">%</div>
    <div id="type-template" class="none-display">%</div>
<?php
Pjax::begin(['id' => 'update-template-pjax']);
$form = ActiveForm::begin(['id' => 'update-template-form']);
Modal::begin([
    'id' => 'update-template-modal',
    'title' => 'Редактирование шаблона',
    'size' => 'modal-lg',
    'footer' => Html::submitButton('Сохранить', ['class' => 'btn btn-success mg-right-74-p']) . Html::button('Закрыть', [
            'class' => 'btn btn-danger',
            'data-dismiss' => 'modal'
        ])
]);
echo $this->render('_template-form-modal', [
    'form' => $form,
    'model' => $selectedTemplate,
    'operation' => OPERATION_UPDATE,
    'templateExample' => $templateExample,
    'view' => false
]);
Modal::end();
ActiveForm::end();
Pjax::end();
?>
<?php
Pjax::begin(['id' => 'view-template-pjax']);
$form = ActiveForm::begin(['id' => 'view-template-form']);
Modal::begin([
    'id' => 'view-template-modal',
    'title' => 'Просмотр шаблона',
    'size' => 'modal-lg',
    'footer' => Html::button('Закрыть', [
        'class' => 'btn btn-danger',
        'data-dismiss' => 'modal'
    ])
]);
echo $this->render('_template-form-modal', [
    'form' => $form,
    'model' => $selectedTemplate,
    'operation' => OPERATION_UPDATE,
    'templateExample' => $templateExample,
    'view' => true
]);
Modal::end();
ActiveForm::end();
Pjax::end();
?>
<?php
$this->registerJS(<<<JS
    $('#template-modal').on('hidden.bs.modal', function () {
        $('#template-form')[0].reset();
        $('.example-template').html('');
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('hidden.bs.modal', '#update-template-modal', function () {
        $('#btn-clicked').html('%');
        $('#type-template').html('%');
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('beforeSubmit', '#template-form', function() {
        var data = $(this).serialize();
        $.ajax({
            url: '/index.php?r=template%2Findex',
            type: 'POST',
            data: data,
            success: function(res) {
                $.pjax.reload({container: '#template-table-pjax', replace: false});
                $('#template-modal').modal('hide');
            }
        });
        return false;
    });
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.template-row', function() {
        var isButton = $('#btn-clicked').html();
        if (isButton == '%') {
            var idUT = this.id.split('%')[0];
            var typeUT = this.id.split('%')[1].split('-')[0];
            $.pjax.reload({container: '#view-template-pjax', data: {idUT: idUT, typeUT: typeUT}, replace: false});
        }
    })
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.update-template-btn', function() {
        var idUT = this.id.split('%')[0];
        var typeUT = this.id.split('%')[1].split('-')[0];
        $.pjax.reload({container: '#update-template-pjax', data: {idUT: idUT, typeUT: typeUT}, replace: false});
        $('#btn-clicked').html(idUT);
        $('#type-template').html(typeUT);
    })
JS
);

$this->registerJs(<<<JS
    $(document).on('click', '.delete-template-btn', function() {
        $('#btn-clicked').html('1');
        if (confirm('Вы уверены, что хотите удалить данный шаблон?')) {
            var idDT = this.id.split('%')[0];
            var typeDT = this.id.split('%')[1].split('-')[0];
            $.ajax({
                url: '/index.php?r=template%2Findex',
                type: 'POST',
                data: {idDT: idDT, typeDT: typeDT},
                success: function(res) {
                    if (res == '0') {
                        $('.alert-danger').css('display', '').delay(5000).fadeOut();
                    }
                    $.pjax.reload({container: '#template-table-pjax', replace: false});
                }
            });
        } else {
            $.pjax.reload({container: '#template-table-pjax', replace: false});
        }
    })
JS
);

$this->registerJs(<<<JS
    $('#template-table-pjax').on('pjax:success', function () {
        $('#btn-clicked').html('%');
        $('#type-template').html('%');
    });
JS
);

$this->registerJs(<<<JS
    $('#update-template-pjax').on('pjax:success', function () {
        $('#update-template-modal').modal('show');
    });
JS
);

$this->registerJs(<<<JS
    $('#view-template-pjax').on('pjax:success', function () {
        $('#view-template-modal').modal('show');
    });
JS
);

$this->registerJS(<<<JS
    $(document).on('beforeSubmit', '#update-template-form', function() {
        var data = new FormData($(this)[0]);
        data.append('idUT', $('#btn-clicked').html());
        data.append('typeUT', $('#type-template').html());
        $.ajax({
            url: '/index.php?r=template%2Findex',
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            success: function(res) {
                $.pjax.reload({container: '#template-table-pjax', replace: false});
                $('#update-template-modal').modal('hide');
            }
        });
        return false;
    })
JS
);
