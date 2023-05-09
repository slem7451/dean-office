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

    public static function findAllTemplatesWithCertificatesAsArray()
    {
        $templates = [];
        $certificateTemplates = CertificateTemplate::find()->asArray()->all();
        foreach ($certificateTemplates as $certificateTemplate) {
            $templates[] = [
                'type' => TemplateForm::TYPE_CERTIFICATE,
                'template' => $certificateTemplate
            ];
        }
        $decreeTemplates = DecreeTemplate::find()->asArray()->all();
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