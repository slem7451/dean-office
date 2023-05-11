<?php

/** @var yii\web\View $this */

/** @var \frontend\models\Group $groups */

/** @var \frontend\models\Student $students */

/** @var \frontend\models\Flow $flows */

/** @var array $studentStatistic */

/** @var array $groupsStatistic */

/** @var array $decreesStatistic */

/** @var array $certificatesStatistic */

use common\helpers\GroupHelper;
use common\helpers\StatisticHelper;
use dosamigos\chartjs\ChartJs;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\AutoComplete;

$this->title = 'Главная';

?>
    <div class="site-index">
        <h1 class="text-center">Электронный деканат</h1>
        <div class="row">
            <div class="searcher col-3">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        Поиск
                    </div>
                    <div class="card-body">
                        <?= AutoComplete::widget([
                            'id' => 'students-search',
                            'attribute' => 'second_name',
                            'clientOptions' => [
                                'source' => $students,
                                'autoFill' => true,
                            ],
                            'options' => [
                                'class' => 'form-control mg-bottom-15px',
                                'placeholder' => 'Найти студента'
                            ]
                        ]); ?>
                        <?= Html::dropDownList('groups-select', null, ArrayHelper::map($groups, 'id', function ($model) {
                            return GroupHelper::getFullName($model);
                        }), [
                            'prompt' => ['text' => 'Группы', 'options' => ['selected' => true]],
                            'class' => 'form-control mg-bottom-15px',
                        ]) ?>
                        <?= Html::dropDownList('flows-select', null, ArrayHelper::map($flows, 'id', 'name'), [
                            'prompt' => ['text' => 'Потоки', 'options' => ['selected' => true]],
                            'class' => 'form-control',
                        ]) ?>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <div class="card-title">
                            Количество поступивших студентов в <?= date('Y') ?> году
                        </div>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <?= ChartJs::widget([
                            'type' => 'doughnut',
                            'data' => [
                                'labels' => ['Отчисленные', 'Обучаются'],
                                'datasets' => [
                                    [
                                        'data' => [$studentStatistic['closedStudents'], $studentStatistic['openedStudents']],
                                        'label' => '',
                                        'backgroundColor' => [
                                            '#dc3545',
                                            '#28a745'
                                        ],
                                        'borderColor' => [
                                            '#fff',
                                            '#fff'
                                        ],
                                        'borderWidth' => 1,
                                        'hoverBorderColor' => ["#999", "#999"]
                                    ]
                                ]
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <div class="card-title">
                    Тип оплаты студентов <?= date('Y') ?> год
                </div>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?= ChartJs::widget([
                    'type' => 'doughnut',
                    'data' => [
                        'labels' => ['Бюджет', 'Контракт'],
                        'datasets' => [
                            [
                                'data' => [$studentStatistic['budgetStudents'], $studentStatistic['contractStudents']],
                                'label' => '',
                                'backgroundColor' => [
                                    '#007bff',
                                    '#6c757d'
                                ],
                                'borderColor' => [
                                    '#fff',
                                    '#fff'
                                ],
                                'borderWidth' => 1,
                                'hoverBorderColor' => ["#999", "#999"]
                            ]
                        ]
                    ]
                ]) ?>
            </div>
        </div>
        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-title">
                    Группы <?= date('Y') ?> года
                </div>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?= ChartJs::widget([
                    'type' => 'bar',
                    'data' => [
                        'labels' => ArrayHelper::getColumn($groupsStatistic, 'name'),
                        'datasets' => [
                            [
                                'data' => ArrayHelper::getColumn($groupsStatistic, 'studentCount'),
                                'label' => 'Кол-во студентов',
                                'backgroundColor' => array_fill(0, count($groupsStatistic), '#007bff'),
                                'borderColor' => array_fill(0, count($groupsStatistic), '#fff'),
                                'borderWidth' => 1,
                                'hoverBorderColor' => array_fill(0, count($groupsStatistic), "#999"),
                            ]
                        ]
                    ],
                    'clientOptions' => [
                        'legend' => [
                            'display' => false
                        ],
                        'scales' => [
                            'yAxes' => [
                                [
                                    'ticks' => [
                                        'min' => 0,
                                    ]
                                ]
                            ]
                        ],
                    ]
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <div class="card-title">
                            Приказы <?= date('Y') ?> года
                        </div>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <?= ChartJs::widget([
                            'type' => 'pie',
                            'data' => [
                                'labels' => ArrayHelper::getColumn($decreesStatistic, 'name'),
                                'datasets' => [
                                    [
                                        'data' => ArrayHelper::getColumn($decreesStatistic, 'studentCount'),
                                        'label' => '',
                                        'backgroundColor' => StatisticHelper::getRandomColors(count($decreesStatistic)),
                                        'borderWidth' => 1,
                                    ]
                                ]
                            ],
                            'clientOptions' => [
                                    'legend' => [
                                            'display' => false
                                    ]
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <div class="card-title">
                            Справки <?= date('Y') ?> года
                        </div>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <?= ChartJs::widget([
                            'type' => 'pie',
                            'data' => [
                                'labels' => ArrayHelper::getColumn($certificatesStatistic, 'name'),
                                'datasets' => [
                                    [
                                        'data' => ArrayHelper::getColumn($certificatesStatistic, 'studentCount'),
                                        'label' => '',
                                        'backgroundColor' => StatisticHelper::getRandomColors(count($certificatesStatistic)),
                                        'borderWidth' => 1,
                                    ]
                                ]
                            ],
                            'clientOptions' => [
                                'legend' => [
                                    'display' => false
                                ]
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$this->registerJs(<<<JS
    $(document).on('change', '[name="groups-select"]', function () {
        if (this.value) {
            window.location.href = 'http://localhost:20080/index.php?r=group%2Fview&id=' + this.value;
        }
    });
JS
);

$this->registerJs(<<<JS
    $(document).on('change', '[name="flows-select"]', function () {
        if (this.value) {
            window.location.href = 'http://localhost:20080/index.php?r=flow%2Fview&id=' + this.value;
        }
    });
JS
);

$this->registerJs(<<<JS
    $(document).on('autocompleteselect', '#students-search', function(event, ui) {
        var id = ui.item.value;
        ui.item.value = ui.item.label;
        window.location.href = 'http://localhost:20080/index.php?r=student%2Fview&id=' + id;
    });
JS
);

$this->registerJs(<<<JS
    $(document).on('keyup', '#students-search', function(event) {
        if (event.keyCode == 13 && this.value) {
            window.location.href = 'http://localhost:20080/index.php?r=student%2Fsearch&text=' + this.value;
        }
    });
JS
);
