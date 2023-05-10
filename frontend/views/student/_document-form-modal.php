<?php
/** @var \yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\DocumentForm $model */
/** @var string $operation */

use unclead\multipleinput\MultipleInput;

echo $form->field($model, 'document')->widget(MultipleInput::class, [
    'min' => 1,
    'max' => $operation == OPERATION_UPDATE ? 1 : 9999,
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
