<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CrawlTask */

$this->title = 'Create Crawl Task';
$this->params['breadcrumbs'][] = ['label' => 'Crawl Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crawl-task-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
