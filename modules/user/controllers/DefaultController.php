<?php

namespace app\modules\user\controllers;

use Yii;
use app\modules\user\models\User;
use app\modules\user\models\UserSearch;
use app\modules\user\models\forms\RegistrationForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * DefaultController implements the CRUD actions for User model.
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'signup', 'logout', 'confirm', 'reset', 'profile', 'remove', 'online', 'show',
                    'index', 'view', 'update', 'delete', 'rmv', 'multiactive', 'multiblock', 'multidelete'],
                'rules' => [
                    [
                        'actions' => ['login', 'signup', 'confirm', 'reset', 'show'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['login', 'signup', 'show', 'logout', 'profile', 'remove', 'online'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['userManager'],
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['userView'],
                    ],
                    [
                        'actions' => ['update', 'rmv', 'multiactive', 'multiblock'],
                        'allow' => true,
                        'roles' => ['userUpdate'],
                    ],
                    [
                        'actions' => ['delete', 'multidelete'],
                        'allow' => true,
                        'roles' => ['userDelete'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? '445541891789289' : null,
            ],
        ];
    }


    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionRegistration()
    {
        $model = new RegistrationForm();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //обработка поступивших данных
            Yii::$app
                ->getSession()
                ->setFlash(
                    'signup-success',
                    'Link to the registration confirmation sent to the Email.'
                );
            //return $this->redirect(['view', 'id' => 'user']);
        }


        //выводим стндартную форму
        return $this->render('registration.jade', [
            'model' => $model,
        ]);

    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
