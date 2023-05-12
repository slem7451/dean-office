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
    public $certificate_year;

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

    public static function findCertificates($name = null, $id = null, $created_at = null)
    {
        $certificates = self::find()->joinWith(['template'])->with(['students']);
        if ($name) {
            $certificates->andWhere(['ilike', 'certificate_template.name', $name]);
        }
        if ($id) {
            $certificates->andWhere(['ilike', 'template_id', $id]);
        }
        if ($created_at) {
            $certificates->andWhere(["DATE_PART('year', certificate.created_at)" => $created_at]);
        }
        return $certificates;
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

    public static function findYears()
    {
        return self::find()->select(["DATE_PART('year', created_at) as certificate_year"])->groupBy(["DATE_PART('year', created_at)"])->all();
    }
}