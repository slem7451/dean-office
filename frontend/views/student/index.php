<?php

use common\helpers\SexHelper;
use frontend\models\StudentForm;
use kartik\date\DatePicker;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Modal;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;
use common\helpers\AgeHelper;

/** @var \frontend\models\StudentForm $model */
/** @var \frontend\models\Group $groups */
/** @var \yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Студенты';
?>

    <div class="student-container">
        <?php
        $form = ActiveForm::begin(['id' => 'student-form']);
        Modal::begin([
            'id' => 'student-modal',
            'toggleButton' => ['label' => 'Создать ученика', 'class' => 'btn btn-primary mg-bottom-15px'],
            'title' => 'Создание ученика',
            'footer' => Html::submitButton('Создать', ['class' => 'btn btn-success save-student-btn']) . Html::button('Закрыть', [
                    'class' => 'btn btn-danger',
                    'data-bs-dismiss' => 'modal'
                ])
        ]);
        echo $form->field($model, 'first_name')->textInput(['placeholder' => 'Имя'])->label(false);
        echo $form->field($model, 'second_name')->textInput(['placeholder' => 'Фамилия'])->label(false);
        echo $form->field($model, 'patronymic')->textInput(['placeholder' => 'Отчество (при наличии)'])->label(false);
        ?>
        <div class="row mg-bottom-15px">
            <?php
            echo $form->field($model, 'group', ['options' => ['class' => 'col-6']])
                ->dropDownList(ArrayHelper::map($groups, 'id', 'name'), [
                    'prompt' => ['text' => 'Группа', 'options' => ['disabled' => true, 'selected' => true]],
                    'id' => 'groups-for-student'
                ])
                ->label(false);
            echo $form->field($model, 'birthdate', ['options' => ['class' => 'col-6']])->widget(DatePicker::class, [
                'name' => 'dp_birthdate',
                'type' => DatePicker::TYPE_INPUT,
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true
                ],
                'options' => [
                    'placeholder' => 'Дата рождения'
                ],
            ])->label(false);
            ?>
        </div>
        <div class="row">
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
                'id' => 'phone-for-student',
                'mask' => '+7(999)999-99-99',
                'options' => [
                    'placeholder' => 'Контактный телефон',
                    'class' => 'form-control',
                ]
            ])->label(false);
            ?>
        </div>
        <?php
        Modal::end();
        ActiveForm::end();
        Pjax::begin(['id' => 'student-table-pjax']);
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{pager}",
            'columns' => [
                [
                    'header' => 'Имя',
                    'content' => function ($model) {
                        return '<p>' . $model->first_name . '</p>';
                    }
                ],
                [
                    'header' => 'Фамилия',
                    'content' => function ($model) {
                        return '<p>' . $model->second_name . '</p>';
                    }
                ],
                [
                    'header' => 'Отчество',
                    'content' => function ($model) {
                        return '<p>' . $model->patronymic ? $model->patronymic : '</p>';
                    }
                ],
                [
                    'header' => 'Пол',
                    'content' => function ($model) {
                        return '<p>' . SexHelper::getSex($model->sex) . '</p>';
                    }
                ],
                [
                    'header' => 'Телефон',
                    'content' => function ($model) {
                        return '<p>' . $model->phone . '</p>';
                    }
                ],
                [
                    'header' => 'Группа',
                    'content' => function ($model) {
                        return '<p>' . $model->group->name . ' (' . date('Y', strtotime($model->group->created_at)) . ' - ' . date('Y', strtotime($model->group->closed_at)) . ')' . '</p>';
                    }
                ],
                [
                    'header' => 'Дата рождения',
                    'content' => function ($model) {
                        return '<p>' . date('d.m.Y', strtotime($model->birthdate)) . ' (' . AgeHelper::getAge($model->birthdate) . ')' . '</p>';
                    }
                ]
            ]
        ]);
        Pjax::end();
        ?>
    </div>

<?php
$this->registerJS(<<<JS
    $('#student-modal').on('hidden.bs.modal', function () {
        $('#student-form')[0].reset();
    })
JS
);

$this->registerJS(<<<JS
    $('#student-form').on('beforeSubmit', function() {
        var data = $(this).serialize();
        $.ajax({
            url: '/index.php?r=student%2Findex',
            type: 'POST',
            data: data,
            success: function(res) {
                $.pjax.reload({container: '#student-table-pjax', replace: false});
                $('#student-modal').modal('hide');
            }
        });
        return false;
    })
JS
);