<?php

namespace frontend\controllers;

use frontend\models\Certificate;
use frontend\models\CertificateForm;
use frontend\models\CertificateTemplate;
use frontend\models\Student;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class CertificateController extends Controller
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
        $model = new CertificateForm();
        $selectedCertificate = new CertificateForm();
        $templates = CertificateTemplate::findAllCertificates();
        $students = Student::findAllNotClosedStudents();
        $certificates = Certificate::findCertificates();

        $dataProvider = new ActiveDataProvider([
            'query' => $certificates,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ]
        ]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && !Yii::$app->request->post('idUC')) {
            if ($model->validate()) {
                $model->saveCertificate();
            }
        }

        if (Yii::$app->request->isPjax && Yii::$app->request->get('idUC')) {
            $id = Yii::$app->request->get('idUC');
            $selectedCertificate->loadFromDB(Certificate::findCertificate($id));
        }

        if ($selectedCertificate->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && Yii::$app->request->post('idUC')) {
            if ($selectedCertificate->validate()) {
                $selectedCertificate->updateCertificate(Yii::$app->request->post('idUC'));
            }
        }

        if (Yii::$app->request->isAjax && Yii::$app->request->post('idDC')) {
            Certificate::deleteCertificate(Yii::$app->request->post('idDC'));
        }

        return $this->render('index', [
            'model' => $model,
            'templates' => $templates,
            'students' => $students,
            'dataProvider' => $dataProvider,
            'selectedCertificate' => $selectedCertificate
        ]);
    }
}