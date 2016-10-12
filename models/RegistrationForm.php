<?php

namespace app\models;

use Yii;
use \dektrium\user\models\RegistrationForm as BaseRegistrationForm;

class RegistrationForm extends BaseRegistrationForm {

    public $password_repeat;
//    public $email = '';

    public function rules() {
        // password_repeat rules
        $passw_equals = [
            'passwordRepeat' => ['password_repeat', 'compare', 'compareAttribute' => 'password',
                'message' => 'Пароли должны совпадать'],
            'passwordRepeatRequired' => ['password_repeat', 'required'],
        ];
        $parent_rules = parent::rules();
        unset($parent_rules['emailRequired']);
        return array_merge($parent_rules, $passw_equals);
    }

    public function register()
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var User $user */
        $user = Yii::createObject(User::className());
        $user->setScenario('custom');
        $this->loadAttributes($user);
        if (!$user->register()) {
            return false;
        }

        Yii::$app->session->setFlash(
            'info',
            Yii::t(
                'user',
                'Your account has been created and a message with further instructions has been sent to your email'
            )
        );

        return true;
    }
}