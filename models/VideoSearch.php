<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Video;

/**
 * VideoSearch represents the model behind the search form about `common\models\Video`.
 */
class VideoSearch extends Video
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'length', 'width', 'height', 'size', 'add_time', 'pub_time', 'watermark', 'regex_setting'], 'integer'],
            [['key', 'desc', 'cover_img', 'url', 'm3u8_url', 'local', 'site_url'], 'safe'],
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
        $query = Video::find();

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
            'length' => $this->length,
            'width' => $this->width,
            'height' => $this->height,
            'size' => $this->size,
            'add_time' => $this->add_time,
            'pub_time' => $this->pub_time,
            'watermark' => $this->watermark,
            'regex_setting' => $this->regex_setting,
        ]);

        $query->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'cover_img', $this->cover_img])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'm3u8_url', $this->m3u8_url])
            ->andFilterWhere(['like', 'local', $this->local])
            ->andFilterWhere(['like', 'site_url', $this->site_url]);

        return $dataProvider;
    }
}
