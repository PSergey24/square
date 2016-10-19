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
}