<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CrawlThreadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Crawl Threads';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crawl-thread-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Crawl Thread', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
//            'id',
            'key',
//            'task_id',
            [
                'label' => '状态',
                'attribute' => 'status',
                'format' => 'html',
                'filter' => \common\models\CrawlThread::STATUS_MAP,
                'value'=>function($data) {
                    return  \common\models\CrawlThread::STATUS_MAP[$data->status];
                },
            ],
//            'site',
            'time:datetime',




            // 'duration',
//             'entity_id:ntext',
            [
                'label' => '关联资源',
                'format' => 'html',
                'value'=>function($data) {
                    $entityIds = json_decode($data->entity_id, true);
                    if (!empty($entityIds)) {
                        $htmlTxt = "";
                        foreach($entityIds as $entity) {
                            //http://qy1.appcq.cn:8086
                            $htmlTxt .= Html::a($entity,'/wallpaper/image-admin/view?id='.$entity) . "<br>";
                        }
                        return  $htmlTxt;
                    }
                    return "";

                },
            ],
            [
                'label' => 'Errors',
                'format' => 'html',
                'value'=>function($data) {
                    $errorArr = json_decode($data->error_json, true);
                    $errors = [];
                    if (!empty($errorArr)) {
                        foreach($errorArr as $key=>$value) {
                            $errors[] = "[" . $key . "]\t" . $value;
                        }
                        return  implode("<br>", $errors);
                    }
                    return "";

                },
            ],
//             'error_json:ntext',
            [
                'label' => '采集源',
                'format' => 'raw',
                'value'=>function($data) {
                    return Html::a('查看',$data->url);

                },
            ],
            [
                'label' => '所属任务',
                'format' => 'raw',
                'value'=>function($data) {
                    return Html::a('查看','/crawl-thread-admin/view?id=' . $data->task_id);

                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
