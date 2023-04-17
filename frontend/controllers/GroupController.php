<?php

namespace frontend\controllers;

use frontend\models\Group;
use frontend\models\GroupForm;
use frontend\models\StudentForm;
use frontend\models\StudentToGroup;
use frontend\models\StudentView;
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
        $selectedGroup = new GroupForm();
        $groups = Group::findGroups();
        $dataProvider = new ActiveDataProvider([
            'query' => $groups,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && !Yii::$app->request->post('idUG')) {
            if ($model->validate()) {
                $model->saveGroup();
            }
        }

        if (Yii::$app->request->isPjax && Yii::$app->request->get('idUG')) {
            $id = Yii::$app->request->get('idUG');
            $selectedGroup->loadFromDB(Group::findGroup($id));
        }

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && Yii::$app->request->post('idUG')) {
            if ($model->validate()) {
                $model->updateGroup(Yii::$app->request->post('idUG'));
            }
        }

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'selectedGroup' => $selectedGroup
        ]);
    }

    public function actionView($id)
    {
        $group = Group::findGroup($id);
        $students = StudentView::findStudentsByGroupId($group->id);
        $allStudents = StudentView::findStudentsNotInGroup($group->id);
        $selectedStudent = new StudentForm();
        $groups = Group::findAllGroups();

        if (Yii::$app->request->isPjax && Yii::$app->request->get('idUS')) {
            $id = Yii::$app->request->get('idUS');
            $selectedStudent->loadFromDB(StudentView::findStudent($id));
        }

        if(Yii::$app->request->isAjax && Yii::$app->request->post('students')) {
            StudentToGroup::addStudents($id, Yii::$app->request->post('students'));
        }

        $studentsDataProvider = new ActiveDataProvider([
            'query' => $students
        ]);

        return $this->render('view', [
            'group' => $group,
            'groups' => $groups,
            'selectedStudent' => $selectedStudent,
            'studentsDataProvider' => $studentsDataProvider,
            'allStudents' => $allStudents
        ]);
    }
}