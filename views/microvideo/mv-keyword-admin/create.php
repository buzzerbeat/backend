<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model microvideo\models\MvKeyword */

$this->title = 'Create Mv Keyword';
$this->params['breadcrumbs'][] = ['label' => 'Mv Keywords', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mv-keyword-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
