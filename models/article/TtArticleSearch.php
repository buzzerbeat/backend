<?php

namespace backend\models\article;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use article\models\TtArticle;

/**
 * TtArticleSearch represents the model behind the search form about `article\models\TtArticle`.
 */
class TtArticleSearch extends TtArticle
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'type', 'style',  'media_id'], 'integer'],
            [['cover_ids'], 'safe'],
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
        $query = TtArticle::find();

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
            'article_id' => $this->article_id,
            'type' => $this->type,
            'style' => $this->style,
            'media_id' => $this->media_id,
        ]);

        $query->andFilterWhere(['like', 'cover_ids', $this->cover_ids]);

        $query->orderBy(['article_id'=>SORT_DESC]);

        return $dataProvider;
    }
}
