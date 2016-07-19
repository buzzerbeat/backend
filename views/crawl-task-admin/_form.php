<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CrawlTask */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="crawl-task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'command')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'start_time')->textInput() ?>

    <?= $form->field($model, 'end_time')->textInput() ?>

    <?= $form->field($model, 'success_num')->textInput() ?>

    <?= $form->field($model, 'fail_num')->textInput() ?>

    <?= $form->field($model, 'filter_num')->textInput() ?>

    <?= $form->field($model, 'duplicate_num')->textInput() ?>

    <?= $form->field($model, 'error_json')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
