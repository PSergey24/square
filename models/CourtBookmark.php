<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "court_bookmark".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $court_id
 *
 * @property Court $court
 * @property User $user
 */
class CourtBookmark extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'court_bookmark';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'court_id'], 'required'],
            [['user_id', 'court_id'], 'integer'],
            [['court_id'], 'exist', 'skipOnError' => true, 'targetClass' => Court::className(), 'targetAttribute' => ['court_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'court_id' => 'Court ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourt()
    {
        return $this->hasOne(Court::className(), ['id' => 'court_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
