<?php

/** @var \frontend\models\StudentView $students */
/** @var \yii\data\ActiveDataProvider $dataProvider */

use yii\widgets\ListView;

$this->title = 'Результат поиска';

?>

<div class="student-search-container">
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_result-item',
        'layout' => "{items}\n{pager}",
    ]) ?>
</div>
