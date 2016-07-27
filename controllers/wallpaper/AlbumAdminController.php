<?php

namespace backend\controllers\wallpaper;

use Yii;
use wallpaper\models\Album;
use backend\models\AlbumSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Category;
use backend\controllers\BaseController;
use yii\data\ActiveDataProvider;
use common\components\Utility;

/**
 * AlbumAdminController implements the CRUD actions for Album model.
 */
class AlbumAdminController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * Lists all Album models.
     * @return mixed
     */
    public function actionIndex()
    {
        $category = Category::find()->select(['id', 'name'])->asArray()->all();
        $imgUrl = Yii::getAlias('@imgUrl');
        $adminUrl = \Yii::$app->params['adminUrl'];
        return $this->render('album.tpl', ['category'=>$category, 'imgUrl'=>$imgUrl, 'adminUrl'=>$adminUrl]);
    }

    /**
     * Displays a single Album model.
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
     * Creates a new Album model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Album();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Album model.
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
     * Deletes an existing Album model.
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
     * Finds the Album model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Album the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Album::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionData(){
    	$albumId = \Yii::$app->request->get('id', 0);
        $albumSid = \Yii::$app->request->get('sid', '');
        $desc = \Yii::$app->request->get('desc', 'desc');
        $keyword = \Yii::$app->request->get('keyword', '');
        $category = \Yii::$app->request->get('category', 0);
        $status = \Yii::$app->request->get('status', AlbumSearch::STATUS_ACTIVE);
        
        $query = AlbumSearch::find(['status'=>$status]);
        if(!empty($albumId)){
            $query->andWhere(['id'=>$albumId]);
        }
        if(!empty($albumSid)){
        	$query->andWhere(['id'=>Utility::id($albumSid)]);
        }
        if(!empty($keyword)){
            $keyword = trim($keyword);
        	$query->andWhere(['like', 'title', "%{$keyword}%", false]);
        }
        if(!empty($category)){
            $query->andWhere(['category'=>$category]);
        }
        
        return new ActiveDataProvider([
            'query' => $query->orderBy("create_time {$desc}"),
        ]);
    }
    
    public function actionCategoryData(){
    	$keyword = \Yii::$app->request->get('keyword', '');
    	$keyword = trim($keyword);
    	$query = Category::find()->where(['like', 'name', "%{$keyword}%", false]);
    	
    	return new ActiveDataProvider([
            'query' => $query->orderBy('id desc')->limit(10)
    	]);
    }
    
    public function actionAlbumUpdateIcon(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	$albumId = \Yii::$app->request->post('id');
    	$icon = \Yii::$app->request->post('icon');
    	$iconId = Utility::id($icon);
    	$album = AlbumSearch::findOne($albumId);
    	if(empty($album)){
    	    return ['status'=>-1, 'message'=>'分类不存在'];
    	}
    	$album->setAttributes(['icon'=>$iconId]);
    	if(!$album->save()){
    	    return ['status'=>-1, 'message'=>array_shift($album->getErrors())];
    	}
    	return ['status'=>0, 'message'=>''];
    }
    
    public function actionAlbumUpdate(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $albumId = \Yii::$app->request->post('id');
        $album = AlbumSearch::findOne($albumId);
        if(empty($album)){
            return ['status'=>-1, 'message'=>'分类不存在'];
        }
        $album->setAttributes(\yii::$app->request->post());
        if(!$album->save()){
            return ['status'=>-1, 'message'=>array_shift($album->getErrors())];
        }
        return ['status'=>0, 'message'=>''];
    }
    
    public function actionCategoryCreate(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	$model = new Category();
    	$model->setAttributes(\Yii::$app->request->post());
    	if(!$model->save()){
    	    return ['status'=>-1, 'message'=>array_shift($model->getErrors())];
    	}
    	
    	return ['status'=>0, 'message'=>'', 'data'=>['category'=>$model]];
    }
}
