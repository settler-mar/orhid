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
  public $pay_time_from, $pay_time_to,$client;

  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      [['id', 'type', 'pos_id', 'client_id', 'method', 'status', 'pay_time', 'create_time'], 'integer'],
      [['pay_time_from', 'pay_time_to','client'], 'string'],
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
      'sort' => [
        'defaultOrder' => [
          'id' => SORT_DESC,
        ]
      ],
      'pagination' => [
        'pageSize' => 100,
      ],
    ]);
    $this->load($params);

    if (isset($this->pay_time_to) AND strlen($this->pay_time_to>7)) {
      $date_to = strtotime($this->pay_time_to.' 23:59:59');
    }
    if (isset($this->pay_time_from) AND strlen($this->pay_time_to>7)) {
      $date_from = strtotime($this->pay_time_from.' 00:00:00');
    }

    if (!$this->validate()) {
      // uncomment the following line if you do not want to return any records when validation fails
      // $query->where('0=1');
      return $dataProvider;
    }

    if (isset($date_from)) $query->andFilterWhere(['>=', 'create_time', $date_from]);
    if (isset($date_to)) $query->andFilterWhere(['<=', 'create_time', $date_to]);
    // grid filtering conditions
    $query->andFilterWhere([
      'status' => $this->status,
      'type' => $this->method,
    ]);

    if(is_numeric($this->client)){
      $query->andFilterWhere(['=', 'client_id', $this->client]);
    }else{

    };
/*
    'client_id' => $this->client_id,
*/
    return $dataProvider;
  }
}
