<?php

namespace app\modules\staticPages\controllers;

use Yii;
use app\modules\staticPages\models\StaticPages;
use app\modules\staticPages\models\StaticPagesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DefaultController implements the CRUD actions for StaticPages model.
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    function beforeAction($action) {
        //if (Yii::$app->user->isGuest || !Yii::$app->user->can('userManager')) {
        //    throw new \yii\web\ForbiddenHttpException('You are not allowed to perform this action.');
        //    return false;
        //}
        $this->view->registerJsFile('/js/bootstrap.min.js');
        $this->view->registerCssFile('/css/bootstrap.min.css');
        return true;
    }

    /**
     * Lists all StaticPages models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StaticPagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if (!(Yii::$app->user->can('staticPagesAccess'))) {
            $this->redirect(['/']);
        }
        else{
            if (!is_dir('image/uploads/')) mkdir('image/uploads/',0777,true);
            if (!is_dir('image/uploads_thumbs/')) mkdir('image/uploads_thumbs/',0777,true);
            if (Yii::$app->user->can('staticPagesUpdate')) $actionTemplate = '{update}';
            if (Yii::$app->user->can('staticPagesDelete')) $actionTemplate = $actionTemplate.'{delete}';
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'actionTemplate' => $actionTemplate,
                'canCreate' => Yii::$app->user->can('staticPagesCreate'),
            ]);
        }


    }

    /**
     * Displays a single StaticPages model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new StaticPages model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StaticPages();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            if (Yii::$app->user->can('staticPagesCreate')) {
                return $this->render('create', [
                    'model' => $model,
                    'canCreate' => Yii::$app->user->can('staticPagesCreate')
                ]);
            }
            else {
                return $this->redirect(['index']);
            }
        }
    }

    /**
     * Updates an existing StaticPages model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            if (Yii::$app->user->can('staticPagesUpdate')) {
                return $this->render('update', [
                    'model' => $model,
                    'canCreate' => Yii::$app->user->can('staticPagesCreate'),
                ]);
            }
            else{
                return $this->redirect(['index']);
            }
        }
    }

    /**
     * Deletes an existing StaticPages model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->can('staticPagesDelete')) {
            $this->findModel($id)->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the StaticPages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StaticPages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StaticPages::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
