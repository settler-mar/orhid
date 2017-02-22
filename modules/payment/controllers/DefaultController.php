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
use app\modules\tarificator\models\TarificatorTable;
use app\modules\user\models\User;

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

    $pay=Payments::find()->where(['code'=>$payment->getId(),'status'=>0])->one();
    if(!$pay){
      throw new NotFoundHttpException('Error payment. Contact your administrator.');
    }

    if($payment->getState()=='approved') {
      $pay->status = 1;
      $pay->pay_time = time();
      $pay->save();

      if($pay->type==1){//Если оплатили тариф
        //d($pay);
        $tariff = TarificatorTable::find()->where(['id'=>$pay->pos_id])->one();
        //ddd($tariff);

        $user = User::find()->where(['id'=>$pay->client_id])->one();
        if($tariff->credits==0){
          $user->tariff_unit=$tariff->includeData;
          $user->tariff_end_date=time()+$tariff->timer*60*60*24;
          $user->tariff_id=$tariff->id;
        }else{
          $user->credits+=$tariff->credits;
        }
        $user->save();

        return $this->render('payment_finish', [
          'pay' => $pay,
          'user' => $user,
          'tariff' => $tariff,
        ]);
      }
      return;
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
        $customer->create_time = time();
        $customer->price = $tarificatorTariffs->price;
        $customer->client_id = Yii::$app->user->getId();
        $customer->code = $payment->getId();
        $customer->save();

        $approvalUrl = $payment->getApprovalLink();
        //echo $approvalUrl;
        return $this->redirect($approvalUrl);
      }

      throw new NotFoundHttpException('The requested page does not exist.');
    }

    return $this->render('payment',[
      'tarificator'=>$tarificatorTariffs,
    ]);
  }
}
