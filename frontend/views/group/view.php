<?php

/** @var \frontend\models\Group $group*/

$this->title = $group->name . ' (' . date('y', strtotime($group->created_at)) . '-' . date('y', strtotime($group->closed_at)) . ')';
?>
<div class="view-group-container">
    <?= $group->name ?>
</div>
