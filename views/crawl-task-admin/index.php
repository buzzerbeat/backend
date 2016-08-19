<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CrawlTaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Crawl Tasks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crawl-task-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Crawl Task', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'command',
            [
                'label' => '状态',
                'attribute' => 'status',
                'format' => 'html',
                'filter' => \common\models\CrawlTask::STATUS_MAP,
                'value'=>function($data) {
                    return  \common\models\CrawlTask::STATUS_MAP[$data->status];
                },
            ],

            'start_time:datetime',
            'end_time:datetime',
//            'success_num',
//            'fail_num',
            [
                'label' => 'Success Entity',
                'format' => 'html',
                'value' => function ($data) {
                    return $data->successEntityNum;
                }
            ],

            [
                'label' => 'Fail Entity',
                'format' => 'html',
                'value' => function ($data) {
                    return $data->failEntityNum;
                }
            ],
            [
                'label' => 'Duplicate Entity',
                'format' => 'html',
                'value' => function ($data) {
                    return $data->duplicateEntityNum;
                }
            ],

            [
                'label' => 'Filter Entity',
                'format' => 'html',
                'value' => function ($data) {
                    return $data->filterEntityNum;
                }
            ],
            // 'filter_num',
            // 'duplicate_num',
            [
                'label' => 'Errors',
                'format' => 'html',
                'value'=>function($data) {
                    $errorArr = json_decode($data->error_json, true);
                    $errors = [];
                    if (!empty($errorArr)) {
                        foreach($errorArr as $key=>$value) {
                            $errors[] = "[" . $key . "]\t" . (is_array($value) ? array_shift($value):$value);
                        }
                        return  implode("<br>", $errors);
                    }
                    return "";

                },
            ],
            [
                'label' => '子任务 ',
                'format' => 'raw',
                'value' =>function($data) {
                    //CrawlThreadSearch%5Bid%5D=&CrawlThreadSearch%5Bkey%5D=&CrawlThreadSearch%5Btask_id%5D=3&CrawlThreadSearch%5Bstatus%5D=&CrawlThreadSearch%5Btime%5D=
                    return  Html::a("查看", "/crawl-thread-admin?" . urlencode("CrawlThreadSearch[task_id]") . '=' . $data->id);

                },


            ],
//             'error_json:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
