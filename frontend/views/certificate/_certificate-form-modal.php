<?php
/** @var \yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\CertificateForm $model */
/** @var string $operation */
/** @var string $view */
/** @var \frontend\models\CertificateTemplate $templates */
/** @var \frontend\models\Student $students */

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

?>
    <div class="row mg-bottom-15px">
        <?php
        echo $form->field($model, 'template_id', ['options' => ['class' => 'col-6']])->dropDownList(ArrayHelper::map($templates, 'id', 'name'), [
            'prompt' => ['text' => 'Шаблон', 'options' => ['disabled' => true, 'selected' => true]],
            'disabled' => $view
        ])->label(false);
        echo $form->field($model, 'added_at', ['options' => ['class' => 'col-6']])->widget(DatePicker::class, [
            'name' => 'dp_added_at-' . $operation,
            'type' => DatePicker::TYPE_INPUT,
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'autoclose' => true
            ],
            'options' => [
                'id' => 'dp_added_at-' . $operation,
                'placeholder' => 'Дата справки',
                'disabled' => $view
            ],
        ])->label(false);
        ?>
    </div>
    <div class="row mg-bottom-15px">
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
                'placeholder' => 'Дата начала',
                'disabled' => $view
            ],
        ])->label(false);
        echo $form->field($model, 'closed_at', ['options' => ['class' => 'col-6']])->widget(DatePicker::class, [
            'name' => 'dp_closed_at-' . $operation,
            'type' => DatePicker::TYPE_INPUT,
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'autoclose' => true
            ],
            'options' => [
                'id' => 'dp_closed_at-' . $operation,
                'placeholder' => 'Дата конца',
                'disabled' => $view
            ],
        ])->label(false);
        ?>
    </div>
<?php
echo $form->field($model, 'students')->widget(Select2::class, [
    'data' => ArrayHelper::map($students, 'id', function ($model) {
        return $model->first_name . ' ' . $model->second_name . ($model->patronymic ? ' ' . $model->patronymic : '') . ' (' . $model->id . ')';
    }),
    'options' => [
        'placeholder' => 'Выберите студентов',
        'multiple' => true,
        'id' => 'students-select-' . $operation,
        'disabled' => $view
    ],
    'pluginOptions' => [
        'allowClear' => !$view,
        'width' => '100%'
    ],
    'theme' => Select2::THEME_DEFAULT,
    'name' => 'students-select-' . $operation,
])->label(false);