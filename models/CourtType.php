<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "court_type".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Court[] $courts
 */
class CourtType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'court_type';
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
    public function getCourts()
    {
        return $this->hasMany(Court::className(), ['type_id' => 'id']);
    }
}
