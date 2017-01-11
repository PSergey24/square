<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "court".
 *
 * @property integer $id_court
 * @property string $address
 * @property string $lat
 * @property string $lon
 * @property string $name
 * @property integer $built_up_area
 * @property integer $creator_id
 * @property integer $district_city_id
 * @property integer $type_id
 *
 * @property User $creator
 * @property DistrictCity $districtCity
 * @property CourtType $type
 * @property CourtBookmark[] $courtBookmarks
 * @property CourtLikes[] $courtLikes
 * @property CourtPhoto[] $courtPhotos
 * @property CourtSportType[] $courtSportTypes
 * @property Game[] $games
 */
class Court extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'court';
    }



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address', 'lat', 'lon', 'name', 'creator_id', 'district_city_id', 'type_id'], 'required'],
            [['lat', 'lon'], 'number'],
            [['built_up_area', 'creator_id', 'district_city_id', 'type_id'], 'integer'],
            [['address', 'name'], 'string', 'max' => 255],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creator_id' => 'id']],
            [['district_city_id'], 'exist', 'skipOnError' => true, 'targetClass' => DistrictCity::className(), 'targetAttribute' => ['district_city_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CourtType::className(), 'targetAttribute' => ['type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_court' => 'Id Court',
            'address' => 'Address',
            'lat' => 'Lat',
            'lon' => 'Lon',
            'name' => 'Name',
            'built_up_area' => 'Built Up Area',
            'creator_id' => 'Creator ID',
            'district_city_id' => 'District City ID',
            'type_id' => 'Type ID',
        ];
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
    public function getDistrictCity()
    {
        return $this->hasOne(DistrictCity::className(), ['id' => 'district_city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(CourtType::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourtBookmarks()
    {
        return $this->hasMany(CourtBookmark::className(), ['court_id' => 'id_court']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourtLikes()
    {
        return $this->hasMany(CourtLikes::className(), ['court_id' => 'id_court']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourtPhotos()
    {
        return $this->hasMany(CourtPhoto::className(), ['court_id' => 'id_court']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourtSportTypes()
    {
        return $this->hasMany(CourtSportType::className(), ['court_id' => 'id_court']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGames()
    {
        return $this->hasMany(Game::className(), ['court_id' => 'id_court']);
    }
}
