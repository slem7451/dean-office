<?php
/** @var \yii\bootstrap5\ActiveForm $form */

/** @var \frontend\models\StudentForm $model */

/** @var \frontend\models\Group $groups */

/** @var string $operation */

/** @var array $documents */

use frontend\models\Student;
use frontend\models\StudentForm;
use kartik\date\DatePicker;
use unclead\multipleinput\MultipleInput;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;

echo $form->field($model, 'first_name')->textInput(['placeholder' => 'Имя'])->label(false);
echo $form->field($model, 'second_name')->textInput(['placeholder' => 'Фамилия'])->label(false);
echo $form->field($model, 'patronymic')->textInput(['placeholder' => 'Отчество (при наличии)'])->label(false);
?>
    <div class="row mg-bottom-15px">
        <?php
        echo $form->field($model, 'group', ['options' => ['class' => 'col-6']])
            ->dropDownList(ArrayHelper::map($groups, 'id', 'name'), [
                'prompt' => ['text' => 'Группа', 'options' => ['disabled' => true, 'selected' => true]],
                'id' => 'groups-for-student-' . $operation
            ])
            ->label(false);
        echo $form->field($model, 'birthdate', ['options' => ['class' => 'col-6']])->widget(DatePicker::class, [
            'name' => 'dp_birthdate-' . $operation,
            'type' => DatePicker::TYPE_INPUT,
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'autoclose' => true
            ],
            'options' => [
                'id' => 'dp_birthdate-' . $operation,
                'placeholder' => 'Дата рождения'
            ],
        ])->label(false);
        ?>
    </div>
    <div class="row mg-bottom-15px">
        <?php
        echo $form->field($model, 'sex', ['options' => ['class' => 'col-6']])
            ->dropDownList([
                StudentForm::MALE => 'Мужчина',
                StudentForm::FEMALE => 'Женщина'
            ],
                [
                    'prompt' => ['text' => 'Пол', 'options' => ['disabled' => true, 'selected' => true]],
                    'id' => 'sex-for-student'
                ])
            ->label(false);
        echo $form->field($model, 'phone', ['options' => ['class' => 'col-6']])->widget(MaskedInput::class, [
            'mask' => '+7(999)999-99-99',
            'options' => [
                'placeholder' => 'Контактный телефон',
                'class' => 'form-control',
                'id' => 'phone-for-student-' . $operation,
            ]
        ])->label(false);
        ?>
    </div>
    <div class="row mg-bottom-15px">
        <?php
        echo $form->field($model, 'payment_type', ['options' => ['class' => 'col-6']])
            ->dropDownList([
                Student::BUDGET_PAYMENT => 'Бюджет',
                Student::CONTRACT_PAYMENT => 'Контракт'
            ],
                [
                    'prompt' => ['text' => 'Оплата', 'options' => ['disabled' => true, 'selected' => true]],
                    'id' => 'payment-for-student'
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
                'placeholder' => 'Дата поступления'
            ],
        ])->label(false);
        ?>
    </div>
    <div class="row">
        <?php
        echo $form->field($model, 'document')->widget(MultipleInput::class, [
            'min' => 1,
            'addButtonOptions' => [
                'label' => 'Добавить'
            ],
            'removeButtonOptions' => [
                'label' => 'Удалить'
            ],
            'columns' => [
                [
                    'name' => 'name-' . $operation,
                    'type' => 'textInput',
                    'options' => [
                        'placeholder' => 'Название документа'
                    ]
                ],
                [
                    'name' => 'scan-' . $operation,
                    'type' => 'fileInput'
                ]
            ]
        ])->label(false);
        ?>
    </div>
<?php if (count($documents)): ?>
    <div class="documents">
        <?php foreach ($documents as $document): ?>
            <img src="<?= 'uploads/' . $document ?>" class="document-img" alt="Document"/>
        <?php endforeach; ?>
    </div>
<?php endif; ?>