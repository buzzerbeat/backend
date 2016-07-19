<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model microvideo\models\MvVideo */

$this->title = 'Update Mv Video: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Mv Videos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mv-video-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
