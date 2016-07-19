<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model qsyk\models\Resource */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="resource-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'desc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'rank')->textInput() ?>

    <?= $form->field($model, 'add_time')->textInput() ?>

    <?= $form->field($model, 'pub_time')->textInput() ?>

    <?= $form->field($model, 'del_time')->textInput() ?>

    <?= $form->field($model, 'last_update_time')->textInput() ?>

    <?= $form->field($model, 'pre_pub_set')->textInput() ?>

    <?= $form->field($model, 'pre_pub_time')->textInput() ?>

    <?= $form->field($model, 'pub_way')->textInput() ?>

    <?= $form->field($model, 'userid')->textInput() ?>

    <?= $form->field($model, 'adminid')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
