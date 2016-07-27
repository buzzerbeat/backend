<?php

namespace backend\controllers;

use Yii;
use common\models\Image;
use backend\models\ImageSearch;
use backend\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\Utility;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use common\models\ImageForm;
use yii\web\UploadedFile;
use yii\data\Pagination;
use app\models\ImgSearch;

/**
 * ImageAdminController implements the CRUD actions for Image model.
 */
class ImageAdminController extends BaseController
{
    public $uploadImg;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors();
    }
    
    public $serializer = ['class'=>'yii\rest\Serializer', 'collectionEnvelope'=>'items'];

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
        $ar = ImageSearch::findOne(\Yii::$app->request->post('id'));
        if(empty($ar)){
        	$ret = ['status'=>-1, 'message'=>ImageSearch::STRING_IMAGE_NOT_EXIST];
        }
        else{
            $ar->setAttributes(
                ['status'=>ImageSearch::STATUS_INACTIVE, 'update_time'=>time()]
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
        
        $query = ImageSearch::find(['status'=>Image::STATUS_ACTIVE]);
        if(!empty($imgId)){
            $query->andWhere(['id'=>$imgId]);
        }
        if(!empty($imgSid)){
        	$query->andWhere(['id'=>Utility::id($imgSid)]);
        }
        $total = $query->count();
        $active = new ActiveDataProvider([
            'query' => $query->orderBy("add_time {$desc}"),
        ]);

        return $active;
    }
    
    public function actionUpload()
    {
        $model = new ImageForm();
        if (Yii::$app->request->isPost) {
            //print_r($_FILES);exit;
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
            $uploadImg = $model->uploadMultiple($model->imageFiles);
            if(!$uploadImg){
                $ret = ['status'=>-1, 'message'=>array_shift($model->getErrors())];
            }
            else{
            	
            	$ret = ['status'=>0, 'message'=>'上传图片成功', 'data'=>['imgs'=>$uploadImg]];
            }
        }
        else{
        	$ret = ['status'=>-1, 'message'=>'非post请求'];
        }

        return \yii\helpers\Json::encode($ret);
    }
    
}
