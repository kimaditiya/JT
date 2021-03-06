<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MServiceKategori;

/**
 * MServiceKategoriSearch represents the model behind the search form about `app\models\MServiceKategori`.
 */
class MServiceKategoriSearch extends MServiceKategori
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['serviceKategoriId', 'serviceId'], 'integer'],
            [['serviceKategoriJudul', 'serviceKategoriGambarUrl', 'serviceKategoriStatus'], 'safe'],
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
        $query = MServiceKategori::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'serviceKategoriId' => $this->serviceKategoriId,
            'serviceId' => $this->serviceId,
        ]);

        $query->andFilterWhere(['like', 'serviceKategoriJudul', $this->serviceKategoriJudul])
            ->andFilterWhere(['like', 'serviceKategoriGambarUrl', $this->serviceKategoriGambarUrl])
            ->andFilterWhere(['like', 'serviceKategoriStatus', $this->serviceKategoriStatus]);

        return $dataProvider;
    }
}
