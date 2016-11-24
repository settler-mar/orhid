<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use lowbase\user\components\AuthChoice;
use yii\captcha\Captcha;
use yii\widgets\ActiveForm;
use lowbase\user\UserAsset;

use kartik\widgets\Select2;
use yii\web\JsExpression;
use lowbase\user\components\AuthKeysManager;
use lowbase\user\models\Country;
use lowbase\user\models\User;
use yii\helpers\Url;


$this->title = Yii::t('user', 'Регистрация');
$this->params['breadcrumbs'][] = $this->title;
UserAsset::register($this);
?>

<div class="site-signup row">

        <div class="col-lg-6">

            <h1><?= Html::encode($this->title) ?></h1>

            <?php
            if (Yii::$app->session->hasFlash('signup-success')) {
                echo "<p class='signup-success'>" . Yii::$app->session->getFlash('signup-success') . "</p>";
            } else {
            ?>

            <?php $form = ActiveForm::begin([
                'id' => 'form-signup',
                'fieldConfig' => [
                    'template' => "{input}\n{hint}\n{error}"
                ]
            ]); ?>

            <?= $form->field($model, 'first_name')->textInput([
                'maxlength' => true,
                'placeholder' => $model->getAttributeLabel('first_name')
            ]);?>

            <?= $form->field($model, 'last_name')->textInput([
                'maxlength' => true,
                'placeholder' => $model->getAttributeLabel('last_name')
            ]);?>

            <?= $form->field($model, 'email')->textInput([
                'maxlength' => true,
                'placeholder' => $model->getAttributeLabel('email')
            ]);?>

            <?= $form->field($model, 'password')->passwordInput([
                'maxlength' => true,
                'placeholder' => $model->getAttributeLabel('password')
            ]); ?>

            <?= $form->field($model, 'sex')->dropDownList(User::getSexArray())?>

            <?= $form->field($model, 'country_id')->widget(Select2::classname(), [
                'data' => Country::getAll(),
                'options' => ['placeholder' => $model->getAttributeLabel('country_id')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>

            <?= $form->field($model, 'city_id')->widget(Select2::classname(), [
                'initValueText' => ($model->city_id && $model->city) ? $model->city->city .
                    ' (' . $model->city->state.", ".$model->city->region . ")": '',
                'options' => ['placeholder' => $model->getAttributeLabel('city_id')],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'ajax' => [
                        'url' => Url::to(['city/find']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(city) { return city.text; }'),
                    'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                ],
            ]); ?>

            <?php
            echo $form->field($model, 'captcha')->widget(Captcha::className(), [
                'captchaAction' => '/lowbase-user/default/captcha',
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => $model->getAttributeLabel('captcha')
                ],
                'template' => '<div class="row">
                <div class="col-lg-8">{input}</div>
                <div class="col-lg-4">{image}</div>
                </div>',
            ]);
            ?>

            <div class="form-group">
                <?= Html::submitButton('<i class="glyphicon glyphicon-user"></i> '.Yii::t('user', 'Зарегистрироваться'), [
                    'class' => 'btn btn-lg btn-primary',
                    'name' => 'signup-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>


            <p class="hint-block">
                <?= Yii::t('user', 'Если регистрировались ранее, можете')?> <?=Html::a(Yii::t('user', 'войти на сайт'), ['login'])?>,
                <?= Yii::t('user', 'используя Email или социальные сети')?>.
            </p>

            <?php } ?>

        </div>
        <div class="col-lg-6">
        </div>
</div>
