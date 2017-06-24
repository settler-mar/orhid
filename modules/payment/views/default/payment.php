<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\payment\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Choice of payment method';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="gift_for">
  You select <span><?=$order->name;?></span>
</div>
<div class="gift_for">
  You may pay <span>$<?=number_format($order->price,2,'.',' ');?></span>
</div>

<div class="form_gift_1">
  <?php $form = ActiveForm::begin(); ?>
  <div class="pay_block">
    <label class="paypal_1">
      <input type="radio" value="0" name="method" checked>
    </label>

    <label class="visa_1">
      <input type="radio" value="1" name="method">
    </label>
  </div>

  <input class="send_g" type="submit" value="Send">
  <?php ActiveForm::end(); ?>
</div>
