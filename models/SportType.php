<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sport_type".
 *
 * @property integer $id
 * @property string $name
 *
 * @property CourtSportType[] $courtSportTypes
 * @property Game[] $games
 */
class SportType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sport_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourtSportTypes()
    {
        return $this->hasMany(CourtSportType::className(), ['sport_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGames()
    {
        return $this->hasMany(Game::className(), ['sport_type_id' => 'id']);
    }
}
