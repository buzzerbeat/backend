<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model article\models\TtArticle */

$this->title = 'Update Tt Article: ' . $model->article_id;
$this->params['breadcrumbs'][] = ['label' => 'Tt Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->article_id, 'url' => ['view', 'id' => $model->article_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tt-article-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
