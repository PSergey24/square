<?php

namespace app\models;

use dektrium\user\models\User as BaseUser;
use yii\db\ActiveRecord;

class User extends BaseUser {

    public function scenarios() {

        $base_scenarious = parent::scenarios();

        return array_merge($base_scenarious, ['custom' => ['username', 'password']]);
    }

    public function rules()
    {
        $parent_rules = parent::rules();
        
        unset($parent_rules['emailRequired']);
        
        return $parent_rules;
    }

    public function afterSave($insert, $changedAttributes)
    {
        //instead of parent::afterSave() call ActiveRecord method afterSave()
        ActiveRecord::afterSave($insert, $changedAttributes);

        if ($insert) {
            if (!isset($this->_profile)) {
                $profile = \Yii::createObject(Profile::className());
                //set picture in _attributes
                $profile->setAttribute('picture', Profile::DEFAULT_PICTURE_NAME);
                $this->setProfile($profile);
            }

            $profile->link('user', $this);

        }
    }
}