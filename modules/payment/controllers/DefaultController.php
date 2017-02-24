<?php

namespace app\modules\payment\controllers;

use app\modules\tariff\models\Tariff;
use Yii;
use app\modules\payment\models\Payments;
use app\modules\payment\models\PaymentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\payment\models\DoPayment;
use app\modules\payment\models\Card;
use app\modules\tarificator\models\TarificatorTable;
use app\modules\user\models\User;
use app\modules\payment\models\PaymentFilterForm;

use PayPal\Api\Address;
use PayPal\Api\CreditCard;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;
use PayPal\Api\FundingInstrument;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\RedirectUrls;
use PayPal\Rest\ApiContext;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\PayerInfo;

/**
 * DefaultController implements the CRUD actions for Payments model.
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
                ],
            ],
        ];
    }

    /**
     * Lists all Payments models.
     * @return mixed
     */
    public function actionIndex()
    {
        $role = Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['administrator'];
        if($role) {
            $filterForm = new PaymentFilterForm();

            if(Yii::$app->request->post()) {
                $filterForm->load(Yii::$app->request->post());
                $query['PaymentSearch'] = $filterForm->toArray();
                $time_to = ['pay_time_to' => $filterForm->pay_time_to];
             }

            $searchModel = new PaymentSearch();
            $dataProvider = $searchModel->search($query,$time_to);

            return $this->render('adminIndex', [
                'searchModel' => $searchModel,
                'filterForm' => $filterForm,
                'dataProvider' => $dataProvider,
            ]);
        }
        else{
            $currentTariff = Payments::find()
            ->andWhere(['client_id'=> Yii::$app->user->id])
            ->andWhere(['status'=>Payments::STATUS_ACTIVE])
            ->with(['tarificatorTable'])
            ->one();
            if ($currentTariff == null) {
                $currentTariff = Payments::find()
                    ->andWhere(['client_id' => Yii::$app->user->id])
                    ->andWhere(['status' => Payments::TIME_OUT])
                    ->orderBy('pay_time')
                    ->with(['tarificatorTable'])
                    ->one();
            }
            $tariffs = Tariff::find()->select(['code','description'])->asArray()->all();
            $user = User::find()->where(['id'=>Yii::$app->user->id])->one();
           return $this->render('index', [
                'currentTariff' => $currentTariff,
                'tariffs' => $tariffs,
                'user' => $user,
            ]);
        }
    }

    /**
     * Displays a single Payments model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->method = 3;
            $model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $this->findModel($id),
            ]);
        }
    }



    /**
     * Finds the Payments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Payments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Payments::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

  public function actionFinish(){
    $pay=new DoPayment();
    try {
      $payment=$pay->finishPayment();
    } catch (Exception $e) {
      throw new NotFoundHttpException('Error payment. Contact your administrator.');
    }
    if(!$pay){
      throw new NotFoundHttpException('Error payment. Contact your administrator.');
    }
    $pay=Payments::find()->where(['code'=>$payment->getId(),'status'=>0])->one();
    if(!$pay){
      throw new NotFoundHttpException('Error payment. Contact your administrator.');
    }

    if($payment->getState()=='approved') {
      $pay->method = 1;
      $pay->status = 1;
      $pay->pay_time = time();
      $pay->save();

      return $this->applay_tariff($pay->type,$pay->pos_id,$pay->client_id);
    }

    throw new NotFoundHttpException('Error payment. Contact your administrator.');
  }

  public function actionTariff($id){
    $tarificatorTariffs = TarificatorTable::findOne($id);
    if(!$tarificatorTariffs){
      throw new NotFoundHttpException('The requested page does not exist.');
    }

    $request=Yii::$app->request;
    if($request->getIsPost()){

      if($request->post('method')==0){ //paypal
        $pay=new DoPayment('paypal');
        //$pay=new DoPayment();

        $pay->addItem(array(
          'name'=>$tarificatorTariffs->name.' package',
          'price'=>$tarificatorTariffs->price,
          'tax'=>0
        ));
        $payment= $pay->make_payment();

        //echo $payment->getId().'<br>';

        $customer = new Payments();
        $customer->type = 1;
        $customer->pos_id = $id;
        $customer->method = 1;
        $customer->create_time = time();
        $customer->price = $tarificatorTariffs->price;
        $customer->client_id = Yii::$app->user->getId();
        $customer->code = $payment->getId();
        $customer->save();

        $approvalUrl = $payment->getApprovalLink();
        //echo $approvalUrl;
        return $this->redirect($approvalUrl);
      }

      if($request->post('method')==1){

        if($request->post('cc_type')){
          $pay=new DoPayment('credit_card');

          $pay->addCardData($request->post());

          $pay->addItem(array(
            'name'=>$tarificatorTariffs->name.' package',
            'price'=>$tarificatorTariffs->price,
            'tax'=>0
          ));
          $payment= $pay->make_payment();

          //echo $payment->getId().'<br>';
          if($payment->getState()=='approved') {
            $customer = new Payments();
            $customer->type = 1;
            $customer->status = 1;
            $customer->pos_id = $id;
            $customer->method = 2;
            $customer->create_time = time();
            $customer->pay_time = time();
            $customer->price = $tarificatorTariffs->price;
            $customer->client_id = Yii::$app->user->getId();
            $customer->code = $payment->getId();
            $customer->save();

            return $this->applay_tariff(1,$id,Yii::$app->user->getId());
          }

          ddd($payment);
          return $payment;
        }
        $this->view->registerJsFile("/js/skeuocard.min.js");
        $this->view->registerJsFile("/js/cssua.min.js");
        $this->view->registerCssFile('/css/skeuocard.css');

        return $this->render('card',[
          'tarificator'=>$tarificatorTariffs,
          'post'=>$request->post(),
        ]);
      }
      throw new NotFoundHttpException('The requested page does not exist.');
    }

    return $this->render('payment',[
      'tarificator'=>$tarificatorTariffs,
    ]);
  }

  public function applay_tariff($type,$tarif,$user){
    if($type==1) {
      //d($pay);
      $tariff = TarificatorTable::find()->where(['id' => $tarif])->one();
      //ddd($tariff);

      $user = User::find()->where(['id' => $user])->one();
      if ($tariff->credits == 0) {
        $user->tariff_unit = $tariff->includeData;
        $user->tariff_end_date = time() + $tariff->timer * 60 * 60 * 24;
        $user->tariff_id = $tariff->id;
      } else {
        $user->credits += $tariff->credits;
      }
      $user->save();

      return $this->render('payment_finish', [
        'user' => $user,
        'tariff' => $tariff,
      ]);
    }
  }
}
