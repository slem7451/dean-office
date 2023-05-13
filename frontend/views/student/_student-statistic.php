<?php

use common\helpers\AgeHelper;
use common\helpers\DateHelper;
use common\helpers\GroupHelper;
use common\helpers\SexHelper;
use frontend\models\Student;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var \frontend\models\DecreeTemplate $decrees */
/** @var \frontend\models\Decree $years */
/** @var \yii\data\ActiveDataProvider $dataProvider */
?>
<div class="card card-outline card-secondary collapsed-card">
    <div class="card-header">
        <div class="card-title col-10">
            <div class="row">
                <div class="col-2">
                    Студенты с приказами
                </div>
                <?= Html::dropDownList('decree-select-name', null, ArrayHelper::map($decrees, 'name', 'name'), [
                    'class' => 'form-select col-4 mg-right-20-px',
                    'id' => 'decree-select-name',
                    'prompt' => 'Все приказы'
                ]) ?>
                <?= Html::dropDownList('decree-select-year', null, ArrayHelper::map($years, 'decree_year', 'decree_year'), [
                    'class' => 'form-select col-2',
                    'id' => 'decree-select-year',
                    'prompt' => 'Все года'
                ]) ?>
            </div>
        </div>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <?php Pjax::begin(['id' => 'student-stat-decree-pjax']); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-bordered table-hover dataTable dtr-inline'],
            'layout' => "{items}\n{pager}",
            'rowOptions' => function ($model, $key, $index, $grid) {
                return ['class' => 'student-row', 'id' => $model->id . '-' . 'student-id', 'title' => 'Посмотреть подробно'];
            },
            'columns' => [
                [
                    'header' => 'Имя',
                    'content' => function ($model) {
                        return $model->first_name;
                    }
                ],
                [
                    'header' => 'Фамилия',
                    'content' => function ($model) {
                        return $model->second_name;
                    }
                ],
                [
                    'header' => 'Отчество',
                    'content' => function ($model) {
                        return $model->patronymic ?: '';
                    }
                ],
                [
                    'header' => 'Оплата',
                    'content' => function ($model) {
                        return $model->payment == Student::CONTRACT_PAYMENT ? 'Контракт' : 'Бюджет';
                    }
                ],
                [
                    'header' => 'Дата рождения',
                    'content' => function ($model) {
                        return DateHelper::normalizeDate($model->birthdate) . ' (' . AgeHelper::getAge($model->birthdate) . ')';
                    }
                ],
                [
                    'header' => 'Пол',
                    'content' => function ($model) {
                        return SexHelper::getSex($model->sex);
                    }
                ],
                [
                    'header' => 'Телефон',
                    'content' => function ($model) {
                        return $model->phone;
                    }
                ],
                [
                    'header' => 'Поток',
                    'content' => function ($model) {
                        return $model->group->flow->name;
                    }
                ],
                [
                    'header' => 'Группа',
                    'content' => function ($model) {
                        return GroupHelper::getFullName($model->group);
                    }
                ],
                [
                    'header' => 'Дата поступления',
                    'content' => function ($model) {
                        return DateHelper::normalizeDate($model->created_at);
                    }
                ],
                [
                    'header' => 'Статус',
                    'content' => function ($model) {
                        return $model->closed_at ? 'Отчислен' : 'Обучается';
                    }
                ]
            ]
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>

<?php
$this->registerJS(<<<JS
    $(document).on('change', '#decree-select-name', function () {
        $.pjax.reload({container: '#student-stat-decree-pjax', data: {
            SDN: $('#decree-select-name').val(),
            SDY: $('#decree-select-year').val()
            }, replace: false});
    });
JS
);

$this->registerJS(<<<JS
    $(document).on('change', '#decree-select-year', function () {
        $.pjax.reload({container: '#student-stat-decree-pjax', data: {
            SDN: $('#decree-select-name').val(),
            SDY: $('#decree-select-year').val()
            }, replace: false});
    });
JS
);