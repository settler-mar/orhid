<?php

namespace app\modules\user\controllers;

use Yii;
use app\modules\user\models\Country;
use app\modules\user\models\CountrySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * CountryController implements the CRUD actions for Country model.
 */
class CountryController extends Controller
{
    public function behaviors()
    {
        return [];
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
     * Lists all Country models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CountrySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Deletes an existing Country model.
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
     * Finds the Country model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Country the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Country::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionAjax_multi_set(){
        $allover = array('in_white');
        $res=array('status'=>0,'href'=>'');
        Yii::$app->response->format = Response::FORMAT_JSON;
        $atribute=Yii::$app->request->post('atribute');
        if(!in_array($atribute,$allover)){
            $res['status']=200;
            $res['msg']='Error data';
            return $res;
        }
        $keys = Yii::$app->request->post('keys');
        if ($keys) {
            //$user=User::find()->where('ID IN('.implode(',', $keys).')')->all();
            Country::getDb()
                ->createCommand()
                ->update(
                    Country::tableName(),
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
}
