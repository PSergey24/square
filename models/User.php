<?php

namespace app\models;

use yii\db\ActiveRecord;

class User extends ActiveRecord {
    
    public $login;
    
    public $password;

    public static function tableName() {
        return 'users';
    }
}