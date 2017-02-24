<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\payment\models\Payments;


/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\paymentFilterForm */
/* @var $form ActiveForm */
?>
<div class="paymentFilterForm">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'client_id')->textInput() ?>
    <?= $form->field($model, 'method')->dropDownList( Payments::getTextMethod()) ?>
    <?= $form->field($model, 'status')->dropDownList( Payments::getTextStatus()) ?>
    <?= $form->field($model, 'pay_time')->widget(\yii\jui\DatePicker::classname(), [
        'dateFormat' => 'yyyy-MM-dd',
    ]) ?>
    <?= $form->field($model, 'pay_time_to')->widget(\yii\jui\DatePicker::classname(), [
        'dateFormat' => 'yyyy-MM-dd',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- paymentFilterForm -->
