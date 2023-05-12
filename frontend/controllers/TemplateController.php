<?php

namespace frontend\controllers;

use common\helpers\TemplateHelper;
use frontend\models\CertificateTemplate;
use frontend\models\DecreeTemplate;
use frontend\models\TemplateForm;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;

class TemplateController extends Controller
{
    public function behaviors()
    {
        return [];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new TemplateForm();
        $selectedTemplate = new TemplateForm();
        $templateExample = '';
        $name = Yii::$app->request->get('TN');
        $id = Yii::$app->request->get('TI');
        $type = Yii::$app->request->get('TT');

        $templates = DecreeTemplate::findAllTemplatesWithCertificatesAsArray($name, $id, $type);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $templates,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        if(Yii::$app->request->isAjax && Yii::$app->request->post('template')) {
            $template = str_replace('@', '', Yii::$app->request->post('template'));
            return TemplateHelper::getExample($template);
        }

        if($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && !Yii::$app->request->post('idUT') && !Yii::$app->request->post('typeUT')) {
            if ($model->validate()) {
                $model->saveTemplate();
            }
        }


        if (Yii::$app->request->isPjax && Yii::$app->request->get('idUT') && Yii::$app->request->get('typeUT')) {
            $id = Yii::$app->request->get('idUT');
            $type = Yii::$app->request->get('typeUT');
            switch ($type) {
                case TemplateForm::TYPE_DECREE:
                    $templateDB = DecreeTemplate::findTemplate($id);
                    break;
                case TemplateForm::TYPE_CERTIFICATE:
                    $templateDB = CertificateTemplate::findTemplate($id);
                    break;
                default:
                    $templateDB = null;
                    break;
            }
            $selectedTemplate->loadFromDB($templateDB, $type);
            $templateExample = TemplateHelper::getExample($selectedTemplate->template);
        }

        if ($selectedTemplate->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && Yii::$app->request->post('idUT') && Yii::$app->request->post('typeUT')) {
            $selectedTemplate->type = Yii::$app->request->post('typeUT');
            if ($selectedTemplate->validate()) {
                $selectedTemplate->updateTemplate(Yii::$app->request->post('idUT'));
            }
        }

        if (Yii::$app->request->isAjax && Yii::$app->request->post('idDT') && Yii::$app->request->post('typeDT')) {
            $id = Yii::$app->request->post('idDT');
            $type = Yii::$app->request->post('typeDT');
            switch ($type) {
                case TemplateForm::TYPE_DECREE:
                    return DecreeTemplate::deleteTemplate($id);
                case TemplateForm::TYPE_CERTIFICATE:
                    return CertificateTemplate::deleteTemplate($id);
                default:
                    break;
            }
        }

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'selectedTemplate' => $selectedTemplate,
            'templateExample' => $templateExample
        ]);
    }
}