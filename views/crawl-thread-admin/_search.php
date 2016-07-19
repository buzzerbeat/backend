<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CrawlThreadSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="crawl-thread-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'task_id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'site') ?>

    <?= $form->field($model, 'url') ?>

    <?php // echo $form->field($model, 'key') ?>

    <?php // echo $form->field($model, 'time') ?>

    <?php // echo $form->field($model, 'duration') ?>

    <?php // echo $form->field($model, 'entity_id') ?>

    <?php // echo $form->field($model, 'error_json') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
