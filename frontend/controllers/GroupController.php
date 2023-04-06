<?php

namespace frontend\controllers;

use frontend\models\Group;
use frontend\models\GroupForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class GroupController extends Controller
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
        $model = new GroupForm();
        $groups = Group::findGroups();
        $dataProvider = new ActiveDataProvider([
            'query' => $groups,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            if ($model->validate()) {
                $model->saveGroup();
            }
        }

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }
}