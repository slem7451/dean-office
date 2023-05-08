<?php

namespace frontend\models;

use yii\base\Model;

class TemplateForm extends Model
{
    const TYPE_DECREE = 'd';
    const TYPE_CERTIFICATE = 'c';

    public $id;
    public $name;
    public $type;
    public $template;

    public function rules()
    {
        return [
            ['id', 'required', 'message' => 'Обязательно для заполнения'],

            ['name', 'required', 'message' => 'Обязательно для заполнения'],

            ['type', 'required', 'message' => 'Обязательно для заполнения'],
            ['type', 'in', 'range' => [self::TYPE_CERTIFICATE, self::TYPE_DECREE]],

            ['template', 'required', 'message' => 'Обязательно для заполнения'],
            ['template', 'trim']
        ];
    }

    public function saveTemplate()
    {
        switch($this->type) {
            case self::TYPE_CERTIFICATE:
                $certificateTemplate = new CertificateTemplate();
                $certificateTemplate->id = $this->id;
                $certificateTemplate->name = $this->name;
                $certificateTemplate->template = $this->template;
                return $certificateTemplate->save();
            case self::TYPE_DECREE:
                $decreeTemplate = new DecreeTemplate();
                $decreeTemplate->id = $this->id;
                $decreeTemplate->name = $this->name;
                $decreeTemplate->template = $this->template;
                return $decreeTemplate->save();
            default:
                return false;
        }
    }

    public function loadFromDB($template, $type)
    {
        $this->template = $template->template;
        $this->name = $template->name;
        $this->id = $template->id;
        $this->type = $type;
    }

    public function updateTemplate($id)
    {
        $success = true;
        switch ($this->type) {
            case self::TYPE_CERTIFICATE:
                $template = CertificateTemplate::findOne(['id' => $id]);
                if ($this->id != $template->id) {
                    $newTemplate = new CertificateTemplate();
                    $newTemplate->template = $this->template;
                    $newTemplate->name = $this->name;
                    $newTemplate->id = $this->id;
                    $success *= $newTemplate->save();
                    $certificates = Certificate::findAll(['template_id' => $template->id]);
                    foreach ($certificates as $certificate) {
                        $certificate->template_id = $newTemplate->id;
                        $success *= $certificate->save();
                    }
                    $success *= $template->delete();
                } else {
                    $template->name = $this->name;
                    $template->template = $this->template;
                    $success *= $template->save();
                }
                return $success;
            case self::TYPE_DECREE:
                $template = DecreeTemplate::findOne(['id' => $id]);
                if ($this->id != $template->id) {
                    $newTemplate = new DecreeTemplate();
                    $newTemplate->template = $this->template;
                    $newTemplate->name = $this->name;
                    $newTemplate->id = $this->id;
                    $success *= $newTemplate->save();
                    $decrees = Decree::findAll(['template_id' => $template->id]);
                    foreach ($decrees as $decree) {
                        $decree->template_id = $newTemplate->id;
                        $success *= $decree->save();
                    }
                    $success *= $template->delete();
                } else {
                    $template->name = $this->name;
                    $template->template = $this->template;
                    $success *= $template->save();
                }
                return $success;
            default:
                return false;
        }
    }
}