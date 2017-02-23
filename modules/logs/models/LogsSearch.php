<?php

namespace app\modules\logs\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\logs\models\Log;

/**
 * LogsSearch represents the model behind the search form of `app\modules\logs\models\Log`.
 */
class LogsSearch extends Log
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'action', 'user_id', 'admin_id', 'created_at'], 'integer'],
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
        $query = Log::find();

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
            'type' => $this->type,
            'action' => $this->action,
            'user_id' => $this->user_id,
            'admin_id' => $this->admin_id,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }
}
