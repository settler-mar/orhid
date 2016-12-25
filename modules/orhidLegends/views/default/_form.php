<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\orhidLegends\models\OrhidLegends */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orhid-legends-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image')->fileInput() ?>

    <?= $form->field($model, 'language')->dropDownList(['0' => 'English','1' => 'Русский']) ?>

    <?= $form->field($model, 'state')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
