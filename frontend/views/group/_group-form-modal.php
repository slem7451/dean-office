<?php
/** @var \yii\bootstrap5\ActiveForm $form */

/** @var \frontend\models\GroupForm $model */

/** @var string $operation */

/** @var \frontend\models\Direction $directions */

/** @var \frontend\models\AcademicDegree $academicDegrees */

/** @var \frontend\models\Flow $flows */

use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;

?>

<?php
echo $form->field($model, 'name')->textInput(['placeholder' => 'Название группы'])->label(false);
?>
<div class="row mg-bottom-15px">
    <?php
    echo $form->field($model, 'flow_id', ['options' => ['class' => 'col-6']])
        ->dropDownList(ArrayHelper::map($flows, 'id', 'name'), [
                'prompt' => ['text' => 'Поток', 'options' => ['disabled' => true, 'selected' => true]]
        ])
        ->label(false);
    echo $form->field($model, 'direction_id', ['options' => ['class' => 'col-6']])
        ->dropDownList(ArrayHelper::map($directions, 'id', 'name'), [
            'prompt' => ['text' => 'Направление', 'options' => ['disabled' => true, 'selected' => true]]
        ])
        ->label(false);
    ?>
</div>
<div class="row">
    <?php
    echo $form->field($model, 'academic_id', ['options' => ['class' => 'col-6']])
        ->dropDownList(ArrayHelper::map($academicDegrees, 'id', 'name'), [
            'prompt' => ['text' => 'Степень', 'options' => ['disabled' => true, 'selected' => true]]
        ])
        ->label(false);
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
    ?>
</div>
