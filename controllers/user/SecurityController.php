<?php

namespace app\controllers\user;

use dektrium\user\controllers\SecurityController as BaseSecurityController;
use dektrium\user\models\LoginForm;
use app\models\RegistrationForm;
use dektrium\user\controllers\RegistrationController;

class SecurityController extends BaseSecurityController {

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            $this->goHome();
        }

        /** @var LoginForm $model_login */
        $model_login = \Yii::createObject(LoginForm::className());
        $event_login = $this->getFormEvent($model_login);

        /** @var RegistrationForm $model_register */
        $model_register = \Yii::createObject(RegistrationForm::className());
        $event_register = $this->getFormEvent($model_register);

        $this->performAjaxValidation($model_login);
        $this->trigger(self::EVENT_BEFORE_LOGIN, $event_login);

        $this->performAjaxValidation($model_register);
        $this->trigger(RegistrationController::EVENT_BEFORE_REGISTER, $event_register);


        if ($model_login->load(\Yii::$app->getRequest()->post()) && $model_login->login()) {
            $this->trigger(self::EVENT_AFTER_LOGIN, $event_login);
            return $this->goBack();
        } elseif ($model_register->load(\Yii::$app->request->post()) && $model_register->register()) {
            $this->trigger(RegistrationController::EVENT_AFTER_REGISTER, $event_register);

            return $this->render('/message', [
                'title'  => \Yii::t('user', 'Your account has been created'),
                'module' => $this->module,
            ]);
        }
        
        return $this->render('login', [
            'model_login'  => $model_login,
            'model_register'  => $model_register,
            'module' => $this->module
        ]);
    }
}