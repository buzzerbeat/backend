<?php

namespace backend\models\taskdist;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use taskdist\models\Task;

/**
 * TaskSearch represents the model behind the search form about `taskdist\models\Task`.
 */
class TaskSearch extends Task
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'desc', 'max_concurrency_num'], 'integer'],
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
    public function searchV2($params)
    {

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
        $query = Task::find();

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
            'status' => $this->status,
            'desc' => $this->desc,
            'max_concurrency_num' => $this->max_concurrency_num,
        ]);


        return $dataProvider;
    }
}
