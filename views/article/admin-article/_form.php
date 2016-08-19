<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model article\models\TtArticle */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile('@web/ckeditor/ckeditor.js');
$this->registerJs("CKEDITOR.replace( 'editor', {
					width: '70%',
					height: 500
				});", View::POS_END, 'my-options');
?>

<div class="tt-article-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'article_id')->textInput() ?>

    <?= $form->field($model, 'type')->textInput() ?>


    <?= $form->field($model, 'content')->textarea(
        ['id'=>'editor']
    ) ?>


    <?= $form->field($model, 'style')->textInput() ?>


    <?= $form->field($model, 'media_id')->textInput() ?>

    <?= $form->field($model, 'cover_ids')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
