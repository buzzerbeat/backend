<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Image;
use common\components\Utility;

/**
 * ImageSearch represents the model behind the search form about `common\models\Image`.
 */
class BackendImg extends Image
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
    	return Utility::unix_get_time($this->add_time);
    }
    
    public function getUpdateTime(){
    	return Utility::unix_get_time($this->update_time);
    }
}
