<?php

namespace common\helpers;

use frontend\models\Student;
use wapmorgan\yii2inflection\Inflector;
use Yii;

class TemplateHelper
{
    const BUDGET_PAY = 'за счет бюджетных ассигнований федерального бюджета';
    const CONTRACT_PAY = 'по договору об оказании платных образовательных услуг';

    public static function getExample($template)
    {
        $template = str_replace('$STUDENT_I$', Yii::$app->inflection->inflectName('Иванов Иван Иванович', Inflector::NOMINATIVE), $template);
        $template = str_replace('$STUDENT_R$', Yii::$app->inflection->inflectName('Иванов Иван Иванович', Inflector::GENITIVE), $template);
        $template = str_replace('$STUDENT_D$', Yii::$app->inflection->inflectName('Иванов Иван Иванович', Inflector::DATIVE), $template);
        $template = str_replace('$STUDENT_V$', Yii::$app->inflection->inflectName('Иванов Иван Иванович', Inflector::ACCUSATIVE), $template);
        $template = str_replace('$STUDENT_T$', Yii::$app->inflection->inflectName('Иванов Иван Иванович', Inflector::ABLATIVE), $template);
        $template = str_replace('$STUDENT_P$', Yii::$app->inflection->inflectName('Иванов Иван Иванович', Inflector::PREPOSITIONAL), $template);
        $template = str_replace('$GROUP$', 'МТ-401', $template);
        $template = str_replace('$DIR$', '02.03.02', $template);
        $template = str_replace('$DIR_N$', 'Фундаментальная информатика и информационные технологии', $template);
        $template = str_replace('$PAY$', self::BUDGET_PAY, $template);
        $template = str_replace('$DATE_B$', date('d.m.Y') . ' г.', $template);
        $template = str_replace('$DATE_E$', date('d.m.Y', strtotime('+ 1 month')) . ' г.', $template);
        return $template;
    }

    public static function parseTemplate($template, $student, $toStudent)
    {
        $template = str_replace('$STUDENT_I$', Yii::$app->inflection->inflectName($student->second_name . ' ' . $student->first_name . ($student->patronymic ? ' ' . $student->patronymic : ''), Inflector::NOMINATIVE), $template);
        $template = str_replace('$STUDENT_R$', Yii::$app->inflection->inflectName($student->second_name . ' ' . $student->first_name . ($student->patronymic ? ' ' . $student->patronymic : ''), Inflector::GENITIVE), $template);
        $template = str_replace('$STUDENT_D$', Yii::$app->inflection->inflectName($student->second_name . ' ' . $student->first_name . ($student->patronymic ? ' ' . $student->patronymic : ''), Inflector::DATIVE), $template);
        $template = str_replace('$STUDENT_V$', Yii::$app->inflection->inflectName($student->second_name . ' ' . $student->first_name . ($student->patronymic ? ' ' . $student->patronymic : ''), Inflector::ACCUSATIVE), $template);
        $template = str_replace('$STUDENT_T$', Yii::$app->inflection->inflectName($student->second_name . ' ' . $student->first_name . ($student->patronymic ? ' ' . $student->patronymic : ''), Inflector::ABLATIVE), $template);
        $template = str_replace('$STUDENT_P$', Yii::$app->inflection->inflectName($student->second_name . ' ' . $student->first_name . ($student->patronymic ? ' ' . $student->patronymic : ''), Inflector::PREPOSITIONAL), $template);
        $template = str_replace('$GROUP$', $student->group->name, $template);
        $template = str_replace('$DIR$', $student->group->direction->id, $template);
        $template = str_replace('$DIR_N$', $student->group->direction->name, $template);
        $template = str_replace('$PAY$', $student->payment == Student::BUDGET_PAYMENT ? self::BUDGET_PAY : self::CONTRACT_PAY, $template);
        $template = str_replace('$DATE_B$', DateHelper::normalizeDate($toStudent->created_at) . ' г.', $template);
        if ($toStudent->closed_at) {
            $template = str_replace('$DATE_E$', DateHelper::normalizeDate($toStudent->closed_at) . ' г.', $template);
        }
        return $template;
    }
}