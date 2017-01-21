<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "game".
 *
 * @property integer $id
 * @property string $time
 * @property integer $need_ball
 * @property integer $sport_type_id
 * @property integer $court_id
 * @property integer $creator_id
 *
 * @property Court $court
 * @property User $creator
 * @property SportType $sportType
 */
class Game extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'game';
    }

    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time', 'sport_type_id', 'court_id', 'creator_id'], 'required'],
            [['time'], 'safe'],
            ['need_ball', 'default', 'value' =>  0],
            [['need_ball', 'sport_type_id', 'court_id', 'creator_id'], 'integer'],
            [['court_id'], 'exist', 'skipOnError' => true, 'targetClass' => Court::className(), 'targetAttribute' => ['court_id' => 'id']],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creator_id' => 'id']],
            [['sport_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SportType::className(), 'targetAttribute' => ['sport_type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'time' => 'Time',
            'need_ball' => 'Need Ball',
            'sport_type_id' => 'Sport Type ID',
            'court_id' => 'Court ID',
            'creator_id' => 'Creator ID',
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
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'creator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSportType()
    {
        return $this->hasOne(SportType::className(), ['id' => 'sport_type_id']);
    }
}
