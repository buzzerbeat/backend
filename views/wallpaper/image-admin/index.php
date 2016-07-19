<?php

use yii\helpers\ArrayHelper;
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

//            'id',
            [
                'label' => 'sid',
                'attribute' => 'sid',
                'format' => 'html',
                'value'=>function($data) { return  $data->image->sid; },
            ],
            [
                'label' => '图片',
                'attribute' => 'imgSrc',
                'format' => 'image',
                'value'=>function($data) { return  Yii::getAlias('@imgUrl/thumb/240/320/'.$data->image->sid.'/'.$data->image->md5.$data->image->dotExt); },
            ],
            [
                'label' => '分类',
                'attribute' => 'album',
                'format' => 'html',
                'filter' => Html::activeDropDownList($searchModel, 'album', ArrayHelper::map(\wallpaper\models\Album::find()->asArray()->all(), 'id', 'title'),['class'=>'form-control','prompt' => '类别']),
                'value'=>function($data) {
                    if (!empty($data->album)) {
                        return $data->album->title;
                    } else {
                        return "";
                    }
                },

            ],
            [
                'label' => '状态',
                'attribute' => 'status',
                'format' => 'html',
                'filter' => [
                    \wallpaper\models\WpImage::STATUS_ACTIVE => "可用",
                    \wallpaper\models\WpImage::STATUS_INACTIVE => "不可用"
                ],
                'value'=>function($data) {
                    return  \wallpaper\models\WpImage::STATUS_MAP[$data->status];
                },
            ],
//            'status',
//            'desc',


//            'file_path',
//            'add_time:datetime',
            // 'update_time:datetime',
//             'width',
//             'height',
            // 'mime',
            // 'md5',
            // 'size',
            // 'dynamic',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
