<?php

namespace common\helpers;

class AgeHelper
{
    public static function getAge($birthdate)
    {
        $birthday_timestamp = strtotime($birthdate);
        $age = date('Y') - date('Y', $birthday_timestamp);
        if (date('md', $birthday_timestamp) > date('md')) {
            $age--;
        }
        if ($age % 10 > 1 && $age % 10 < 5 && !($age > 9 && $age < 20)) {
            return $age . ' года';
        } else {
            if ($age % 10 > 4 || $age % 10 == 0 || ($age > 9 && $age < 20)) {
                return $age . ' лет';
            } else {
                return $age . ' год';
            }
        }
    }
}