<?php

namespace app\controllers\user;

use Yii;
use dektrium\user\controllers\SettingsController as BaseSettings;
use app\models\Profile;
use app\models\User;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use dektrium\user\models\SettingsForm;
use yii\db\Query;

class SettingsController extends BaseSettings {

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'disconnect' => ['post'],
                    'delete'     => ['post']
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['profile', 'rename', 'account', 'networks', 'disconnect', 'delete'],
                        'roles'   => ['@'],
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['confirm'],
                        'roles'   => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    public function actionProfile()
    {
        $model = $this->finder->findProfileById(\Yii::$app->user->identity->getId());

        if ($model == null) {
            $model = \Yii::createObject(Profile::className());
            $model->link('user', \Yii::$app->user->identity);
        }

        $event = $this->getProfileEvent($model);

        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_PROFILE_UPDATE, $event);

        if (\Yii::$app->request->post()) {
            /*copy model object for possibility to update db field value
             it's inpossible to do, with UploadedFile instance in $model object, after UploadedFile:saveAs*/
            $model_old = $model;
            $model->load(\Yii::$app->request->getBodyParams());
            //create object if picture was given
            $model->picture = UploadedFile::getInstance($model, 'picture');

            if ($model->validate()) {
                //delete previous profile picture if exist
                $current_picture = $model->getAttribute('picture');
                if ($current_picture != Profile::DEFAULT_PICTURE_NAME)   {
                    $file_to_remove = getcwd() . Profile::DEFAULT_PICTURE_FOLDER . $current_picture;
                    if (file_exists($file_to_remove))
                        unlink($file_to_remove);
                }
                
                $filename = 'user_img_'.Yii::$app->user->identity->getId(). '.' .$model->picture->getExtension();
                $model->picture->name = $filename;
//                update picture field value in db
                $model_old->setAttribute('picture', $filename);
                $model_old->update();
//                upload img to server and save profile
                if ($model->uploadPicture()) {
                    if ($model->save($runValidation = false)) {
                        \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Your profile has been updated'));
                        $this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
                        return $this->refresh();
                    }
                }
            }
        }
        $model_account = \Yii::createObject(SettingsForm::className());
        return $this->render('profile', [
            'model' => $model,
            'model_account' => $model_account
        ]);
    }

    public function actionRename()
    {
        $model = $this->finder->findProfileById(\Yii::$app->user->identity->getId());

        if ($model == null) {
            $model = \Yii::createObject(Profile::className());
            $model->link('user', \Yii::$app->user->identity);
        }

        $event = $this->getProfileEvent($model);

        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_PROFILE_UPDATE, $event);

        if (\Yii::$app->request->post()) {
            $modelRe = \Yii::createObject(User::className());
            $modelRename = \Yii::$app->request->getBodyParams('username');
                $id = Yii::$app->user->identity->getId();
                
                $query = new Query;
                    $query->select('*')
                    ->from('user')
                    ->where(['username' => $modelRename['User']['username']]);
                $name = $query->all();

                if(count($name)==0)
                {
                    $modelRe = User::findOne($id);
                    $modelRe->username = $modelRename['User']['username'];
                    if($modelRe->update())
                    {
                        if($model->save($runValidation = false))
                        {
                            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Ваше имя изменено'));
                            $this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
                            return $this->refresh();
                        }
                    }else{
                        \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Ваше имя не изменено. Русские символы нельзя'));
                        $this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
                        return $this->refresh();
                    }
                }else{
                    \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Данное имя уже занято. Выберите другое.'));
                    $this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
                    return $this->refresh();
                }
                
        }
        return $this->redirect(['profile']);
    }
}