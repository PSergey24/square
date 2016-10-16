<?php

namespace app\controllers\user;

use dektrium\user\controllers\SecurityController as BaseSecurityController;
use dektrium\user\models\LoginForm;
use app\models\RegistrationForm;
use app\controllers\user\RegistrationController;

class SecurityController extends BaseSecurityController {

    //Login and registration in one action
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            $this->goHome();
        }

        if (\Yii::$app->request->post()) {
            //check which one form we get request from
            $body_params = \Yii::$app->request->getBodyParams();
            //if request from registration form
            if (isset($body_params['register-form'])) {
                //create instance of registration controller and call action register
                $module = \Yii::$app->getModule('user');
                $register_controller = \Yii::createObject(RegistrationController::className(), [
                    $id = 'user', $module
                ]);
                $register_controller->actionRegister();

            }elseif (isset($body_params['login-form'])) { //if request from login form try to login user

                $model_login = \Yii::createObject(LoginForm::className());
                $event_login = $this->getFormEvent($model_login);

                $this->performAjaxValidation($model_login);
                $this->trigger(self::EVENT_BEFORE_LOGIN, $event_login);

                if ($model_login->load(\Yii::$app->getRequest()->post()) && $model_login->login()) {
                    $this->trigger(self::EVENT_AFTER_LOGIN, $event_login);
                    return $this->goBack();
                }
            }
        }   
        //if request not post, just render two forms, based on login and registration models
        /** @var LoginForm $model_login */
        $model_login = \Yii::createObject(LoginForm::className());

        /** @var RegistrationForm $model_register */
        $model_register = \Yii::createObject(RegistrationForm::className());
        
        return $this->render('login', [
            'model_login'  => $model_login,
            'model_register'  => $model_register,
            'module' => $this->module
        ]);
    }
}