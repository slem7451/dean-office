<?php
/** @var \frontend\models\DirectionForm $model */
/** @var \yii\bootstrap5\ActiveForm $form */
/** @var string $operation */

echo $form->field($model, 'id')->textInput(['placeholder' => 'Номер направления'])->label(false);
echo $form->field($model, 'name')->textInput(['placeholder' => 'Название'])->label(false);
