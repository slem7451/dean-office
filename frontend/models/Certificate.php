<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * Certificate model
 *
 * @property integer $id
 * @property string $template_id
 * @property date $created_at
 */

class Certificate extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%certificate}}';
    }

    public function getTemplate()
    {
        return $this->hasOne(CertificateTemplate::class, ['id' => 'template_id']);
    }

    public function getToStudents()
    {
        return $this->hasMany(CertificateToStudent::class, ['certificate_id' => 'id']);
    }

    public function getStudents()
    {
        return $this->hasMany(Student::class, ['id' => 'student_id'])->via('toStudents');
    }

    public static function findCertificates()
    {
        return self::find()->with('template', 'students');
    }

    public static function findCertificate($id)
    {
        return self::findOne(['id' => $id]);
    }

    public static function deleteCertificate($id)
    {
        CertificateToStudent::deleteAll(['certificate_id' => $id]);
        $certificate = self::findOne(['id' => $id]);
        return $certificate->delete();
    }

    public static function getStatistic()
    {
        $statistic = [];
        $certificates = self::find()->where(["DATE_PART('year', created_at)" => date('Y')])->all();
        foreach ($certificates as $certificate) {
            $studentCount = CertificateToStudent::find()->where(['certificate_id' => $certificate->id])->count();
            if ($studentCount) {
                $statistic[] = [
                    'name' => $certificate->template->name,
                    'studentCount' => $studentCount
                ];
            }
        }
        return $statistic;
    }
}