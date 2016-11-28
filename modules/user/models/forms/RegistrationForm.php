<?php

namespace app\modules\user\models\forms;
use Yii;
use app\modules\user\models\User;


class RegistrationForm extends User
{
    public $password;   // Пароль
    public $captcha;    // Капча

    /**
     * Генерация ключа авторизации, токена подтверждения регистрации
     * и хеширование пароля перед сохранением
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        //var_dump($this);

        //проверяем существовние пользователя
        if($this->findByEmail($this->email)) {
            $this->addError('email', 'Email already exists');
        }

        if($this->findByUsername($this->username)) {
            $this->addError('username', 'Username already exists');
        }

        if ($this->hasErrors()) return false;

        if (parent::beforeSave($insert)) {
            $this->status = 2;
            //var_dump($this);
            $this->password = $this->setPassword($this->password);
            var_dump($this);
            $this->generateAuthKey();
            $this->generateEmailConfirmToken();
            return true;
        }
        return false;
    }
    /**
     * Отправка письма согласно шаблону "confirmEmail"
     * после регистрации
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $view = '@app/modules/user/mail/confirmEmail';
        if (method_exists(\Yii::$app->controller->module, 'getCustomMailView')) {
            $view = \Yii::$app->controller->module->getCustomMailView('confirmEmail', $view);
        }

        Yii::$app->mailer->compose($view, ['model' => $this])
            ->setFrom([Yii::$app->params['adminEmail']])
            ->setTo($this->email)
            ->setSubject('Confirmation of registration at the website')
            ->send();
    }

}