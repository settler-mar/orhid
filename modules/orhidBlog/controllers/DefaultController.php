<?php

namespace app\modules\orhidBlog\controllers;

use app\modules\user\models\User;
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
        return [];
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
        if (!is_dir('image/uploads/')) mkdir('image/uploads/');
        if (!is_dir('image/uploads_thumbs/')) mkdir('image/uploads_thumbs/');
        $searchModel = new OrhidBlogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
print_r(Yii::$app->user->can('blogCreate'));
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'canCreate' => Yii::$app->user->can('blogCreate'),
            'canUpdate' => Yii::$app->user->can('blogUpdate'),
            'canDelete' => Yii::$app->user->can('blogDelete'),
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
            if (Yii::$app->user->can('blogCreate')) {
                return $this->render('create', ['model' => $model,]);
            }
            else {
                return $this->redirect(['index']);
            }
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
        $old_address =$model->image;
        if ($model->load(Yii::$app->request->post())){   // data from request
            if ($model->image==null) $model->image = $old_address;
            $model->save();
            return $this->redirect(['index']);
        }
        else {                                              // begin update
            if (Yii::$app->user->can('blogUpdate')) {
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
     * Deletes an existing OrhidBlog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->can('blogDelete')) {
            if (file_exists($this->findModel($id)->image)) unlink($this->findModel($id)->image);
            $this->findModel($id)->delete();
        }
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
