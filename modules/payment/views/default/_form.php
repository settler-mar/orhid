<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\payment\models\Payments;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\PaymentsList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payments-list-form">

    <?php $form = ActiveForm::begin(); ?>
<p>Client ID  :</p> <p><?= $model->client_id?></p>
<p>Price      : </p><p><?= $model->price ?></p>




    <?= $form->field($model, 'status')->dropDownList(Payments::getTextStatus()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
