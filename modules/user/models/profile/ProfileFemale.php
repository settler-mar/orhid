<?php

namespace app\modules\user\models\profile;


use Yii;
use app\modules\user\models\Profile;
use yii\base\Model;

/**
 * Форма профиля для женщин(личного кабинета)
 * Class ProfileForm
 * @package app\modules\user\models\forms
 */
class ProfileFamale extends Profile{
    public function rules()
    {
        $rule = parent::rules();
        return $rule;
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

        return $labels;
    }

}