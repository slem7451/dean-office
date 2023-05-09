<?php

namespace frontend\controllers;

use frontend\models\AcademicDegree;
use frontend\models\Direction;
use frontend\models\Flow;
use frontend\models\FlowForm;
use frontend\models\Group;
use frontend\models\GroupForm;
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
                'defaultOrder' => ['id' => SORT_DESC],
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

    public function actionView($id)
    {
        $selectedGroup = new GroupForm();
        $flow = Flow::findFlow($id);
        $groups = Group::findFlowsGroups($id);
        $directions = Direction::findAllDirections();
        $academicDegrees = AcademicDegree::findAllAcademicDegrees();
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

        if (Yii::$app->request->isPjax && Yii::$app->request->get('idUG')) {
            $id = Yii::$app->request->get('idUG');
            $selectedGroup->loadFromDB(Group::findGroup($id));
        }

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'selectedGroup' => $selectedGroup,
            'directions' => $directions,
            'academicDegrees' => $academicDegrees,
            'flows' => $flows,
            'flow' => $flow
        ]);
    }
}