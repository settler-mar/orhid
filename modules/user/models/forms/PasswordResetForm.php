<?php

namespace app\modules\user\models\forms;
use Yii;
use app\modules\user\models\User;
use yii\base\Model;

/**
 * Форма восстановления пароля
 * Class PasswordResetForm
 * @package lowbase\user\models\forms
 */
class PasswordResetForm extends Model
{
    public $email;      // Электронная почта
    public $password;   // Пароль
    public $captcha;    // Капча
    /**
     * Правила валидации
     * @return array
     */
    public function rules()
    {
        return [
            [['email', 'password', 'captcha'], 'required'], // Обязательны для заполнения
            [['password'], 'string', 'min' => 4],   // Пароль минимум 4 символа
            ['email', 'email'], // Электронная почта
            ['email', 'exist',
                'targetClass' => 'app\modules\user\models\User',
                'message' => 'User with Email is not registered.'
            ],  // Значение электронной почты должно присутствовать в базе данных
            ['captcha', 'captcha', 'captchaAction' => 'user/user/captcha'], // Проверка капчи
        ];
    }
    /**
     * Наименования полей формы
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'password' => 'New password',
            'email' => 'Email',
            'captcha' => 'Captcha',
        ];
    }
    /**
     * Отправка сообщения с подтверждением
     * нового пароля
     * @return bool
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findByEmail($this->email);
        if ($user) {
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }
            if ($user->save()) {
                // Отправка по шаблону письма "passwordResetToken"
                $view = '@app/modules/user/mail/passwordResetToken';
                if (method_exists(\Yii::$app->controller->module, 'getCustomMailView')) {
                    $view = \Yii::$app->controller->module->getCustomMailView('passwordResetToken', $view);
                }
                return \Yii::$app->mailer->compose($view, [
                    'model' => $user,
                    'password' => $this->password
                ])
                    ->setFrom(\Yii::$app->params['adminEmail'])
                    ->setTo($this->email)
                    ->setSubject('Password Reset Online')
                    ->send();
            }
        }
        return false;
    }
}