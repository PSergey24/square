<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "game_user".
 *
 * @property integer $id
 * @property integer $game_id
 * @property integer $user_id
 */
class GameUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'game_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['game_id', 'user_id'], 'required'],
            [['game_id', 'user_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'game_id' => 'Game ID',
            'user_id' => 'User ID',
        ];
    }
}
