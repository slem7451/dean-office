<?php
/** @var \frontend\models\StudentView $model */

use common\helpers\GroupHelper;
use common\helpers\SexHelper;
use yii\helpers\Html;

?>

<div class="result-item">
    <div class="result-text">
        <?= Html::a($model->second_name . ' ' . $model->first_name . ($model->patronymic ? ' ' . $model->patronymic : ''), ['student/view', 'id' => $model->id]) ?>
        <div><?= SexHelper::getDetailSex($model->sex) ?></div>
        <div><?= GroupHelper::getFullName($model->group) ?></div>
    </div>
</div>
