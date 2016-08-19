<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use microvideo\models\MvVideo;
use microvideo\models\MvVideoCount;
use backend\models\microvideo\MvTag;
use backend\models\microvideo\MvVideoTagRel;
use backend\models\CommentSearch;

/**
 * MvVideoSearch represents the model behind the search form about `microvideo\models\MvVideo`.
 */
class MvVideoSearch extends MvVideo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'video_id', 'status', 'review'], 'integer'],
            [['key', 'title', 'desc', 'source_url', 'create_time', 'update_time'], 'safe'],
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
        $query = MvVideo::find();

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
            'video_id' => $this->video_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'source_url', $this->source_url])
            ->andFilterWhere(['like', 'create_time', $this->create_time])
            ->andFilterWhere(['like', 'update_time', $this->update_time]);

        return $dataProvider;
    }
    
    public function fields()
    {
        $fields = [
            'id',
            'sid',
            'title',
            'desc',
            'elapsedTime',
            'video',
            'keywords',
            'createTime',
            'updateTime',
            'review',
            'tags',
            'status',
            'key' => function($modal){
                $video = VideoSearch::findOne($modal->video_id);
                return $video->key;    
            },
            'commentNum',
            'countNum',
        ];
        return $fields;
    }
    
    public function getCreateTime(){
    	return date('Y-m-d H:i:s', $this->create_time);
    }
    
    public function getUpdateTime(){
        return date('Y-m-d H:i:s', $this->update_time);
    }
    
    public function getTags(){
        return $this->hasMany(MvTag::className(), ['id' => 'mv_tag_id'])
        ->via('tagRels');
    }
    
    public function getTagRels() {
        return $this->hasMany(MvVideoTagRel::className(), ['mv_video_id'=>'id']);
    }
    
    public function getCommentNum(){
    	return $this->hasMany(CommentSearch::className(), ['item_id'=>'id'])
    	   ->where(['item_type'=>'microvideo/video', 'status'=>CommentSearch::STATUS_ACTIVE])
    	   ->count();
    }
    
    public function getCountNum(){
        return $this->hasOne(MvVideoCount::className(), ['video_id'=>'id']);
    }

}
