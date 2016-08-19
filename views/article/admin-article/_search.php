<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\article\TtArticleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tt-article-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'article_id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'style') ?>

    <?= $form->field($model, 'wenda_info') ?>

    <?= $form->field($model, 'media_id') ?>

    <?php // echo $form->field($model, 'cover_ids') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
