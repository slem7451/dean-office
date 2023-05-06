<?php
/** @var \yii\bootstrap5\ActiveForm $form */

/** @var \frontend\models\GroupForm $model */

/** @var string $operation */

use kartik\date\DatePicker;

?>

<?php
echo $form->field($model, 'name')->textInput(['placeholder' => 'Название группы'])->label(false);
?>
<div class="row">
    <?php
    echo $form->field($model, 'created_at', ['options' => ['class' => 'col-6']])->widget(DatePicker::class, [
        'name' => 'dp_created_at-' . $operation,
        'type' => DatePicker::TYPE_INPUT,
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'autoclose' => true
        ],
        'options' => [
            'id' => 'dp_created_at-' . $operation,
            'placeholder' => 'Дата начала'
        ],
    ])->label(false);
    echo $form->field($model, 'closed_at', ['options' => ['class' => 'col-6']])->widget(DatePicker::class, [
        'name' => 'dp_closed_at-' . $operation,
        'type' => DatePicker::TYPE_INPUT,
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'autoclose' => true,
        ],
        'options' => [
                'id' => 'dp_closed_at-' . $operation,
            'placeholder' => 'Дата окончания'
        ],
    ])->label(false);
    ?>
</div>
