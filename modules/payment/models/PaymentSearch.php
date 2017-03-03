<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\Payments;

/**
 * PaymentSearch represents the model behind the search form of `app\modules\payment\models\Payments`.
 */
class PaymentSearch extends Payments
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'pos_id', 'client_id','method', 'status', 'pay_time', 'create_time'], 'integer'],
            [['price'], 'number'],
            [['code'], 'safe'],
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
        $query = Payments::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        $date_from = strtotime($this['pay_time']);
        $date_to =   strtotime($time_to['pay_time_to']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($date_from!=null) $query->andFilterWhere(['>=', 'pay_time', $date_from]);
        if ($date_to!=null) $query->andFilterWhere(['<=', 'pay_time', $date_to]);
        // grid filtering conditions
        $query->andFilterWhere([
            'status' => $this->status,
            'type' => $this->method,
            'client_id' => $this->client_id,
        ]);

        return $dataProvider;
    }
}
