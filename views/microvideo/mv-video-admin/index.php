<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MvVideoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mv Videos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mv-video-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Mv Video', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'sid',
                'attribute' => 'sid',
                'format' => 'html',
                'value'=>function($data) { return  $data->sid; },
            ],

            [
                'label' => '缩略图',
                'attribute' => 'cover_img',
                'format' => 'image',
                'value'=>function($data) { return  Yii::getAlias('@imgUrl/thumb/240/320/'.$data->video->coverImg->sid.'/'.$data->video->coverImg->md5.$data->video->coverImg->dotExt); },
            ],
            [
                'label' => '视频',
                'format' => 'raw',
                'value'=>function($data) {
                    return Html::a('播放', $data->video->url, [ 'target' => '_blank',]);

                },
            ],
            [
                'label' => '视频',
                'format' => 'raw',
                'value' =>function($data) {
                    //CrawlThreadSearch%5Bid%5D=&CrawlThreadSearch%5Bkey%5D=&CrawlThreadSearch%5Btask_id%5D=3&CrawlThreadSearch%5Bstatus%5D=&CrawlThreadSearch%5Btime%5D=
                    return  Html::a("查看", "/video-admin?" . urlencode("VideoSearch[id]") . '=' . $data->video_id);

                },

            ],
            [
                'label' => '状态',
                'attribute' => 'status',
                'format' => 'html',
                'filter' => \microvideo\models\MvVideo::STATUS_MAP,
                'value'=>function($data) {
                    return  \microvideo\models\MvVideo::STATUS_MAP[$data->status];
                },
            ],
            'key',
            'title',
            // 'desc:ntext',
            [
                'label' => '源',
                'format' => 'raw',
                'value'=>function($data) {
                    return Html::a('查看', $data->source_url, [ 'target' => '_blank',]);

                },
            ],

            [
                'label' => '创建时间',
                'format' => 'html',
                'value'=>function($data) {
                    return date("Y-m-d H:i:s", $data->create_time);

                },
            ],

            [
                'label' => '标签',
                'attribute' => 'keywords',
                'format' => 'html',
//                'filter' => \microvideo\models\MvVideo::STATUS_MAP,
                'value'=>function($data) {
                    $ret = "";
                    foreach($data->keywords as $keyword) {
                            $ret.=$keyword->name."<br>";
                    }
                    return $ret;
//                    return  \microvideo\models\MvVideo::STATUS_MAP[$data->status];
                },
            ],
            // 'update_time',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
