<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model article\models\TtArticle */

$this->title = 'Create Tt Article';
$this->params['breadcrumbs'][] = ['label' => 'Tt Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tt-article-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
