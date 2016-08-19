<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/8/10
 * Time: 20:51
 */

namespace backend\models\article;


use article\models\TtArticle;

class TtArticleUpdateForm extends TtArticle
{

    public $content;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['content'], 'string'];
        return $rules;
    }

}