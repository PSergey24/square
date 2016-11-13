<?php

namespace app\controllers\user;

use dektrium\user\controllers\RegistrationController as BaseRegistration;
use dektrium\user\models\RegistrationForm;
 
 class RegistrationController extends BaseRegistration {

     public function actionRegister()
     {
         if (!$this->module->enableRegistration) {
             throw new NotFoundHttpException();
         }

         /** @var RegistrationForm $model */
         $model = \Yii::createObject(RegistrationForm::className());
         $event = $this->getFormEvent($model);

         $this->trigger(self::EVENT_BEFORE_REGISTER, $event);

         $this->performAjaxValidation($model);

         if ($model->load(\Yii::$app->request->post()) && $model->register()) {
             $this->trigger(self::EVENT_AFTER_REGISTER, $event);
             return $this->render('/message', [
                 'title'  => \Yii::t('user', 'Your account has been created'),
                 'module' => $this->module,
             ]);
         }
         \Yii::$app->session->setFlash('danger', "Пользователь с таким именем уже существует");
         return $this->render('@app/views/user/registration/register', [
             'model'  => $model,
             'module' => $this->module,
         ]);
     }
 }