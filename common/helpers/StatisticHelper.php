<?php

namespace common\helpers;

class StatisticHelper
{
    public static function getRandomColors($count)
    {
        $colors = [];
        for ($i = 0; $i < $count; ++$i) {
            $colors[] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
        }
        return $colors;
    }
}