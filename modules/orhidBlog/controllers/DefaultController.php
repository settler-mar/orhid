<?php

namespace app\modules\orhidBlog\controllers;

use Yii;
use app\modules\orhidBlog\models\OrhidBlog;
use app\modules\orhidBlog\models\OrhidBlogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * DefaultController implements the CRUD actions for OrhidBlog model.
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
            'access' =>[
                'class' => AccessControl::className(),
                'only' => ['create','update','delete'],
                'rules' => [
                    [
                        'actions' =>  ['create','update','delete'],
                        'allow' => true,
                        //'roles' => ['@'],
                    ],
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
     * Lists all OrhidBlog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrhidBlogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrhidBlog model.
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
     * Creates a new OrhidBlog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OrhidBlog();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing OrhidBlog model.
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
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing OrhidBlog model.
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
     * Finds the OrhidBlog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrhidBlog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrhidBlog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
