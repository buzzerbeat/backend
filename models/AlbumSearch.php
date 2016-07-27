<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use wallpaper\models\Album;
use backend\models\WpImageSearch;

/**
 * AlbumSearch represents the model behind the search form about `wallpaper\models\Album`.
 */
class AlbumSearch extends Album
{
    
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const STATUS_MAP = [
        self::STATUS_INACTIVE => "不可用",
        self::STATUS_ACTIVE => "可用",
    ];
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'create_time','category', 'icon'], 'integer'],
            [['title', 'key'], 'safe'],
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
        $query = Album::find();

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
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'key', $this->key]);

        return $dataProvider;
    }
    
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['create_time'], $fields['icon']);
        $fields[] = 'id';
        $fields[] = 'status';
        //$fields[] = 'category';
        $fields[] = 'sid';
        $fields[] = 'iconImg';
        $fields[] = 'cat';
        $fields[] = 'createTime';
        $fields[] = 'wpImages';
        return $fields;
    }
    
    public function getCreateTime(){
    	return date('Y-m-d H:i:s', $this->create_time);
    }
    
    public function getWpImages() {
        return $this->hasMany(WpImageSearch::className(), ['id' => 'wp_img_id'], '')
            ->via('imgRels');
    }

    public function getImgRels()
    {
        return $this->hasMany(AlbumImgRelSearch::className(), ['album_id'=>'id'])->limit(20)->orderBy('id desc');
    }
}
