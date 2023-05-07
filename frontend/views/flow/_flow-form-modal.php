<?php
/** @var \frontend\models\FlowForm $model */
/** @var \yii\bootstrap5\ActiveForm $form */

/** @var string $operation */

use kartik\date\DatePicker;

echo $form->field($model, 'name')->textInput(['placeholder' => 'Название потока'])->label(false);
echo $form->field($model, 'created_at')->widget(DatePicker::class, [
    'name' => 'dp_created_at-' . $operation,
    'type' => DatePicker::TYPE_INPUT,
    'pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autoclose' => true
    ],
    'options' => [
        'id' => 'dp_created_at-' . $operation,
        'placeholder' => 'Дата поступления'
    ],
])->label(false);