<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use microvideo\models\MvKeyword;
use backend\models\microvideo\MvTag;

/**
 * MvKeywordSearch represents the model behind the search form about `microvideo\models\MvKeyword`.
 */
class MvKeywordSearch extends MvKeyword
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'rank', 'is_filter', 'tag_id'], 'integer'],
            [['name'], 'safe'],
            [['name'], 'unique'],
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
        $query = MvKeyword::find();

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
            'rank' => $this->rank,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
    
    public function fields()
    {
        $fields = parent::fields();
        $fields[] = 'id';
        $fields[] = 'rank';
        $fields[] = 'tag';
        $fields[] = 'is_filter';

        return $fields;
    }
    
    public function getTag(){
    	$tag = MvTag::findOne($this->tag_id);
    	return !empty($tag) ? $tag : null;
    }
}
