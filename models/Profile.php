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

    //default picture name that applied to every new user
    const DEFAULT_PICTURE_NAME = 'default_avatar.png';

    //default folder where are store all profile picture
    const DEFAULT_PICTURE_FOLDER = '/img/uploads/';

    /**
     * @var string
     */
    public $picture = self::DEFAULT_PICTURE_NAME;

    public function uploadPicture()
    {
        if ($this->validate()) {
            $this->picture->saveAs(getcwd() . self::DEFAULT_PICTURE_FOLDER . $this->picture->baseName . '.' . $this->picture->extension);
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
    
    public function getPicture()
    {
        $finder = \Yii::createObject(Finder::className());
        $model = $finder->findProfileById(\Yii::$app->user->identity->getId());
        $picture_filename = $model->getAttribute('picture');
        //check if $picture_filename is url from vk
        $vk_image_path = ['https://pp.vk.me/', 'http://vk.com/images/'];
        foreach ($vk_image_path as $path) {
            if (strpos($picture_filename, $path) === 0) {
                $url_flag = 1;
            }
        }
        if (isset($url_flag))
            return $picture_filename;

        return self::DEFAULT_PICTURE_FOLDER . $picture_filename;

    }
}