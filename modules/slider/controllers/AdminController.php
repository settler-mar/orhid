<?php

namespace app\modules\slider\controllers;

use Yii;
use app\modules\slider\models\SliderImages;
use app\modules\slider\models\SliderImagesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdminController implements the CRUD actions for SliderImages model.
 */
class AdminController extends Controller
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

        if (Yii::$app->user->isGuest || !Yii::$app->user->can('userManager')) {
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
     * Lists all SliderImages models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SliderImagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SliderImages model.
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
     * Creates a new SliderImages model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SliderImages();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->image_id]);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SliderImages model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $old_address = $this->findModel($id)->address;
        if ($model->load(Yii::$app->request->post())){
            $model->address = $old_address;
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
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SliderImages model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
       //if (file_exists($this->findModel($id)->address)) {
       // echo $this->findModel($id)->address;
        if (file_exists($this->findModel($id)->address)) unlink($this->findModel($id)->address);
       // }
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the SliderImages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SliderImages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SliderImages::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
