<?php

namespace app\models;

use yii\base\Model;

class MapFilters extends Model
{
    public $city;

    public $district_sity;

    public $sport_type = 0;


    public function rules()
    {
        return [
            [['city', 'district_sity'], 'required'],
            [['city', 'district_sity'], 'integer']
        ];
    }
}