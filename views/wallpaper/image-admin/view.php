<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Image */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Images', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="image-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
<!--        --><?//= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'status',
            'img_id',

            'source_url',
            [
                'label' => '文件路径',
                'value'=>$model->image->file_path,
            ],

            [
                'label' => '宽',
                'value'=>$model->image->width,
            ],
            [
                'label' => '高',
                'value'=>$model->image->height,
            ],
            [
                'label' => '大小',
                'value'=>$model->image->size,
            ],

            [
                'label' => 'Mime',
                'value'=>$model->image->mime,
            ],
            [
                'label' => 'Dynamic',
                'value'=>$model->image->dynamic,
            ],

            'desc',
        ],
    ]) ?>

</div>
