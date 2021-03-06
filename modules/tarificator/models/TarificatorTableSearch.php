<?php

namespace app\modules\tarificator\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tarificator\models\TarificatorTable;

/**
 * TarificatorTableSearch represents the model behind the search form about `app\modules\tarificator\models\TarificatorTable`.
 */
class TarificatorTableSearch extends TarificatorTable
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'timer'], 'integer'],
            [['name', 'description'], 'safe'],
            [['price'], 'number'],
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
        $query = TarificatorTable::find();

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
            'timer' => $this->timer,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'includeData', $this->includeData]);

        return $dataProvider;
    }
}
