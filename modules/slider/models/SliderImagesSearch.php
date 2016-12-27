<?php

namespace app\modules\slider\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\slider\models\SliderImages;

/**
 * SliderImagesSearch represents the model behind the search form about `app\modules\slider\models\SliderImages`.
 */
class SliderImagesSearch extends SliderImages
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image_id'], 'integer'],
            [['address', 'text', 'gender'], 'safe'],
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
        $query = SliderImages::find();

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
            'image_id' => $this->image_id,
        ]);

        $query->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'gender', $this->gender]);

        return $dataProvider;
    }
}
