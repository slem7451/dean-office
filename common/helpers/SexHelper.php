<?php

namespace common\helpers;

use frontend\models\StudentForm;

class SexHelper
{
    public static function getSex($sex)
    {
        switch ($sex) {
            case StudentForm::MALE:
                return 'М';
            case StudentForm::FEMALE:
                return 'Ж';
            default:
                return '';
        }
    }

    public static function getDetailSex($sex)
    {
        switch ($sex) {
            case StudentForm::MALE:
                return 'Мужчина';
            case StudentForm::FEMALE:
                return 'Женщина';
            default:
                return '';
        }
    }
}