<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * Decree model
 *
 * @property integer $id
 * @property string $template_id
 * @property date $created_at
 */

class Decree extends ActiveRecord
{
    public $decree_year;

    public static function tableName()
    {
        return '{{%decree}}';
    }

    public function getTemplate()
    {
        return $this->hasOne(DecreeTemplate::class, ['id' => 'template_id']);
    }

    public function getToStudents()
    {
        return $this->hasMany(DecreeToStudent::class, ['decree_id' => 'id']);
    }

    public function getStudents()
    {
        return $this->hasMany(Student::class, ['id' => 'student_id'])->via('toStudents');
    }

    public static function findDecrees($name = null, $id = null, $created_at = null)
    {
        $decrees = self::find()->joinWith(['template'])->with(['students']);
        if ($name) {
            $decrees->andWhere(['ilike', 'decree_template.name', $name]);
        }
        if ($id) {
            $decrees->andWhere(['ilike', 'template_id', $id]);
        }
        if ($created_at) {
            $decrees->andWhere(["DATE_PART('year', decree.created_at)" => $created_at]);
        }
        return $decrees;
    }

    public static function findDecree($id)
    {
        return self::findOne(['id' => $id]);
    }

    public static function deleteDecree($id)
    {
        DecreeToStudent::deleteAll(['decree_id' => $id]);
        $decree = self::findOne(['id' => $id]);
        return $decree->delete();
    }

    public static function getStatistic()
    {
        $statistic = [];
        $decrees = self::find()->where(["DATE_PART('year', created_at)" => date('Y')])->all();
        foreach ($decrees as $decree) {
            $studentCount = DecreeToStudent::find()->where(['decree_id' => $decree->id])->count();
            if ($studentCount) {
                $statistic[] = [
                    'name' => $decree->template->name,
                    'studentCount' => $studentCount
                ];
            }
        }
        return $statistic;
    }

    public static function findYears()
    {
        return self::find()->select(["DATE_PART('year', created_at) as decree_year"])->groupBy(["DATE_PART('year', created_at)"])->all();
    }
}