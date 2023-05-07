<?php

namespace frontend\controllers;

use frontend\models\Flow;
use frontend\models\FlowForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class FlowController extends Controller
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
        $model = new FlowForm();
        $selectedFlow = new FlowForm();
        $flows = Flow::findFlows();

        $dataProvider = new ActiveDataProvider([
            'query' => $flows,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => ['name' => SORT_ASC],
            ]
        ]);

        if (Yii::$app->request->isPjax && Yii::$app->request->get('idUF')) {
            $id = Yii::$app->request->get('idUF');
            $selectedFlow->loadFromDB(Flow::findFlow($id));
        }

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && !Yii::$app->request->post('idUF')) {
            if ($model->validate()) {
                $model->saveFlow();
            }
        }

        if ($selectedFlow->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && Yii::$app->request->post('idUF')) {
            if ($selectedFlow->validate()) {
                $selectedFlow->updateFlow(Yii::$app->request->post('idUF'));
            }
        }

        if (Yii::$app->request->isAjax && Yii::$app->request->post('idCF')) {
            Flow::closeFlow(Yii::$app->request->post('idCF'));
        }

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'selectedFlow' => $selectedFlow
        ]);
    }
}