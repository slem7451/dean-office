<?php
/** @var \frontend\models\Student $model */

use common\helpers\GroupHelper;
use common\helpers\SexHelper;
use yii\helpers\Html;

?>

<div class="card">
    <div class="card-header">
        <?= Html::a($model->second_name . ' ' . $model->first_name . ($model->patronymic ? ' ' . $model->patronymic : '') . ' (' . $model->id . ')', ['student/view', 'id' => $model->id]) ?>
    </div>
    <div class="card-body">
        <div><?= SexHelper::getDetailSex($model->sex) ?></div>
        <div><?= GroupHelper::getFullName($model->group) ?></div>
    </div>
</div>
