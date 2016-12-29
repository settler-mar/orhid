<?php

namespace app\modules\orhidLegends\controllers;

use Yii;
use app\modules\orhidLegends\models\OrhidLegends;
use app\modules\orhidLegends\models\OrhidLegendsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * DefaultController implements the CRUD actions for OrhidLegends model.
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
      //  if (Yii::$app->user->isGuest || !Yii::$app->user->can('userManager')) {
      //      throw new \yii\web\ForbiddenHttpException('You are not allowed to perform this action.');
      //      return false;
      //  }
        $this->view->registerJsFile('/js/bootstrap.min.js');
          $this->view->registerCssFile('/css/bootstrap.min.css');
        return true;
    }

    /**
     * Lists all OrhidLegends models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrhidLegendsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'canCreate' => Yii::$app->user->can('createLegend'),
            'canUpdate' => Yii::$app->user->can('updateLegend'),
            'canDelete' => Yii::$app->user->can('deleteLegend'),
        ]);
    }

    /**
     * Displays a single OrhidLegends model.
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
     * Creates a new OrhidLegends model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OrhidLegends();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
                if (Yii::$app->user->can('createLegend')) {
                    return $this->render('create', ['model' => $model,]);
                }
                else {
                    return $this->redirect(['index']);
                }
        }
    }

    /**
     * Updates an existing OrhidLegends model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $old_address = $this->findModel($id)->image;
        if ($model->load(Yii::$app->request->post())){
            $model->image = $old_address;
            if ($model->save()){
                if ($model->image!=null){
                    if (file_exists($old_address)) {
                        unlink($old_address);
                    }
                }
            }
            return $this->redirect(['index']);
        }
        else {                                              // begin update
            if (Yii::$app->user->can('updateLegend')) {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
            else{
                return $this->redirect(['index']);
            }
        }
    }

    /**
     * Deletes an existing OrhidLegends model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->can('deleteLegend')) {
            if (file_exists($this->findModel($id)->image)) unlink($this->findModel($id)->image);
            $this->findModel($id)->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrhidLegends model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrhidLegends the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrhidLegends::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
