<?php

namespace frontend\controllers;

use frontend\models\Decree;
use frontend\models\DecreeForm;
use frontend\models\DecreeTemplate;
use frontend\models\Student;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class DecreeController extends Controller
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
        $model = new DecreeForm();
        $selectedDecree = new DecreeForm();
        $name = Yii::$app->request->get('DN');
        $id = Yii::$app->request->get('DI');
        $created_at = Yii::$app->request->get('DC');
        $templates = DecreeTemplate::findAllDecrees();
        $students = Student::findAllNotClosedStudents();
        $decrees = Decree::findDecrees($name, $id, $created_at);
        $years = Decree::findYears();

        $dataProvider = new ActiveDataProvider([
            'query' => $decrees,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ]
        ]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && !Yii::$app->request->post('idUD')) {
            if ($model->validate()) {
                $model->saveDecree();
            }
        }

        if (Yii::$app->request->isPjax && Yii::$app->request->get('idUD')) {
            $id = Yii::$app->request->get('idUD');
            $selectedDecree->loadFromDB(Decree::findDecree($id));
        }

        if ($selectedDecree->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && Yii::$app->request->post('idUD')) {
            if ($selectedDecree->validate()) {
                $selectedDecree->updateDecree(Yii::$app->request->post('idUD'));
            }
        }

        if (Yii::$app->request->isAjax && Yii::$app->request->post('idDD')) {
            Decree::deleteDecree(Yii::$app->request->post('idDD'));
        }

        return $this->render('index', [
            'model' => $model,
            'templates' => $templates,
            'students' => $students,
            'dataProvider' => $dataProvider,
            'selectedDecree' => $selectedDecree,
            'years' => $years
        ]);
    }
}