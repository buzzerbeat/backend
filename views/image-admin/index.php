<?php

use common\models\Image;
use yii\helpers\Html;
use yii\grid\GridView;
use common\components\Utility;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ImageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '图片管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="image-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php $form = ActiveForm::begin(['action' => \yii::$app->params['adminUrl'] . 'image-admin/upload', 'options' => ['enctype' => 'multipart/form-data']]) ?>

        <?= $form->field($uploadImg, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>
    <p>
        <?= Html::button('多图', ['class' => 'btn btn-success', 'type' => 'submit']) ?>
    </p>
    <?php ActiveForm::end() ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            /* ['class' => 'yii\grid\SerialColumn'], */
            [
                'label' => 'sid',
                'attribute' => 'sid',
                'format' => 'html',
                'value'=>function($data) { return  $data->id . '<br/>' . $data->sid; },
            ],
            [
                'label' => '信息',
                'format' => 'html',
                'value' => function($data){
                    return 'PATH:' . $data->file_path . '<br/>' . '增加时间：' . date('Y-m-d H:i:s', $data->add_time) . 
                    '&nbsp;&nbsp;更新时间：' . date('Y-m-d H:i:s', $data->update_time);
                }
            ],
            [
            'label' => '宽*高',
                'format' => 'html',
                'value' => function($data){
                    return '宽：' . $data->width . '<br/>高：' . $data->height;
                }
            ],
            [
                'label' => '预览',
                'attribute' => 'imgSrc',
                'format' => 'image',
                'value'=>function($data) { return  Yii::getAlias('@imgUrl/thumb/300/120/'.$data->sid.'/'.$data->md5.$data->dotExt); },
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
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{privew} {delete}',
                'buttons' => [
                    'privew'=>function($url, $model, $key){
                        $url = Yii::getAlias('@imgUrl/thumb/0/0/'.$model->sid.'/'.$model->md5.$model->dotExt);
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['target'=>'_blank']);
                    }
                ]
            ],
        ],
    ]); ?>
</div>
