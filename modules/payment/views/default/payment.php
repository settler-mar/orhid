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

<p>
  You select <?=$tarificator->name;?> package
</p>
<p>
  You may pay $<?=$tarificator->price;?>
</p>
<?php $form = ActiveForm::begin(); ?>
  <label>
    <input type="radio" value="0" name="method" checked>PayPal
  </label>
  <br>
  <label>
    <input type="radio" value="1" name="method" disabled>Visa/Mastercard
  </label>
  <hr>
  <input type="submit">
<?php ActiveForm::end(); ?>
