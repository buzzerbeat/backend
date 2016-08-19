<?php

namespace backend\controllers\microvideo;

use Yii;
use common\models\Comment;
use backend\models\CommentSearch;
use backend\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use backend\models\MvVideoSearch;

/**
 * MvCommentController implements the CRUD actions for Comment model.
 */
class MvCommentAdminController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * Lists all Comment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Comment model.
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
     * Creates a new Comment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Comment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Comment model.
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
     * Deletes an existing Comment model.
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
     * Finds the Comment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Comment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionList(){
    	$vId = \yii::$app->request->get('vid', 0);
    	$title = !empty($vId) ? MvVideoSearch::findOne($vId)->title : '';
    	$imgUrl = Yii::getAlias('@imgUrl');
        return $this->render('list.tpl', ['vid'=>$vId, 'title'=>$title, 'imgUrl'=>$imgUrl]);
    }
    
    public function actionData(){
        $videoId = \Yii::$app->request->get('vid', 0);
        $keyword = \Yii::$app->request->get('keyword', '');
        $keyword = trim($keyword);
        $desc = \Yii::$app->request->get('desc', 'desc');
        $query = CommentSearch::find();
        
        $query->where(['status'=>CommentSearch::STATUS_ACTIVE]);
        
        if(!empty($videoId)){
            $query->andWhere(['item_id'=>$videoId, 'item_type'=>'microvideo/video']);
        }
        if(!empty($keyword)){
            $query->andWhere(['like', 'content', "%{$keyword}%", false]);
        }

        return new ActiveDataProvider([
            'query' => $query->orderBy("id {$desc}"),
            'pagination'=>[
                'pageSize' => \Yii::$app->request->get('per-page', 20),
            ]
        ]);
    }
    
    public function actionDelComment(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	$id = \Yii::$app->request->post('id');
    	
    	$model = CommentSearch::findOne($id);
    	
    	$model->setAttributes(['status'=>CommentSearch::STATUS_INACTIVE]);
    	if(!$model->save()){
    		$ret = ['status'=>-1, 'message'=>array_shift($model->getErrors())];
    	}
    	else{
    		$ret = ['status'=>0, 'message'=>''];
    	}
    	
    	return $ret;
    	
    }
}
