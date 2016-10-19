<?php

namespace app\models;

use dektrium\user\models\Account as BaseAccount;
use yii\authclient\ClientInterface;
use yii\helpers\Json;

class Account extends BaseAccount {

    protected static function fetchUser(BaseAccount $account)
    {
        $user = static::getFinder()->findUserByEmail($account->email);

        if (null !== $user) {
            return $user;
        }

        $user = \Yii::createObject([
            'class'    => User::className(),
            'scenario' => 'connect',
            'username' => $account->username,
            'email'    => $account->email,
        ]);

        if (!$user->validate(['email'])) {
            $account->email = null;
        }

        if (!$user->validate(['username'])) {
            $account->username = null;
        }

        if ($account->getAttribute('provider') == 'vkontakte') {
            $profile = \Yii::createObject(Profile::className());
            $account_data_json = $account->getAttribute('data');
            $account_data = Json::decode($account_data_json);
            $profile->setAttribute('picture', $account_data['photo']);
            $user->setProfile($profile);
        }

        return $user->create() ? $user : false;
    }
}