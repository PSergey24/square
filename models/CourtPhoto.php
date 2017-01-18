<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "court_photo".
 *
 * @property integer $id
 * @property integer $court_id
 * @property string $photo
 * @property integer $avatar
 * @property integer $flag_moderation
 *
 * @property Court $court
 */
class CourtPhoto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'court_photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['court_id', 'photo', 'avatar', 'flag_moderation'], 'required'],
            [['court_id', 'avatar', 'flag_moderation'], 'integer'],
            [['photo'], 'string', 'max' => 255],
            [['court_id'], 'exist', 'skipOnError' => true, 'targetClass' => Court::className(), 'targetAttribute' => ['court_id' => 'id']],
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
            'photo' => 'Photo',
            'avatar' => 'Avatar',
            'flag_moderation' => 'Flag Moderation',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourt()
    {
        return $this->hasOne(Court::className(), ['id' => 'court_id']);
    }
}
