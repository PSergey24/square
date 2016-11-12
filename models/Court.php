<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "court".
 *
 * @property integer $id
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

    public function init()
    {
        parent::init();
        $this->creator_id = Yii::$app->user->getId();
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
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(),'targetAttribute' => ['creator_id' => 'id']],
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
            'id' => 'ID',
            'address' => 'Адрес',
            'lat' => 'Широта',
            'lon' => 'Долгота',
            'name' => 'Описание',
            'built_up_area' => 'Площадь ~м.кв.',
            'creator_id' => 'Создатель',
            'district_city_id' => 'Район',
            'type_id' => 'Тип площадки',
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
    public function getCourtPhotos()
    {
        return $this->hasMany(CourtPhoto::className(), ['court_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourtSportTypes()
    {
        return $this->hasMany(CourtSportType::className(), ['court_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGames()
    {
        return $this->hasMany(Game::className(), ['court_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\query\CourtQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\CourtQuery(get_called_class());
    }
}
