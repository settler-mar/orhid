<?php

namespace app\modules\tariff\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tariff\models\Tariff;

/**
 * TariffSearch represents the model behind the search form about `app\modules\tariff\models\Tariff`.
 */
class TariffSearch extends Tariff
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'code'], 'integer'],
            [['price'], 'number'],
            [['description'], 'safe'],
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
        $query = Tariff::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'price' => $this->price,
            'code' => $this->code,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
