<?php

namespace app\modules\orhidBlog\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\orhidBlog\models\OrhidBlog;

/**
 * OrhidBlogSearch represents the model behind the search form about `app\modules\orhidBlog\models\OrhidBlog`.
 */
class OrhidBlogSearch extends OrhidBlog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'language', 'state'], 'integer'],
            [['title', 'annotation', 'text', 'image'], 'safe'],
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
        $query = OrhidBlog::find();

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
            'language' => $this->language,
            'state' => $this->state,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'annotation', $this->annotation])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }
}
