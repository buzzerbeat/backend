<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel qsyk\models\ResourceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Resources';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resource-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Resource', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'type',
            [
                'label' => 'Content',
                'format' => 'text',
                'value' => function($dataProvider) {
                    $value = mb_substr($dataProvider['desc'],0,10,'UTF-8') . '...' ;
                    return $value;
                },
            ],
            'keyword',
            'title',
            'status',
            // 'rank',
            // 'add_time:datetime',
            // 'pub_time:datetime',
            // 'web_pubtime:datetime',
            // 'valid_time:datetime',
            // 'del_time:datetime',
            // 'last_update_time:datetime',
            // 'pre_pub_set',
            // 'pre_pub_time:datetime',
            // 'pub_way',
            // 'userid',
            // 'adminid',
            // 'is_check',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
