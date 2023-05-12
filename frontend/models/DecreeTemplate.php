<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * DecreeTemplate model
 *
 * @property string $id
 * @property string $name
 * @property text $template
 */

class DecreeTemplate extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%decree_template}}';
    }

    public static function findAllTemplatesWithCertificatesAsArray($name = null, $id = null, $type = null)
    {
        $templates = [];
        $certificateTemplates = CertificateTemplate::find();
        $decreeTemplates = DecreeTemplate::find();
        if ($name) {
            $decreeTemplates->andWhere(['ilike', 'name', $name]);
            $certificateTemplates->andWhere(['ilike', 'name', $name]);
        }
        if ($id) {
            $decreeTemplates->andWhere(['ilike', 'id', $id]);
            $certificateTemplates->andWhere(['ilike', 'id', $id]);
        }
        $decreeTemplates = $decreeTemplates->asArray()->all();
        $certificateTemplates = $certificateTemplates->asArray()->all();
        if ($type) {
            switch ($type) {
                case TemplateForm::TYPE_CERTIFICATE:
                    foreach ($certificateTemplates as $certificateTemplate) {
                        $templates[] = [
                            'type' => TemplateForm::TYPE_CERTIFICATE,
                            'template' => $certificateTemplate
                        ];
                    }
                    return $templates;
                case TemplateForm::TYPE_DECREE:
                    foreach ($decreeTemplates as $decreeTemplate) {
                        $templates[] = [
                            'type' => TemplateForm::TYPE_DECREE,
                            'template' => $decreeTemplate
                        ];
                    }
                    return $templates;
                default:
                    break;
            }
        }
        foreach ($certificateTemplates as $certificateTemplate) {
            $templates[] = [
                'type' => TemplateForm::TYPE_CERTIFICATE,
                'template' => $certificateTemplate
            ];
        }
        foreach ($decreeTemplates as $decreeTemplate) {
            $templates[] = [
                'type' => TemplateForm::TYPE_DECREE,
                'template' => $decreeTemplate
            ];
        }
        return $templates;
    }

    public static function findTemplate($id)
    {
        return self::findOne(['id' => $id]);
    }

    public static function deleteTemplate($id)
    {
        $decree = Decree::findAll(['template_id' => $id]);
        if (count($decree)) {
            return 0;
        }
        $decree = self::findOne(['id' => $id]);
        return $decree->delete();
    }

    public static function findAllDecrees()
    {
        return self::find()->all();
    }
}