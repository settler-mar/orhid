<?php

namespace app\modules\user\controllers;

use Yii;
use app\modules\user\models\User;
use app\modules\user\models\UserSearch;
use app\modules\user\models\Profile;
use app\modules\user\models\forms\CreateForm;
use app\modules\user\models\forms\ProfileForm;
use app\modules\user\models\profile\ProfileFemale;
use app\modules\user\models\profile\ProfileMale;
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

    public function actionCreate()
    {
        $model = new CreateForm();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //обработка поступивших данных
            //Перекидываем на страницу редактированиия профиля
            return $this->redirect(['update','id'=>$model->id]);
        }


        //выводим стндартную форму
        return $this->render('@app/modules/user/views/user/registration.jade', [
            'model' => $model,
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
        $model = ProfileForm::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException( 'User is not found');
        }

        if($model->sex==0){
            $profile=ProfileMale::findIdentity($id);
        }else{
            $profile=ProfileFemale::findIdentity($id);
        }


        if (!$profile) {
            // INSERT
            Yii::$app->db->createCommand()->insert('profile', [
                'user_id' => $id
            ])->execute();
            if($model->sex==0){
                $profile=ProfileMale::findIdentity($id);
            }else{
                $profile=ProfileFemale::findIdentity($id);
            }
        }

        //return ;

        $post=Yii::$app->request->post();
        if(isset($post['ProfileForm'])){
            //удаляем картинку до сохранения
            $post['ProfileForm']['photo']=$model->photo;
            //добавляем метку обновления
            $post['ProfileForm']['updated_at'] = time();
        }


        $request = Yii::$app->request;
        if($request->isPost) {
            $to_save = false;

            if(isset($post['moderate-button'])){
                $post['ProfileForm']['moderate']=1;
            }
            if(isset($post['moderate-button-stop'])){
                $post['ProfileForm']['moderate']=0;
            }

            //Готовим профиль к сохранению
            if ($profile->load($post) && $profile->validate()) {
                $to_save = true;
            }

            //Готовим пользователя к сохранению
            if ($model->load($post) && $model->validate()) {
                $to_save = true && $to_save;
            } else {
                $to_save = false;
            }

            //Если номально отвалидировало отправляем на сохранение
            if($to_save && $profile->save() && $model->save()){
                //При успешнос мохранении обновляем страницу и выводим сообщение
                Yii::$app->getSession()->setFlash('success', 'The profile updated.');
                return $this->redirect(['update','id'=>$model->id]);
            }
        }

        // Преобразуем дату в понятный формат
        if ($profile->birthday) {
            $profile->birthday = Date('M  j,Y',$profile->birthday);
        }

        return $this->render('@app/modules/user/views/user/profile', [
            'model' => $model,
            'profile' =>$profile
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

        User::rmdir($id);

        //Удаляем запись в таблице пользователя
        $user=$this->findModel($id);
        if($user)$user->delete();
        Yii::$app->getSession()->setFlash('success', 'User deleted.');
        return $this->redirect(['index']);
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
