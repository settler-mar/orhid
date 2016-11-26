<?php
/**
 * @package   yii2-user
 * @author    Yuri Shekhovtsov <shekhovtsovy@yandex.ru>
 * @copyright Copyright &copy; Yuri Shekhovtsov, lowbase.ru, 2015 - 2016
 * @version   1.0.0
 */

use yii\helpers\Html;
use lowbase\user\components\AuthChoice;
use yii\widgets\ActiveForm;
use lowbase\user\UserAsset;

/* @var $model \lowbase\user\models\forms\LoginForm */
/* @var $oauth boolean */

UserAsset::register($this);
?>

    <div class="reg_user_link">
        <a href="/signup">Регистрация</a>
        <a href="/lowbase-user/user/#" data-toggle="modal" data-target="#pass">восстановить пароль</a>
    </div>


<div class="lb-user-module-login-widget">

    <?php $form = ActiveForm::begin([
        'id' => 'contFrm',
        'fieldConfig' => [
            'template' => "{input}\n{hint}\n{error}"
        ],
    ]); ?>

    <div>
        <input name="email" type="text" class="inputBox" placeholder="Email" autocomplete="off" value="">
    </div>
    <div>
        <input name="password" type="password" class="inputBox" placeholder="Пароль" autocomplete="off" value="">
        <button type="submit" id="send" name="login-button" class="add_reg" value="login">Войти</button>
    </div>


    <?php ActiveForm::end(); ?>

    <?php if ($oauth) { ?>

        <p class="hint-block"><?=Yii::t('user', 'Войти с помощью социальных сетей')?>:</p>

        <div class="text-center" style="text-align: center">
            <?= AuthChoice::widget([
                'baseAuthUrl' => ['/user/auth/index'],
            ]) ?>
        </div>

    <?php } ?>

</div>
