<?php

namespace app\controllers\user;

use dektrium\user\controllers\SettingsController as BaseSettings;
use app\models\Profile;
use yii\web\UploadedFile;

class SettingsController extends BaseSettings {

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
                    if (!unlink($file_to_remove)) {
                        throw new \yii\web\HttpException(500, 'The requested Item could not be found.');
                    }
                }
                $filename = $model->picture->name;
                //rename if file with filename = $model_picture already exist
                for ($i = 1; file_exists(getcwd() . '/img/uploads/' . $filename); $i++){
                    $filename =  $model->picture->getBaseName() . '_' . $i . '.' .$model->picture->getExtension();
                }
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
        
        return $this->render('profile', [
            'model' => $model,
        ]);
    }
}