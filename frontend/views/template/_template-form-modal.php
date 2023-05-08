<?php
/** @var \yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\TemplateForm $model */
/** @var string $operation */
/** @var string $templateExample */
/** @var boolean $view */

use frontend\models\TemplateForm;

?>
    <div class="row mg-bottom-15px">
        <?php
        echo $form->field($model, 'id', ['options' => ['class' => 'col-6']])->textInput([
            'placeholder' => 'Номер',
            'disabled' => $view
        ])->label(false);
        echo $form->field($model, 'type', ['options' => ['class' => 'col-6']])->dropDownList([
            TemplateForm::TYPE_DECREE => 'Приказ',
            TemplateForm::TYPE_CERTIFICATE => 'Справка'
        ], [
            'prompt' => ['text' => 'Тип', 'options' => ['disabled' => true, 'selected' => true]],
            'class' => 'form-control',
            'disabled' => $operation == OPERATION_UPDATE
        ])->label(false);
        ?>
    </div>
<?php
echo $form->field($model, 'name')->textInput([
    'placeholder' => 'Название',
    'disabled' => $view
])->label(false);
echo $form->field($model, 'template')->textarea([
    'placeholder' => 'Шаблон',
    'rows' => 10,
    'class' => 'resize-none form-control template-text',
    'disabled' => $view
])->label(false);
?>
    <div class="card card-outline card-info collapsed-card">
        <div class="card-header">
            <h3 class="card-title">Пример результата</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                            class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="card-body example-template">
            <?= $templateExample ?>
        </div>
    </div>
<?php
$this->registerJS(<<<JS
    $(document).on('input', '.template-text', function () {
        $.ajax({
            url: '/index.php?r=template%2Findex',
            type: 'POST',
            data: {template: $(this).val() + '@'},
            success: function(res) {
                $('.example-template').html(res);
            }
        });
    });
JS
);
