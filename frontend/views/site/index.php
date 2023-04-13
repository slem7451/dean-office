<?php

/** @var yii\web\View $this */

/** @var \frontend\models\Group $groups */

/** @var \frontend\models\StudentView $students */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\AutoComplete;

$this->title = 'Главная';
?>
    <div class="site-index">
        <h1 class="text-center">Начальная страница</h1>
        <div class="searcher col-3">
            <?= AutoComplete::widget([
                'id' => 'students-search',
                'attribute' => 'second_name',
                'clientOptions' => [
                    'source' => $students,
                    'autoFill' => true,
                ],
                'options'=>[
                    'class'=>'form-control mg-bottom-15px',
                    'placeholder' => 'Найти студента'
                ]
            ]); ?>
            <?= Html::dropDownList('groups-select', null, ArrayHelper::map($groups, 'id', 'name'), [
                'prompt' => ['text' => 'Группы', 'options' => ['selected' => true]],
                'class' => 'form-select',
            ]) ?>
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
