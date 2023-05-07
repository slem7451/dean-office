<?php

namespace common\helpers;

class GroupHelper
{
    public static function getFullName($group)
    {
        return $group->name . ' (' . date('Y', strtotime($group->created_at)) . ')';
    }
}