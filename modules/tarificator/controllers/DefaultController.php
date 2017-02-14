<?php

namespace app\modules\tarificator\controllers;

use Yii;
use app\modules\tarificator\models\TarificatorTable;
use app\modules\tarificator\models\TarificatorTableSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\tariff\models\Tariff;
use yii\data\ActiveDataProvider;
/**
 * DefaultController implements the CRUD actions for TarificatorTable model.
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    function beforeAction($action) {

      if (Yii::$app->user->isGuest) {
        throw new \yii\web\ForbiddenHttpException('You are not allowed to perform this action.');
        return false;
      }

      $this->view->registerJsFile('/js/bootstrap.min.js');
      $this->view->registerJsFile('/js/admin.js');
      $this->view->registerCssFile('/css/bootstrap.min.css');
      $this->view->registerCssFile('/css/admin.css',['depends'=>['app\assets\AppAsset']]);
      return true;
    }

    /**
     * Lists all TarificatorTable models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TarificatorTableSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if (Yii::$app->user->can('tarificatorView')!=1) $this->redirect(['/']);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'canCreate' => Yii::$app->user->can('tarificatorCreate'),
            'canUpdate' => Yii::$app->user->can('tarificatorUpdate'),
        ]);
    }

    /**
     * Displays a single TarificatorTable model.
     * @param integer $id
     * @return mixed
     */

    /**
     * Creates a new TarificatorTable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TarificatorTable();
        //запрос
        $query = Tariff::find();
        $tariffs = new ActiveDataProvider([
            'query' => $query,
        ]);
// вывод данных
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'tariffs' => $tariffs,
            ]);
        }
    }

    /**
     * Updates an existing TarificatorTable model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $query = Tariff::find();
        $tariffs = new ActiveDataProvider([
            'query' => $query,
        ]);
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $model->includeData=json_decode($model->includeData,true);
            return $this->render('update', [
                'model' => $model,
                'tariffs' => $tariffs,
            ]);
        }
    }

    /**
     * Deletes an existing TarificatorTable model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TarificatorTable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TarificatorTable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TarificatorTable::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
