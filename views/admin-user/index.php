<?php

use common\models\User;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'label' => 'avatar',
                'attribute' => 'avatar',
                'format' => 'image',
                'value'=>function($data) {
                    return  !empty($data->avatarImg) ?  Yii::getAlias('@imgUrl/thumb/80/80/'.$data->avatarImg->sid.'/'.$data->avatarImg->md5.$data->avatarImg->dotExt) : "";
                },
            ],
            'username',
            'device_uuid',
//            'auth_key',
//            'password_hash',
            // 'password_reset_token',
            // 'email:email',
            [
                'label' => 'Status',
                'attribute' => 'status',
                'format' => 'html',
                'filter' => User::STATUS_MAP,
                'value'=>function($data) {
                    return  User::STATUS_MAP[$data->status];
                },
            ],

            [
                'label' => 'Type',
                'attribute' => 'type',
                'format' => 'html',
                'filter' => User::TYPE_MAP,
                'value'=>function($data) {
                    return  User::TYPE_MAP[$data->type];
                },
            ],
            // 'created_at',
            // 'updated_at',
//             'type',
            // 'salt',
            // 'sex',
            // 'avatar',
             'qq',
             'weixin',
             'weibo',
             'mobile',
             'client_id',
            // 'personal_sign:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
