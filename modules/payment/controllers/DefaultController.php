<?php

namespace app\modules\payment\controllers;

use app\module\task\models\Task;
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
use app\modules\shop\models\ShopOrder;
use app\modules\shop\models\ShopStore;

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
    if ($role) {
      $filterForm = new PaymentFilterForm();

      if (Yii::$app->request->post()) {
        $filterForm->load(Yii::$app->request->post());
        $query['PaymentSearch'] = $filterForm->toArray();
        $time_to = ['pay_time_to' => $filterForm->pay_time_to];
      }

      $searchModel = new PaymentSearch();
      $dataProvider = $searchModel->search($query, $time_to);

      return $this->render('adminIndex', [
        'searchModel' => $searchModel,
        'filterForm' => $filterForm,
        'dataProvider' => $dataProvider,
      ]);
    } else {
      $user = User::find()->where(['id' => Yii::$app->user->id])->one();
      if ($user->sex == '1') {
        $currentTariff = Payments::find()
          ->andWhere(['client_id' => Yii::$app->user->id])
          ->andWhere(['status' => Payments::STATUS_ACTIVE])
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
        $tariffs = Tariff::find()->select(['code', 'description'])->asArray()->all();

        return $this->render('index', [
          'currentTariff' => $currentTariff,
          'tariffs' => $tariffs,
          'user' => $user,
        ]);
      } else {
        $this->redirect('/');
      }
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

  public function actionFinish()
  {
    $pay = new DoPayment();
    try {
      $payment = $pay->finishPayment();
    } catch (Exception $e) {
      throw new NotFoundHttpException('Error payment. Contact your administrator.');
    }
    if (!$pay) {
      throw new NotFoundHttpException('Error payment. Contact your administrator.');
    }
    $pay = Payments::find()->where(['code' => $payment->getId(), 'status' => 0])->one();
    if (!$pay) {
      throw new NotFoundHttpException('Error payment. Contact your administrator.');
    }

    if ($payment->getState() == 'approved') {
      $pay->method = 1;
      $pay->status = 1;
      $pay->pay_time = time();
      $pay->save();
      //ddd($pay);
      return $this->applay_tariff($pay->type, $pay->pos_id, $pay->client_id);
    }

    throw new NotFoundHttpException('Error payment. Contact your administrator.');
  }

  public function actionShop($id)
  {
    $order = ShopOrder::findOne($id);
    if (!$order || $order->status > 0) {
      throw new NotFoundHttpException('The requested page does not exist.');
    }

    $shop = ShopStore::find()->where(['active' => 1, 'id' => $order->item_id])->asArray()->one();
    $user = User::find()->where(['id' => $order->user_to, 'sex' => 1])->one();
    $name = $shop['title'] . ' for ' . $user->first_name . ' ' . date('Y', $user->profile['birthday']);
    $order = (object)[
      'id' => $order->id,
      'type' => 2,
      'price' => $order->price,
      'name' => $name,
    ];
    return $this->payment_page($order);
  }

  public function actionTariff($id)
  {
    $tarificatorTariffs = TarificatorTable::findOne($id);
    if (!$tarificatorTariffs) {
      throw new NotFoundHttpException('The requested page does not exist.');
    }

    $order = (object)[
      'id' => $tarificatorTariffs->id,
      'type' => 1,
      'price' => $tarificatorTariffs->price,
      'name' => $tarificatorTariffs->name . ' package',
    ];
    return $this->payment_page($order);
  }


  public function payment_page($order)
  {
    $request = Yii::$app->request;
    if ($request->getIsPost()) {
      if ($request->post('method') == 0) { //paypal
        $pay = new DoPayment('paypal');
        //$pay=new DoPayment();

        $pay->addItem(array(
          'name' => $order->name,
          'price' => $order->price,
          'tax' => 0
        ));
        $payment = $pay->make_payment();

        //echo $payment->getId().'<br>';

        $customer = new Payments();
        $customer->type = $order->type;
        $customer->pos_id = $order->id;
        $customer->method = 1;
        $customer->create_time = time();
        $customer->price = $order->price;
        $customer->client_id = Yii::$app->user->getId();
        $customer->code = $payment->getId();
        $customer->save();

        $approvalUrl = $payment->getApprovalLink();
        //echo $approvalUrl;
        return $this->redirect($approvalUrl);
      }

      if ($request->post('method') == 1) {

        if ($request->post('cc_type', null) != null) {
          $pay = new DoPayment('credit_card');

          $pay->addCardData($request->post());
          $pay->addItem(array(
            'name' => $order->name,
            'price' => $order->price,
            'tax' => 0
          ));
          $payment = $pay->make_payment();

          //echo $payment->getId().'<br>';
          if ($payment->getState() == 'approved') {
            $customer = new Payments();
            $customer->type = $order->type;
            $customer->status = 1;
            $customer->pos_id = $order->id;
            $customer->method = 2;
            $customer->create_time = time();
            $customer->pay_time = time();
            $customer->price = $order->price;
            $customer->client_id = Yii::$app->user->getId();
            $customer->code = $payment->getId();
            $customer->save();

            return $this->applay_tariff($order->type, $order->id, Yii::$app->user->getId());
          }

          //ddd($payment);
          return $payment;
        }
        $this->view->registerJsFile("/js/skeuocard.min.js");
        $this->view->registerJsFile("/js/cssua.min.js");
        $this->view->registerCssFile('/css/skeuocard.css');

        return $this->render('card', [
          'tarificator' => $order,
          'post' => $request->post(),
        ]);
      }
      throw new NotFoundHttpException('The requested page does not exist.');
    }

    return $this->render('payment', [
      'order' => $order,
    ]);
  }

  public function applay_tariff($type, $tarif, $user)
  {
    if ($type == 1) { //оплата тарифа
      //d($pay);
      $tariff = TarificatorTable::find()->where(['id' => $tarif])->one();
      //ddd($tariff);

      $user = User::find()->where(['id' => $user])->one();
      if (strlen($tariff->includeData) < 5) {

        //если есть кредиты то добавляем их
        if ($user->credits != 0) {
          $tariff->includeData = json_decode($tariff->includeData, true);
          $tariff->includeData['credits'] = $user->credits;
          $tariff->includeData = json_encode($tariff->includeData);
        }

        if ($user->tariff_end_date < time() + 600) { //если тариф уже закнчен или ему осталось менее 10 минут
          $user->tariff_unit = $tariff->includeData;
          $user->tariff_end_date = time() + $tariff->timer * 60 * 60 * 24;//задаем время начала тарифа
          $user->tariff_id = $tariff->id;
        } else {
          $user->tariff_end_date += $tariff->timer * 60 * 60 * 24; //добавлям к врмени окончания тарифа его период

          $task = new Task();
          $task->user_id = $user->id;
          $task->task_id = 1;
          $task->date_todo = $user->tariff_end_date;
          $task->params = json_encode([
            'tarif_id' => $tariff->id,
            'tariff_unit' => $tariff->includeData
          ]);
          $task->save();
        }


      } else {
        $user->credits += $tariff->credits;
      }
      $user->save();

      $order = (object)[
        'id' => $tariff->id,
        'type' => 1,
        'price' => $tariff->price,
        'name' => $tariff->name . ' package',
      ];

      return $this->render('payment_finish', [
        'user' => $user,
        'order' => $order,
      ]);
    }

    if ($type == 2) {
      $order = ShopOrder::findOne($tarif);
      $order->status = 2;
      $order->save();

      $shop = ShopStore::find()->where(['active' => 1, 'id' => $order->item_id])->asArray()->one();
      $user = User::find()->where(['id' => $order->user_to, 'sex' => 1])->one();
      $name = $shop['title'] . ' for ' . $user->first_name . ' ' . date('Y', $user->profile['birthday']);
      $order = (object)[
        'id' => $order->id,
        'type' => 2,
        'price' => $order->price,
        'name' => $name,
      ];

      return $this->render('payment_finish', [
        'user' => $user,
        'order' => $order,
      ]);
    }
  }
}
