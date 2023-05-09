<?php
/** @var \yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\CloseStudentForm $model */
/** @var \frontend\models\DecreeTemplate $decrees */
/** @var string $operation */

use frontend\models\CloseStudentForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;

echo $form->field($model, 'decree')->dropDownList(ArrayHelper::map($decrees, 'id', 'name'), [
    'prompt' => $operation == CloseStudentForm::CLOSE_STUDENT ? 'Отчислить с приказом' : 'Зачислить с приказом',
    'id' => 'decree-select-' . $operation
])->label(false);
echo $form->field($model, 'added_at')->widget(DatePicker::class, [
    'name' => 'dp_added_at-' . $operation,
    'type' => DatePicker::TYPE_INPUT,
    'pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autoclose' => true
    ],
    'options' => [
        'id' => 'dp_added_at-' . $operation,
        'placeholder' => 'Дата приказа'
    ],
])->label(false);
echo $form->field($model, 'created_at')->widget(DatePicker::class, [
    'name' => 'dp_created_at-' . $operation,
    'type' => DatePicker::TYPE_INPUT,
    'pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autoclose' => true
    ],
    'options' => [
        'id' => 'dp_created_at-' . $operation,
        'placeholder' => $operation == CloseStudentForm::CLOSE_STUDENT ? 'Дата отчисления' : 'Дата зачисления'
    ],
])->label(false);