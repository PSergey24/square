<?php

namespace app\models;

use Yii;
use \dektrium\user\models\RegistrationForm as BaseRegistrationForm;

class RegistrationForm extends BaseRegistrationForm {

    public $password_repeat;

    public function rules() {
        $passw_equals = [
            'passwordRepeat' => ['password_repeat', 'compare', 'compareAttribute' => 'password',
                'message' => "Пароли должны совпадать"],
            'passwordRepeatRequired' => ['password_repeat', 'required']
        ];
        return array_merge(parent::rules(), $passw_equals);
    }
}