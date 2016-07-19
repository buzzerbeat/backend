<?php

namespace backend\models;

use wallpaper\models\WpImage;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Image;

/**
 * ImageSearch represents the model behind the search form about `common\models\Image`.
 */
class WpImageSearch extends Image
{

    public $album;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'album'], 'integer'],
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
        $this->load($params);

        if (!empty($this->album)) {
            $query = WpImage::find()->leftJoin('album_img_rel', '`album_img_rel`.`wp_img_id` = `wp_image`.`id`');
        } else {
            $query = WpImage::find();
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);






        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['status'=>1]);
        if (!empty($this->album)) {
            $query->andFilterWhere(['`album_img_rel`.`album_id`'=>$this->album]);
        }
        return $dataProvider;
    }
}
