<?php

namespace app\models;

use Yii;
use \dektrium\user\models\RegistrationForm as BaseRegistrationForm;

class RegistrationForm extends BaseRegistrationForm {

    public $password_repeat;
    
    public function attributeLabels() {
       return array_merge(parent::attributeLabels(), ['password_repeat' => Yii::t('user', 'Password repeat')]);
    }

    public function rules() {
        // password_repeat rules
        $passw_equals = [
            'passwordRepeat' => ['password_repeat', 'compare', 'compareAttribute' => 'password',
                'message' => 'Пароли должны совпадать'],
            'passwordRepeatRequired' => ['password_repeat', 'required'],
        ];
        $parent_rules = parent::rules();
        //email field is not required in register
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
            'success',
            Yii::t(
                'user',
                'Account <b>{0}</b> has been created',
                $user->username
            )
        );
        return true;
    }
    
    protected function loadAttributes(\dektrium\user\models\User $user) {

        $user->setAttributes([
            'username' => $this->attributes['username'],
            'password' => $this->attributes['password'],
        ]);

        /** @var Profile $profile */
        $profile = \Yii::createObject(Profile::className());

        $profile->setAttribute('picture', $profile->picture);
        $user->setProfile($profile);
    }
}