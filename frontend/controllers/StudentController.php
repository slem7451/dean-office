<?php

namespace frontend\controllers;

use frontend\models\Group;
use frontend\models\StudentForm;
use frontend\models\StudentView;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class StudentController extends Controller
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
        $model = new StudentForm();
        $groups = Group::findAllGroups();
        $students = StudentView::findStudents();
        $dataProvider = new ActiveDataProvider([
            'query' => $students,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            if ($model->validate()) {
                $model->saveStudent();
            }
        }

        return $this->render('index', [
            'model' => $model,
            'groups' => $groups,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionView($id)
    {
        $student = StudentView::findStudent($id);
        return $this->render('view', [
            'student' => $student
        ]);
    }

    public function actionSearch($text)
    {
        $students = StudentView::findStudentsByText($text);
        return $this->render('search', [
            'students' => $students
        ]);
    }
}