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
//use lowbase\user\models\Country;
use lowbase\user\models\User;
use yii\helpers\Url;

use app\models\LbCountry;
use app\models\LbCity;
use app\components\chosen\Chosen;
use app\components\geoip\Geoip;


$this->title = Yii::t('user', 'Регистрация');
$this->params['breadcrumbs'][] = $this->title;
UserAsset::register($this);
Geoip::widget();
//var_dump(\Yii::$app->session->get('ip_city'));
//var_dump(\Yii::$app->session->get('ip_country'));
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

            <?= Chosen::widget([
                'name' => 'sex',
                'data' => User::getSexArray(),
                'options' => ['placeholder' => $model->getAttributeLabel('Sex')],

            ]); ?>

            <?= Chosen::widget([
                'name' => 'country_id',
                'data' => LbCountry::find()->all(),
                'valueText' => 'name',
                'selected' => \Yii::$app->session->get('ip_country'),
                'options' => ['data-img-src'=>'iso','src_prefix'=>'/img/flags/16/','placeholder' => $model->getAttributeLabel('country_id')],
                'className'=>'my_select_box icon-select',
                'type'=>'object'
            ]); ?>

            <?= Chosen::widget([
                'name' => 'city_id',
                'data' => LbCity::find()
                            ->where(['country_id' => \Yii::$app->session->get('ip_country')])
                            ->orderBy('city')
                            ->all(),
                'valueText' => 'city',
                'valueDopText' => 'state',
                'selected' => \Yii::$app->session->get('ip_city'),
                'options' => ['placeholder' => $model->getAttributeLabel('city_id')],
                'type'=>'object'
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
