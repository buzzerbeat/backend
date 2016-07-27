<?php

namespace backend\models\microvideo;

use Yii;
use backend\models\MvVideoSearch;

/**
 * This is the model class for table "mv_tag".
 *
 * @property integer $id
 * @property integer $name
 */
class MvTag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mv_tag';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('mvDb');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max'=>40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }
    
    public function fields()
    {
        $fields = [
            'id',
            'name',
            'tags',
        ];
        return $fields;
    }
    
    public function getTags(){
        //return $this->hasMany(MvTagRel::className(), ['tag_id'=>'id']);
        $rels = MvTagRel::find()->where('tag_id = ' . $this->id . ' or rel_tag_id = ' . $this->id)->all();
        $ret = [];
        foreach($rels as $rel){
            $relId = $rel->rel_tag_id == $this->id ? $rel->tag_id : $rel->rel_tag_id;
        	$tag = MvTag::findOne($relId);
        	if(empty($tag)){
        		continue;
        	}
        	$ret[] = ['id'=>$rel->id, 'tag'=>['id'=>$tag->id, 'name'=>$tag->name, 'type'=>$rel->rel_tag_id == $this->id ? 'top' : 'sub']];
        }
        
        return $ret;
    }
    
    public function getVideos(){
        return $this->hasMany(MvVideoTagRel::className(), ['mv_tag_id'=>'id']);
    }
}
