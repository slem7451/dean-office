<?php
/** @var \frontend\models\GroupForm $model */

/** @var \frontend\models\GroupForm $selectedGroup */

/** @var \yii\data\ActiveDataProvider $dataProvider */

/** @var \frontend\models\Direction $directions */

/** @var \frontend\models\Flow $flows */

/** @var \frontend\models\Flow $flow */

use yii\bootstrap4\Modal;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\widgets\Pjax;

$this->title = $flow->name;
?>

    <div class="group-view-container">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-title col-10">
                    <div class="row">
                        <div class="mg-right-20-px col-1">Группы</div>
                        <?= $this->render('/group/_group-filter', ['flows' => null, 'directions' => $directions]) ?>
                    </div>
                </div>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php
                Pjax::begin(['id' => 'group-table-pjax']);
                echo $this->render('/group/_group-view', ['dataProvider' => $dataProvider]);
                Pjax::end();
                ?>
            </div>
        </div>
    </div>
    <div id="btn-clicked" class="none-display">0</div>
<?php
Pjax::begin(['id' => 'update-group-pjax']);
$form = ActiveForm::begin(['id' => 'update-group-form']);
Modal::begin([
    'id' => 'update-group-modal',
    'title' => 'Редактирование группы',
    'footer' => Html::submitButton('Сохранить', ['class' => 'btn btn-success mg-right-58-p']) . Html::button('Закрыть', [
            'class' => 'btn btn-danger',
            'data-dismiss' => 'modal'
        ])
]);
echo $this->render('/group/_group-form-modal', [
    'form' => $form,
    'model' => $selectedGroup,
    'operation' => OPERATION_UPDATE,
    'directions' => $directions,
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

$this->registerJS(<<<JS
    $(document).on('input', '#group-search-name', function () {
        $.pjax.reload({container: '#group-table-pjax', data: {
            GN: $('#group-search-name').val(),
            GD: $('#group-search-direction').val(),
            GC: $('#group-search-closed_at').val()
            }, replace: false});
    });
JS
);

$this->registerJS(<<<JS
    $(document).on('change', '#group-search-direction', function () {
        $.pjax.reload({container: '#group-table-pjax', data: {
            GN: $('#group-search-name').val(),
            GD: $('#group-search-direction').val(),
            GC: $('#group-search-closed_at').val()
            }, replace: false});
    });
JS
);

$this->registerJS(<<<JS
    $(document).on('change', '#group-search-closed_at', function () {
        $.pjax.reload({container: '#group-table-pjax', data: {
            GN: $('#group-search-name').val(),
            GD: $('#group-search-direction').val(),
            GC: $('#group-search-closed_at').val()
            }, replace: false});
    });
JS
);