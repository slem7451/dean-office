<?php
/** @var \frontend\models\DirectionForm $model */
/** @var \yii\bootstrap5\ActiveForm $form */
/** @var string $operation */

echo $form->field($model, 'id')->textInput(['placeholder' => 'Шифр направления'])->label(false);
echo $form->field($model, 'full_name')->textInput(['placeholder' => 'Полное название'])->label(false);
echo $form->field($model, 'short_name')->textInput(['placeholder' => 'Короткое название'])->label(false);
echo $form->field($model, 'academic_name')->textInput(['placeholder' => 'Квалификация'])->label(false);
echo $form->field($model, 'profile')->textInput(['placeholder' => 'Направленность (профиль)'])->label(false);
