<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CrawlThread;

/**
 * CrawlThreadSearch represents the model behind the search form about `common\models\CrawlThread`.
 */
class CrawlThreadSearch extends CrawlThread
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'task_id', 'status', 'time', 'duration'], 'integer'],
            [['site', 'url', 'key', 'entity_id', 'error_json'], 'safe'],
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
        $query = CrawlThread::find();

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
            'task_id' => $this->task_id,
            'status' => $this->status,
            'time' => $this->time,
            'duration' => $this->duration,
        ]);

        $query->andFilterWhere(['like', 'site', $this->site])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'entity_id', $this->entity_id])
            ->andFilterWhere(['like', 'error_json', $this->error_json]);

        return $dataProvider;
    }
}
