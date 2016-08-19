<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model article\models\TtArticle */
$this->registerJsFile('@web/ckeditor/ckeditor.js');
$this->title = $model->article_id;
$this->params['breadcrumbs'][] = ['label' => 'Tt Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("CKEDITOR.replace( 'editor1', {
					width: '70%',
					height: 500
				});", View::POS_END, 'my-options');
?>
<div class="tt-article-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->article_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->article_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'article_id',
            [
                'label' => 'Title',
                'format' => 'html',
                'value'=>$model->article->title,
            ],
            [
                'label' => 'Content',
                'format' => 'raw',
                'value'=>'<textarea id="editor1" rows="10" cols="120">' . $model->article->webContent . '</textarea>',
            ],
            'type',
            'style',
            'media_id',
            'cover_ids',
        ],
    ]) ?>

</div>
