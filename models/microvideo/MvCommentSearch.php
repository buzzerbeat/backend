<?php

namespace backend\models\microvideo;

use Yii;
use yii\data\ActiveDataProvider;
use yii\base\Model;
use backend\models\CommentSearch;

/**
 * This is the model class for table "mv_comment".
 *
 * @property integer $id
 * @property integer $comment_id
 * @property integer $status
 * @property integer $dig
 * @property integer $is_hot
 */
class MvCommentSearch extends \microvideo\models\MvComment
{ 
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
        $query = MvCommentSearch::find();
    
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
            'comment_id' => $this->comment_id,
            'mv_video_id' => $this->mv_video_id,
            'status' => $this->status,
            'dig' => $this->dig,
            'is_hot' => $this->is_hot,
        ]);
    
        return $dataProvider;
    }
    
    public function fields(){
        $fields = [
            'id'=>'comment_id',
            'vid'=>'mv_video_id',
            'comment',
            'dig',
            'is_hot'
        ];
         
        return $fields;
    }
    
    public function getComment(){
        return $this->hasOne(CommentSearch::className(), ['id'=>'comment_id']);
    }
}
