<?php
/** @var \frontend\models\AcademicDegreeForm $model */
/** @var \yii\bootstrap5\ActiveForm $form */
/** @var string $operation */

echo $form->field($model, 'name')->textInput(['placeholder' => 'Название'])->label(false);