<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CrawlThread */

$this->title = 'Create Crawl Thread';
$this->params['breadcrumbs'][] = ['label' => 'Crawl Threads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crawl-thread-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
