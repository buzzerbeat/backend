<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model microvideo\models\MvVideo */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Mv Videos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mv-video-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            'video_id',
            'status',
            'key',
            'title',
            'desc:ntext',
            'source_url:url',
            'create_time',
            'update_time',
        ],
    ]) ?>

</div>
