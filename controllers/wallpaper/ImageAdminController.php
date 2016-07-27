<?php

namespace backend\controllers\wallpaper;

use wallpaper\models\WpImage;
use Yii;
use common\models\Image;
use backend\models\WpImageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\controllers\BaseController;
use common\components\Utility;
use yii\data\ActiveDataProvider;
use backend\models\AlbumSearch;

/**
 * ImageAdminController implements the CRUD actions for Image model.
 */
class ImageAdminController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * Lists all Image models.
     * @return mixed
     */
    public function actionIndex()
    {
        $imgUrl = Yii::getAlias('@imgUrl');
        return $this->render('list.tpl', ['imgUrl'=>$imgUrl]);
        
        /* $searchModel = new WpImageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]); */
    }

    /**
     * Displays a single Image model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Image model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Image();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Image model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Image model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Image model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Image the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WpImage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionData(){
    	$wpId = \Yii::$app->request->get('wpid', 0);
    	$wpSid = \Yii::$app->request->get('wpsid', '');
    	$desc = \Yii::$app->request->get('desc', 'desc');
    	$albumName = \Yii::$app->request->get('keyword', '');
    	$imgId = \Yii::$app->request->get('imgid', 0);
    	$imgSid = \Yii::$app->request->get('imgsid', '');
    	
        $query = WpImageSearch::find();
        
        if(!empty($wpId)){
        	$query->andWhere(['id'=>$wpId]);
        }
        if(!empty($wpSid)){
        	$query->andWhere(['id'=>Utility::id($wpSid)]);
        }
        if(!empty($imgId)){
            $query->andWhere(['img_id'=>$imgId]);
        }
        if(!empty($imgSid)){
            $query->andWhere(['img_id'=>Utility::id($imgSid)]);
        }
        if(!empty($albumName)){
            $album = AlbumSearch::find()->select('id')->where(['like', 'title', "%{$albumName}%", false])->all();
            $ids = [];
            foreach($album as $al){
            	$ids[] = $al->id;
            }

        	$query->joinWith('rel')->where(['album_img_rel.album_id'=>$ids]);
        }
        return new ActiveDataProvider([
            'query'=>$query->orderBy("id {$desc}")
        ]);
    }
}
