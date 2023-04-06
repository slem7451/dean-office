<?php
/** @var \frontend\models\GroupForm $model */

/** @var \yii\data\ActiveDataProvider $dataProvider */

use kartik\date\DatePicker;
use yii\bootstrap5\Modal;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Группы';
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
                    'data-bs-dismiss' => 'modal'
                ])
        ]);
        echo $form->field($model, 'name')->textInput(['placeholder' => 'Название группы'])->label(false);
        ?>
        <div class="row">
            <?php
            echo $form->field($model, 'created_at', ['options' => ['class' => 'col-6']])->widget(DatePicker::class, [
                'name' => 'dp_created_at',
                'type' => DatePicker::TYPE_INPUT,
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true
                ],
                'options' => [
                    'placeholder' => 'Дата начала'
                ],
            ])->label(false);
            echo $form->field($model, 'closed_at', ['options' => ['class' => 'col-6']])->widget(DatePicker::class, [
                'name' => 'dp_closed_at',
                'type' => DatePicker::TYPE_INPUT,
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true,
                ],
                'options' => [
                    'placeholder' => 'Дата окончания'
                ],
            ])->label(false);
            ?>
        </div>
        <?php
        Modal::end();
        ActiveForm::end();
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{pager}",
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
                ]
            ]
        ])
        ?>
    </div>

<?php
$this->registerJS(<<<JS
    $('#group-modal').on('hidden.bs.modal', function () {
        $('#group-form')[0].reset();
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
                $('#group-modal').modal('hide');
            }
        });
        return false;
    })
JS
);