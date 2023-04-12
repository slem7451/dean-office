<?php

namespace common\helpers;

class PhoneHelper
{
    public static function formatePhone($phone)
    {
        return str_replace([')', '(', '+', ' ', '-', '_'], '', $phone);
    }
}