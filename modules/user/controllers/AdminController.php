<?php

namespace app\modules\user\controllers;

use Yii;
use app\modules\user\models\User;
use app\modules\user\models\UserSearch;
use app\modules\user\models\Profile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Response;

class AdminController extends Controller
{
    public function behaviors()
    {
        return [

        ];
    }

    function beforeAction($action) {
        $this->view->registerJsFile('/js/bootstrap.min.js');
        $this->view->registerJsFile('/js/admin.js');
        $this->view->registerCssFile('/css/bootstrap.min.css');
        return true;
    }
    /**
     * Менеджер пользователей (список таблицей)
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //\Yii::app()->clientScript->registerCssFile('/css/yourcss.css');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Просмотр пользователя (карточки)
     * @param $id - ID пользователя
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('@vendor/lowbase/yii2-user/views/user/view', [
            'model' => $this->findModel($id),
        ]);
    }
    /**
     * Редактирование пользователя в режиме
     * администрирования (по аналогии с личным кабинетом)
     * @param $id - ID пользователя
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        /** @var \lowbase\user\models\forms\ProfileForm $model */
        $model = ProfileForm::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('user', 'Запрошенная страница не найдена.'));
        }
        // Преобразуем дату в понятный формат
        if ($model->birthday) {
            $date = new \DateTime($model->birthday);
            $model->birthday = $date->format('d.m.Y');
        }
        if ($model->load(Yii::$app->request->post())) {
            // Загружаем изображение, если оно есть
            $model->photo = UploadedFile::getInstance($model, 'photo');
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Данные профиля обновлены.'));
                return $this->redirect(['update', 'id' => $id]);
            }
        }
        return $this->render('@vendor/lowbase/yii2-user/views/user/update', [
            'model' => $model
        ]);
    }
    /**
     * Удаление пользователя
     * @param $id - ID пользователя
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        if($id==Yii::$app->user->id){
            Yii::$app->getSession()->setFlash('success', 'You can not delete yourself.');
            return $this->redirect(['index']);
        }

        //Удаление записи в таблице профиля
        $profile=Profile::findIdentity($id);
        if($profile)$profile->delete();

        //чистим папку файла
        $path=User::getUserPath($id);
        $files = glob($path."*");
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
        if(file_exists($path))rmdir($path);

        //Удаляем запись в таблице пользователя
        $user=$this->findModel($id);
        if($user)$user->delete();
        Yii::$app->getSession()->setFlash('success', 'User deleted.');
        return $this->redirect(['index']);
    }

    /**
     * Множественная активация пользователей
     * Перевод в статус STATUS_ACTIVE
     * @return bool
     * @throws NotFoundHttpException
     */
    public function actionMultiactive()
    {
        $models = Yii::$app->request->post('keys');
        if ($models) {
            foreach ($models as $id) {
                if ($id != Yii::$app->user->id) {
                    /** @var \lowbase\user\models\User $model */
                    $model = $this->findModel($id);
                    $model->status = User::STATUS_ACTIVE;
                    $model->save();
                }
            }
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Пользователи активированы.'));
        }
        return true;
    }
    /**
     * Множественная блокировка пользователей
     * Перевод в статус STATUS_BLOCKED
     * @return bool
     * @throws NotFoundHttpException
     */
    public function actionMultiblock()
    {
        $models = Yii::$app->request->post('keys');
        if ($models) {
            foreach ($models as $id) {
                if ($id != Yii::$app->user->id) {
                    /** @var \lowbase\user\models\User $model */
                    $model = $this->findModel($id);
                    $model->status = User::STATUS_BLOCKED;
                    $model->save();
                }
            }
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Пользователи заблокированы.'));
        }
        return true;
    }
    /**
     * Множественное удаление пользователей
     * @return bool
     * @throws NotFoundHttpException
     */
    public function actionMultidelete()
    {
        /** @var \lowbase\user\models\User $models */
        $models = Yii::$app->request->post('keys');
        if ($models) {
            foreach ($models as $id) {
                if ($id != Yii::$app->user->id) {
                    /** @var \lowbase\user\models\User $user */
                    $user = $this->findModel($id);
                    $user->removeImage(); // Удаление аватарки с сервера
                    $user->delete();
                }
            }
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Пользователи удалены.'));
        }
        return true;
    }


    public function actionAjax_multi_set(){
        $allover = array('moderate');
        $res=array('status'=>0,'href'=>'');
        Yii::$app->response->format = Response::FORMAT_JSON;
        $atribute=Yii::$app->request->post('atribute');
        if(!in_array($atribute,$allover)){
            $res['status']=200;
            $res['msg']='Error data';
            return false;
        }
        $keys = Yii::$app->request->post('keys');
        if ($keys) {
            //$user=User::find()->where('ID IN('.implode(',', $keys).')')->all();
            User::getDb()
                ->createCommand()
                ->update(
                    User::tableName(),
                    [ $atribute => (int)Yii::$app->request->post('value')],
                    'ID IN('.implode(',', $keys).')'
                )->execute();
            //$user->$atribute=(int)Yii::$app->request->post('value');
            //$user->save();
            $res['msg']='Successful saving data';
            $res['href']='#';
            Yii::$app->getSession()->setFlash('success', 'Successful saving data');
        }
        return $res;
    }


    /**
     * Поиск пользователя по ID
     * @param $id - ID пользователя
     * @return null|static
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('User not found.');
        }
    }
}