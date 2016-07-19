<?php

use common\models\Video;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\VideoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Videos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Video', ['create'], ['class' => 'btn btn-success']) ?>
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
                'value'=>function($data) { return  Yii::getAlias('@imgUrl/thumb/240/320/'.$data->coverImg->sid.'/'.$data->coverImg->md5.$data->coverImg->dotExt); },
            ],

            [
                'label' => '状态',
                'attribute' => 'status',
                'format' => 'html',
                'filter' => Video::STATUS_MAP,
                'value'=>function($data) {
                    return  Video::STATUS_MAP[$data->status];
                },
            ],
            'key',
            [
                'label' => 'Desc',
                'format' => 'raw',
                'value'=>function($data) {
                    return mb_substr($data->desc,0, 20) . "...";

                },
            ],

//            'cover_img',
            // 'length',
            // 'width',
            // 'height',
            // 'size',
             'add_time:datetime',
            // 'pub_time:datetime',
            // 'watermark',
            [
                'label' => '视频',
                'format' => 'raw',
                'value'=>function($data) {
                    return Html::a('播放', $data->url, [ 'target' => '_blank',]);

                },
            ],
            [
                'label' => '源网站',
                'format' => 'raw',
                'value'=>function($data) {
                    return Html::a('查看', $data->site_url, [ 'target' => '_blank',]);

                },
            ],
            // 'm3u8_url:url',
            // 'local',
            // 'regex_setting',
//             'site_url:url',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
