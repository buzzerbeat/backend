<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CrawlTask;

/**
 * CrawlTaskSearch represents the model behind the search form about `common\models\CrawlTask`.
 */
class CrawlTaskSearch extends CrawlTask
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'start_time', 'end_time', 'success_num', 'fail_num', 'filter_num', 'duplicate_num'], 'integer'],
            [['command', 'error_json'], 'safe'],
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
        $query = CrawlTask::find();

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
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'success_num' => $this->success_num,
            'fail_num' => $this->fail_num,
            'filter_num' => $this->filter_num,
            'duplicate_num' => $this->duplicate_num,
        ]);

        $query->andFilterWhere(['like', 'command', $this->command])
            ->andFilterWhere(['like', 'error_json', $this->error_json]);

        return $dataProvider;
    }
}
