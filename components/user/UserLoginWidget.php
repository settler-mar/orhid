<?php
/**
 * @package   yii2-user
 * @author    Yuri Shekhovtsov <shekhovtsovy@yandex.ru>
 * @copyright Copyright &copy; Yuri Shekhovtsov, lowbase.ru, 2015 - 2016
 * @version   1.0.0
 */

namespace app\components\user;

use lowbase\user\models\forms\LoginForm;
use yii\base\Widget;

/**
 * Виджет авторизации (входа на сайт)
 * 
 * Class LoginWidget
 * @package app\modules\user\components
 */
class UserLoginWidget extends Widget
{
    public $oauth = false;   // Авторизация с помощью соц. сетей

    public function run()
    {
        $model = new LoginForm();
        return $this->render('loginWidget', [
            'model' => $model,
            'oauth' => $this->oauth
        ]);
    }
}
