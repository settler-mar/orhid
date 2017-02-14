<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\PaymentsList;

/**
 * PaymentSearch represents the model behind the search form about `app\modules\payment\models\PaymentsList`.
 */
class PaymentSearch extends PaymentsList
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'order_id', 'status'], 'integer'],
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
        $query = PaymentsList::find();

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
            'user_id' => $this->user_id,
            'order_id' => $this->order_id,
            'status' => $this->status,
        ]);

        return $dataProvider;
    }
}
