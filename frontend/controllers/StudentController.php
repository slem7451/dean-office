<?php

namespace frontend\controllers;

use frontend\models\Group;
use frontend\models\Student;
use frontend\models\StudentForm;
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
        $selectedStudent = new StudentForm();
        $groups = Group::findAllGroups();
        $students = Student::findStudents();
        $dataProvider = new ActiveDataProvider([
            'query' => $students,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && !Yii::$app->request->post('idUS')) {
            if ($model->validate()) {
                $model->saveStudent();
            }
        }

        if (Yii::$app->request->isPjax && Yii::$app->request->get('idUS')) {
            $id = Yii::$app->request->get('idUS');
            $selectedStudent->loadFromDB(Student::findStudent($id));
        }

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && Yii::$app->request->post('idUS')) {
            if ($model->validate()) {
                $model->updateStudent(Yii::$app->request->post('idUS'));
            }
        }

        if (Yii::$app->request->isAjax && Yii::$app->request->post('idDS')) {

        }

        return $this->render('index', [
            'model' => $model,
            'groups' => $groups,
            'dataProvider' => $dataProvider,
            'selectedStudent' => $selectedStudent
        ]);
    }

    public function actionView($id)
    {
        $student = Student::findStudent($id);
        return $this->render('view', [
            'student' => $student
        ]);
    }

    public function actionSearch($text)
    {
        $students = Student::findStudentsByText($text);
        $dataProvider = new ActiveDataProvider([
            'query' => $students,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $this->render('search', [
            'students' => $students,
            'dataProvider' => $dataProvider
        ]);
    }
}