<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model qsyk\models\ResourceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="resource-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'desc') ?>

    <?= $form->field($model, 'keyword') ?>

    <?= $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'rank') ?>

    <?php // echo $form->field($model, 'add_time') ?>

    <?php // echo $form->field($model, 'pub_time') ?>

    <?php // echo $form->field($model, 'web_pubtime') ?>

    <?php // echo $form->field($model, 'valid_time') ?>

    <?php // echo $form->field($model, 'del_time') ?>

    <?php // echo $form->field($model, 'last_update_time') ?>

    <?php // echo $form->field($model, 'pre_pub_set') ?>

    <?php // echo $form->field($model, 'pre_pub_time') ?>

    <?php // echo $form->field($model, 'pub_way') ?>

    <?php // echo $form->field($model, 'userid') ?>

    <?php // echo $form->field($model, 'adminid') ?>

    <?php // echo $form->field($model, 'is_check') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
