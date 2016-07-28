<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Image;

/**
 * ImageSearch represents the model behind the search form about `common\models\Image`.
 */
class ImageSearch extends Image
{
    const STRING_IMAGE_NOT_EXIST = '图片不存在';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'add_time', 'update_time', 'width', 'height', 'size', 'dynamic'], 'integer'],
            [['desc', 'file_path', 'mime', 'md5'], 'safe'],
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
        $query = Image::find();

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
            'add_time' => $this->add_time,
            'update_time' => $this->update_time,
            'width' => $this->width,
            'height' => $this->height,
            'size' => $this->size,
            'dynamic' => $this->dynamic,
        ]);

        $query->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'file_path', $this->file_path])
            ->andFilterWhere(['like', 'mime', $this->mime])
            ->andFilterWhere(['like', 'md5', $this->md5])
            ->andFilterWhere(['status'=>1]);

        return $dataProvider;
    }
    
    public function fields()
    {
        $fields = parent::fields();
        $fields[] = 'sid';
        $fields[] = 'dotExt';
        $fields[] = 'id';
        $fields[] = 'file_path';
        $fields[] = 'addTime';
        $fields[] = 'updateTime';
        return $fields;
    }
    
    public function getAddTime(){
        return date('Y-m-d H:i:s', $this->add_time);
    }
    
    public function getUpdateTime(){
        return date('Y-m-d H:i:s', $this->update_time);
    }
}
