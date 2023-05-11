<?php

namespace frontend\controllers;

use frontend\models\CloseStudentForm;
use frontend\models\DecreeTemplate;
use frontend\models\Direction;
use frontend\models\DirectionForm;
use frontend\models\Flow;
use frontend\models\Group;
use frontend\models\GroupForm;
use frontend\models\Student;
use frontend\models\StudentForm;
use frontend\models\StudentToGroup;
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
        $directions = Direction::findAllDirections();
        $flows = Flow::findAllNotClosedFlows();
        $dataProvider = new ActiveDataProvider([
            'query' => $groups,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ]
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

        if ($selectedGroup->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && Yii::$app->request->post('idUG')) {
            if ($selectedGroup->validate()) {
                $selectedGroup->updateGroup(Yii::$app->request->post('idUG'));
            }
        }

        if (Yii::$app->request->isAjax && Yii::$app->request->post('idCG')) {
            Group::closeGroup(Yii::$app->request->post('idCG'));
        }

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'selectedGroup' => $selectedGroup,
            'directions' => $directions,
            'flows' => $flows
        ]);
    }

    public function actionView($id)
    {
        $group = Group::findGroup($id);
        $closeStudentForm = new CloseStudentForm();
        $decrees = DecreeTemplate::findAllDecrees();
        $students = Student::findStudentsByGroupId($group->id);
        $allStudents = Student::findStudentsNotInGroup($group->id);
        $selectedStudent = new StudentForm();
        $groups = Group::findAllNotClosedGroups();
        $documents = [];

        if (Yii::$app->request->isPjax && Yii::$app->request->get('idUS')) {
            $id = Yii::$app->request->get('idUS');
            $documents = $selectedStudent->loadFromDB(Student::findStudent($id));
        }

        if(Yii::$app->request->isAjax && Yii::$app->request->post('students')) {
            StudentToGroup::addStudents($id, Yii::$app->request->post('students'));
        }

        $studentsDataProvider = new ActiveDataProvider([
            'query' => $students,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ]
        ]);

        return $this->render('view', [
            'group' => $group,
            'groups' => $groups,
            'selectedStudent' => $selectedStudent,
            'studentsDataProvider' => $studentsDataProvider,
            'allStudents' => $allStudents,
            'documents' => $documents,
            'closeStudentForm' => $closeStudentForm,
            'decrees' => $decrees
        ]);
    }

    public function actionDirection()
    {
        $model = new DirectionForm();
        $selectedDirection = new DirectionForm();
        $directions = Direction::findDirections();
        $dataProvider = new ActiveDataProvider([
            'query' => $directions,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ]
        ]);

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && !Yii::$app->request->post('idUD')) {
            if ($model->validate()) {
                $model->saveDirection();
            }
        }

        if (Yii::$app->request->isPjax && Yii::$app->request->get('idUD')) {
            $id = Yii::$app->request->get('idUD');
            $selectedDirection->loadFromDB(Direction::findDirection($id));
        }

        if ($selectedDirection->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && Yii::$app->request->post('idUD')) {
            if ($selectedDirection->validate()) {
                $selectedDirection->updateDirection(Yii::$app->request->post('idUD'));
            }
        }

        if (Yii::$app->request->isAjax && Yii::$app->request->post('idDD')) {
            if (!Direction::deleteDirection(Yii::$app->request->post('idDD'))) {
                return 0;
            }
        }

        return $this->render('direction', [
            'model' => $model,
            'selectedDirection' => $selectedDirection,
            'dataProvider' => $dataProvider
        ]);
    }
}