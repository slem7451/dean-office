<?php

namespace common\helpers;

class DateHelper
{
    public static function normalizeDate($date)
    {
        return date('d.m.Y', strtotime($date));
    }
}