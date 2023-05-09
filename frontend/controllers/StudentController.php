<?php

namespace frontend\controllers;

use frontend\models\CertificateToStudent;
use frontend\models\CloseStudentForm;
use frontend\models\DecreeTemplate;
use frontend\models\DecreeToStudent;
use frontend\models\DocumentForm;
use frontend\models\DocumentToStudent;
use frontend\models\Group;
use frontend\models\Student;
use frontend\models\StudentForm;
use frontend\models\StudentHistory;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
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
        $closeStudentForm = new CloseStudentForm();
        $selectedStudent = new StudentForm();
        $decrees = DecreeTemplate::findAllDecrees();
        $groups = Group::findAllNotClosedGroups();
        $students = Student::findStudents();
        $documents = [];
        $dataProvider = new ActiveDataProvider([
            'query' => $students,
            'pagination' => [
                'pageSize' => 20
            ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ]
        ]);

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && !Yii::$app->request->post('idUS')) {
            if ($model->validate()) {
                $model->saveStudent();
            }
        }

        if ($closeStudentForm->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && Yii::$app->request->post('idDS')) {
            if ($closeStudentForm->validate()) {
                $closeStudentForm->closeStudent(Yii::$app->request->post('idDS'));
            }
        }

        if ($closeStudentForm->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && Yii::$app->request->post('idAS')) {
            if ($closeStudentForm->validate()) {
                $closeStudentForm->openStudent(Yii::$app->request->post('idAS'));
            }
        }

        if (Yii::$app->request->isPjax && Yii::$app->request->get('idUS')) {
            $id = Yii::$app->request->get('idUS');
            $documents = $selectedStudent->loadFromDB(Student::findStudent($id));
        }

        if ($selectedStudent->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && Yii::$app->request->post('idUS')) {
            if ($selectedStudent->validate()) {
                $selectedStudent->updateStudent(Yii::$app->request->post('idUS'));
            }
        }

        if (Yii::$app->request->isAjax && Yii::$app->request->post('idDS')) {
            Student::closeStudent(Yii::$app->request->post('idDS'));
        }

        if (Yii::$app->request->isAjax && Yii::$app->request->post('idAS')) {
            Student::openStudent(Yii::$app->request->post('idAS'));
        }

        return $this->render('index', [
            'model' => $model,
            'groups' => $groups,
            'dataProvider' => $dataProvider,
            'selectedStudent' => $selectedStudent,
            'documents' => $documents,
            'closeStudentForm' => $closeStudentForm,
            'decrees' => $decrees
        ]);
    }

    public function actionView($id)
    {
        $student = Student::findStudent($id);
        $studentHistory = StudentHistory::findHistory($student->id);
        $decrees = DecreeToStudent::findDecrees($student->id);
        $certificates = CertificateToStudent::findCertificates($student->id);
        $documents = DocumentToStudent::findDoucments($student->id);
        $model = new DocumentForm();

        $dataProvider = new ActiveDataProvider([
            'query' => $studentHistory,
            'pagination' => [
                'pageSize' => 20
            ],
        ]);

        $decreeDataProvider = new ArrayDataProvider([
            'allModels' => $decrees,
            'pagination' => [
                'pageSize' => 20
            ],
        ]);

        $certificateDataProvider = new ArrayDataProvider([
            'allModels' => $certificates,
            'pagination' => [
                'pageSize' => 20
            ],
        ]);

        $documentDataProvider = new ArrayDataProvider([
            'allModels' => $documents,
            'pagination' => [
                'pageSize' => 20
            ],
        ]);

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && !Yii::$app->request->post('idUD')) {
            if ($model->validate()) {
                $model->saveDocument($id);
            }
        }

        if (Yii::$app->request->isAjax && Yii::$app->request->post('idDD')) {
            DocumentToStudent::deleteDocument($id, Yii::$app->request->post('idDD'));
        }

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && Yii::$app->request->post('idUD')) {
            if ($model->validate()) {
                $model->updateDocument($id, Yii::$app->request->post('idUD'));
            }
        }

        return $this->render('view', [
            'student' => $student,
            'dataProvider' => $dataProvider,
            'decreeDataProvider' => $decreeDataProvider,
            'certificateDataProvider' => $certificateDataProvider,
            'documentDataProvider' => $documentDataProvider,
            'model' => $model
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