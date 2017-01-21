<?php

namespace app\models;

use yii\base\Model;

class MapFilters extends Model
{
    public $city;

    public $district_sity;

    public $sport_type;


    public function rules()
    {
        return [
            [['city', 'district_sity', 'sport_type'], 'integer']
        ];
    }
}