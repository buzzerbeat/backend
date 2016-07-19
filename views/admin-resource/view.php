<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model qsyk\models\Resource */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Resources', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resource-view">

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
            'type',
            'desc',
            'keyword',
            'title',
            'status',
            'rank',
            'add_time:datetime',
            'pub_time:datetime',
            'web_pubtime:datetime',
            'valid_time:datetime',
            'del_time:datetime',
            'last_update_time:datetime',
            'pre_pub_set',
            'pre_pub_time:datetime',
            'pub_way',
            'userid',
            'adminid',
            'is_check',
        ],
    ]) ?>

</div>
