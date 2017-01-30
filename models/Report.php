<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "report".
 *
 * @property integer $id
 * @property integer $court_id
 * @property string $title
 * @property string $description
 * @property integer $user_id
 *
 * @property Court $court
 * @property User $user
 */
class Report extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'report';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['court_id', 'user_id'], 'integer'],
            [['title', 'description'], 'string', 'max' => 255],
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
            'court_id' => 'Court ID',
            'title' => 'Тема жалобы',
            'description' => 'Описание жалобы',
            'user_id' => 'User ID',
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
