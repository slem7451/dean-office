<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * CertificateTemplate model
 *
 * @property string $id
 * @property string $name
 * @property text $template
 */

class CertificateTemplate extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%certificate_template}}';
    }

    public static function findTemplate($id)
    {
        return self::findOne(['id' => $id]);
    }

    public static function deleteTemplate($id)
    {
        $certificate = Certificate::findAll(['template_id' => $id]);
        if (count($certificate)) {
            return 0;
        }
        $certificate = self::findOne(['id' => $id]);
        return $certificate->delete();
    }

    public static function findAllCertificates()
    {
        return self::find()->all();
    }

    public static function getStatistic($year)
    {
        $statistic = [];
        $certificates = Certificate::find()->where(["DATE_PART('year', created_at)" => $year])->all();
        foreach ($certificates as $certificate) {
            $studentCount = CertificateToStudent::find()->where(['certificate_id' => $certificate->id])->count();
            $statistic[] = [
                'name' => $certificate->template->name,
                'studentCount' => $studentCount
            ];
        }
        return $statistic;
    }
}