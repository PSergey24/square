<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Court;

/**
 * CourtSearch represents the model behind the search form about `app\models\Court`.
 */
class CourtSearch extends Court
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'built_up_area', 'creator_id', 'district_city_id', 'type_id'], 'integer'],
            [['address', 'name'], 'safe'],
            [['lat', 'lon'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Court::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'built_up_area' => $this->built_up_area,
            'creator_id' => $this->creator_id,
            'district_city_id' => $this->district_city_id,
            'type_id' => $this->type_id,
        ]);

        $query->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
