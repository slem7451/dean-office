<?php

namespace frontend\controllers;

use frontend\models\CloseStudentForm;
use frontend\models\DecreeTemplate;
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
        $closeStudentForm = new CloseStudentForm();
        $decrees = DecreeTemplate::findAllDecrees();
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

        if ($closeStudentForm->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && Yii::$app->request->post('idCF')) {
            if ($closeStudentForm->validate()) {
                $closeStudentForm->closeFlow(Yii::$app->request->post('idCF'));
            }
        }

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'selectedFlow' => $selectedFlow,
            'closeStudentForm' => $closeStudentForm,
            'decrees' => $decrees
        ]);
    }

    public function actionView($id)
    {
        $selectedGroup = new GroupForm();
        $flow = Flow::findFlow($id);
        $name = Yii::$app->request->get('GN');
        $direction = Yii::$app->request->get('GD');
        $closed_at = Yii::$app->request->get('GC');
        $groups = Group::findFlowsGroups($id, $name, $direction, $closed_at);
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

        if (Yii::$app->request->isPjax && Yii::$app->request->get('idUG')) {
            $id = Yii::$app->request->get('idUG');
            $selectedGroup->loadFromDB(Group::findGroup($id));
        }

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'selectedGroup' => $selectedGroup,
            'directions' => $directions,
            'flows' => $flows,
            'flow' => $flow
        ]);
    }
}