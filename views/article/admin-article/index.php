<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\article\TtArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '新闻管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tt-article-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tt Article', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'article_id',

            [
                'label' => '缩略图',
                'attribute' => 'thumbs',
                'format' => 'html',
                'value'=>function($data) {
                    $thumbArr = [];
                    if (!empty($data->article) && !empty($data->article->getCoverList())) {
                        foreach($data->article->getCoverList() as $thumb) {
                            $thumbArr[] = "<img width='150' height='98' src='" . Yii::getAlias('@imgUrl/thumb/300/196/'.$thumb->sid.'/'.$thumb->md5.$thumb->dotExt) . "'/>";
                        }
                    }
                    return implode(' ', $thumbArr);
                },
            ],
            [
                'label' => 'Title',
                'format' => 'html',
                'value'=>function($data) {
                    return $data->article->title;

                },
            ],
//            [
//                'label' => 'Abstract',
//                'format' => 'html',
//                'value'=>function($data) {
//                    return $data->article->abstract;
//
//                },
//            ],
            [
                'label' => '类型',
                'attribute' => 'type',
                'format' => 'html',
                'filter' => \article\models\TtArticle::TYPE_DICT,
                'value'=>function($data) {
                    return  \article\models\TtArticle::TYPE_DICT[$data->type];
                },
            ],

            [
                'label' => '样式',
                'attribute' => 'style',
                'format' => 'html',
                'filter' => \article\models\TtArticle::STYLE_DICT,
                'value'=>function($data) {
                    return  \article\models\TtArticle::STYLE_DICT[$data->style];
                },
            ],

            [
                'label' => '媒体',
                'attribute' => 'media_id',
                'format' => 'html',
                'value'=>function($data) {
                    return  !empty($data->media) ? $data->media->name : "";
                },
            ],

            [
                'label' => '关键词',
                'attribute' => 'keywords',
                'format' => 'html',
                'value'=>function($data) {
                    $tagArr = [];
                    if (!empty($data->article) && !empty($data->article->tags)) {
                        foreach($data->article->tags as $tag) {
                            $tagArr[] = $tag->name;
                        }
                    }
                    return  implode(' | ', $tagArr);
                },
            ],
            // 'cover_ids',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
