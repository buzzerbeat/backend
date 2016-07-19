<?php

use common\models\Image;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ImageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Images';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="image-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Image', ['create'], ['class' => 'btn btn-success']) ?>
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
                'label' => '图片',
                'attribute' => 'imgSrc',
                'format' => 'image',
                'value'=>function($data) { return  Yii::getAlias('@imgUrl/thumb/240/320/'.$data->sid.'/'.$data->md5.$data->dotExt); },
            ],
            [
                'label' => '状态',
                'attribute' => 'status',
                'format' => 'html',
                'filter' => Image::STATUS_MAP,
                'value'=>function($data) {
                    return  Image::STATUS_MAP[$data->status];
                },
            ],
            'file_path',
            'add_time:datetime',
             'width',
             'height',
            // 'mime',
            // 'md5',
            // 'size',
            // 'dynamic',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
