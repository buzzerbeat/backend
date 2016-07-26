<?php

namespace backend\controllers;

use Yii;
use common\models\Image;
use backend\models\ImageSearch;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\Utility;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use backend\models\BackendImg;
use common\models\ImageForm;
use yii\web\UploadedFile;
use yii\data\Pagination;

/**
 * ImageAdminController implements the CRUD actions for Image model.
 */
class ImageAdminController extends Controller
{
    private $formatType = 'html';
    public $uploadImg;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        if($this->action->id != 'data'){
        	$this->formatType = 'html';
        }
        else{
            $this->formatType = 'json';
        }
        $behaviors = parent::behaviors();
        switch ($this->formatType){
        	default:
    	    case 'json' :
    	    case 'jsonp' :
    	        $formatType = Response::FORMAT_JSON;
    	        $behaviors['contentNegotiator']['formats'] = [];
    	        $behaviors['contentNegotiator']['formats']['application/json'] = $formatType;
    	        break;
    	    case 'xml' :
    	        $formatType = Response::FORMAT_XML;
    	        $behaviors['contentNegotiator']['formats'] = [];
    	        $behaviors['contentNegotiator']['formats']['application/xml'] = $formatType;
    	        break;
    	    case 'html' :
    	        $formatType = Response::FORMAT_HTML;
    	        $behaviors['contentNegotiator']['formats'] = [];
    	        $behaviors['contentNegotiator']['formats']['html/text'] = $formatType;
    	        break;
        }
        
        return $behaviors;
    }

    /**
     * Lists all Image models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ImageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'uploadImg' => new ImageForm(),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Image model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return Image::findOne($id);
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
    public function actionDelete()
    {
        $ar = BackendImg::findOne(\Yii::$app->request->post('id'));
        if(empty($ar)){
        	$ret = ['status'=>-1, 'message'=>BackendImg::STRING_IMAGE_NOT_EXIST];
        }
        else{
            $ar->setAttributes(
                ['status'=>BackendImg::STATUS_INACTIVE, 'update_time'=>time()]
            );
            if($ar->save()){
            	$ret = ['status'=>0, 'message'=>''];
            }
            else{
            	$ret = ['status'=>-1, 'message'=>array_shift($ar->getErrors())];
            }
        }
        
        return \yii\helpers\Json::encode($ret);
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
        if (($model = Image::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionList(){
        $statusMap = Image::STATUS_MAP;
        $imgUrl = Yii::getAlias('@imgUrl');
        $adminUrl = \Yii::$app->params['adminUrl'];
        return $this->render('list.tpl', ['statusMap'=>$statusMap, 'imgUrl'=>$imgUrl, 'adminUrl'=>$adminUrl]);
    }
    
    public function actionData(){
        $imgId = \Yii::$app->request->get('id', 0);
        $imgSid = \Yii::$app->request->get('sid', '');
        $desc = \Yii::$app->request->get('desc', 'desc');
        
        $model = BackendImg::find()->where(['status'=>Image::STATUS_ACTIVE]);
        if(!empty($imgId)){
            $model->andWhere(['id'=>$imgId]);
        }
        if(!empty($imgSid)){
            $model->andWhere(['id'=>Utility::id($imgSid)]);
        }
        $pagination = new Pagination([
            'defaultPageSize' => \Yii::$app->request->get('pre-page', 20),
            'totalCount' => $model->count(),
            'page' => \Yii::$app->request->get('page', 0),
        ]);
        
        $data = $model->orderBy("add_time {$desc}")
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return [
            'list' => $data,
            'total' => $pagination->totalCount,
        ];
        
        /* 
        $query = BackendImg::find();
        if(!empty($imgId)){
            $query->andWhere(['id'=>$imgId]);
        }
        if(!empty($imgSid)){
        	$query->andWhere(['id'=>Utility::id($imgSid)]);
        }
        $total = $query->count();
        $active = new ActiveDataProvider([
            'query' => $query->orderBy("add_time {$desc}"),
            'pagination' => [
                'pageSize' => \Yii::$app->request->get('pre-page', 20),
            ],
            'totalCount' => $total,
        ]);
        //$total = $active->getTotalCount();

        return $active; */
    }
    
    public function actionUpload()
    {
        $model = new ImageForm();
        if (Yii::$app->request->isPost) {
            //print_r($_FILES);exit;
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
            if($model->uploadMultiple($model->imageFiles)){
                $ret = ['status'=>0, 'message'=>'上传图片成功'];
            }
            else{
            	$ret = ['status'=>-1, 'message'=>array_shift($model->getErrors())];
            }
        }
        else{
        	$ret = ['status'=>-1, 'message'=>'非post请求'];
        }

        return \yii\helpers\Json::encode($ret);
    }
    
}
