<?php

namespace app\models;

use dektrium\user\models\Profile as BaseProfile;
use yii\web\UploadedFile;
use yii\validators\ImageValidator;
use dektrium\user\Finder;


/**
 * This is the model class for table "profile".
 * 
 * @property string $picture
 * 
 */

class Profile extends BaseProfile {
    /**
     * @var UploadedFile
     */
    public $picture;

    public function uploadPicture()
    {
        if ($this->validate()) {
            $this->picture->saveAs('../web/img/uploads/' . $this->picture->baseName . '.' . $this->picture->extension);
            return true;
        } else {
            return false;
        }
    }
    public function rules() 
    {
        $parent_rules = parent::rules();
        unset($parent_rules['gravatarEmailPattern'], $parent_rules['gravatarEmailLength']);
        return array_merge($parent_rules, [
            'picture' => [
                'picture', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg',
                'maxSize' => 1024 * 1024 * 2
        ]]);
    }
    
    public function getPicture() {
        $finder = \Yii::createObject(Finder::className());
        $model = $finder->findProfileById(\Yii::$app->user->identity->getId());
        $picture_filename = $model->getAttribute('picture');
        return '../img/uploads/' . $picture_filename;
    }
}