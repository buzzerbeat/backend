<?php

namespace backend\controllers\microvideo;

use Yii;
use microvideo\models\MvVideo;
use backend\models\MvVideoSearch;
use backend\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use backend\models\microvideo\MvVideoTagRel;
use backend\models\microvideo\MvTag;
use backend\models\microvideo\MvTagRel;
use backend\models\MvKeywordSearch;
use microvideo\models\MvVideoKeywordRel;
use common\components\Utility;
use backend\models\VideoSearch;

/**
 * MvVideoAdminController implements the CRUD actions for MvVideo model.
 */
class MvVideoAdminController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        
        return parent::behaviors();
    }
    
    public $serializer = ['class'=>'yii\rest\Serializer', 'collectionEnvelope'=>'items']; 

    /**
     * Lists all MvVideo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MvVideoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MvVideo model.
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
     * Creates a new MvVideo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MvVideo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MvVideo model.
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
     * Deletes an existing MvVideo model.
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
     * Finds the MvVideo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MvVideo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MvVideo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionList(){
        $statusMap = MvVideo::STATUS_MAP;
        $imgUrl = Yii::getAlias('@imgUrl');
        $tag = \Yii::$app->request->get('tag', 0);
        $adminUrl = Yii::$app->params['adminUrl'];
        return $this->render('list.tpl', ['statusMap'=>$statusMap, 'imgUrl'=>$imgUrl, 'tag'=>$tag, 'adminUrl'=>$adminUrl]);
    }
    
    public function actionData(){
        $videoId = \Yii::$app->request->get('id', 0);
        $keyword = \Yii::$app->request->get('keyword', '');
        $desc = \Yii::$app->request->get('desc', 'desc');
        $tag = \Yii::$app->request->get('tag', 0);
        $query = MvVideoSearch::find();
        if(!empty($videoId)){
            $query->andWhere(['`mv_video`.id'=>$videoId]);
        }
        if(!empty($keyword)){
            $query->andWhere(['like', '`mv_video`.title', "%{$keyword}%", false]);
        }
        if(!empty($tag)){
        	$query->leftJoin('`mv_video_tag_rel`', '`mv_video_tag_rel`.mv_video_id = `mv_video`.id')
        	   ->andWhere(["`mv_video_tag_rel`.mv_tag_id"=>$tag]);
        }
        return new ActiveDataProvider([
            'query' => $query->orderBy("id {$desc}"),
            'pagination'=>[
                'pageSize' => \Yii::$app->request->get('per-page', 20),
            ]
        ]);
    }
    
    public function actionKeyword(){
        return $this->render('keyword.tpl', []);
    }
    
    public function actionKeywordData(){
        $id = \Yii::$app->request->get('id', 0);
        $keyword = \Yii::$app->request->get('keyword', '');
        $desc = \Yii::$app->request->get('desc', 'desc');
        $query = MvKeywordSearch::find();
        if(!empty($keyword)){
        	$query->andWhere(['like', 'name', "%{$keyword}%", false]);
        }
        if(!empty($id)){
            $query->andWhere(['id'=>$id]);
        }
        
        return new ActiveDataProvider([
            'query' => $query->orderBy("rank {$desc}, id {$desc}"),
        ]);
    }
    
    public function actionSaveKeyword(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
    	$tagName = \Yii::$app->request->post('tag', '');
    	$keywordId = \Yii::$app->request->post('id', 0);
    	$keywordName = \Yii::$app->request->post('name');
    	$isFilter = \Yii::$app->request->post('is_filter');
    	if(!empty($tagName)){
        	$tagAr = MvTag::find()->where(['name'=>$tagName])->one();
        	if(empty($tagAr)){
        	    $tagAr = new MvTag();
        	    $tagAr->setAttributes(
                    ['name'=>$tagName]
        	    );
        	    if(!$tagAr->save()){
        	    	return ['status'=>-1, 'message'=>array_shift($tagAr->getErrors())];
        	    }
        	    
        	}
        	$tagId = $tagAr->id;
    	}
    	else{
    		$tagId = 0;
    	}
    	
    	if(empty($keywordId)){
    	    $keyword = new MvKeywordSearch();
    	}
    	else{
    		$keyword = MvKeywordSearch::findOne($keywordId);
    	}
    	$keyword->setAttributes(
            [
    	        'name'=>$keywordName,
    	        'is_filter'=>$isFilter,
    	        'tag_id'=>$tagId
            ]
    	);
    	if(!$keyword->save()){
    	    return ['status'=>-1, 'message'=>array_shift($keyword->getErrors())];
    	}
    	else{
    	    //将该keyword对应的video关联增加到tag
    	    if(!empty($keywordId) && !empty($tagId)){
    	        $videoRels = MvVideoKeywordRel::find()->select('video_id')->where(['keyword_id'=>$keywordId])->asArray()->all();
    	        foreach($videoRels as $videoId){
                    $tagRel = MvVideoTagRel::find()->where(['mv_video_id'=>$videoId['video_id'], 'mv_tag_id'=>$tagId])->one();
                    if(empty($tagRel)){
                        $tagRel = new MvVideoTagRel();
                        $tagRel->setAttributes(
                            ['mv_video_id'=>$videoId['album_id'], 'mv_tag_id'=>$tagId]
                        );
                        $tagRel->save();
                    }
    	        }
    	    }
    	    return ['status'=>0 , 'message'=>''];
    	}
    }
    
    public function actionKeywordFilter(){
        $keywordId = \Yii::$app->request->post('id');
        $isFilter = \Yii::$app->request->post('is_filter');
        $keyword = MvKeywordSearch::findOne($keywordId);
        $keyword->setAttributes(
            ['is_filter'=>$isFilter]
        );
        if($keyword->save()){
            $ret = ['status'=>0, 'message'=>''];
        }
        else{
            $ret = ['status'=>-1, 'message'=>array_shift($keyword->getErrors())];
        }
        
        return \yii\helpers\Json::encode($ret);
    }
    
    public function actionTag(){
    	return $this->render('tag.tpl',[]);
    }
    
    public function actionTagData(){
        $id = \Yii::$app->request->get('id', 0);
        $keyword = \Yii::$app->request->get('keyword', '');
        $desc = \Yii::$app->request->get('desc', 'desc');
        $query = MvTag::find();
        if(!empty($keyword)){
            $query->andWhere(['like', 'name', "%{$keyword}%", false]);
        }
        if(!empty($id)){
            $query->andWhere(['id'=>$id]);
        }
        
        $active = new ActiveDataProvider([
            'query' => $query->orderBy("count {$desc}"),
        ]);
        
        return $active;
    }
    
    public function actionTagSave(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $tagName = \Yii::$app->request->post('name', '');
        $tagId = \Yii::$app->request->post('id', 0);
         
        if(empty($tagId)){
            $tag = new MvTag();
        }
        else{
            $tag = MvTag::findOne($tagId);
        }
        $tag->setAttributes(
            [
                'name'=>$tagName,
            ]
        );
        if(!$tag->save()){
            return ['status'=>-1, 'message'=>array_shift($tag->getErrors())];
        }
        else{
            return ['status'=>0 , 'message'=>''];
        }
    }
    
    public function actionTagRelationData(){
    	$keyword = \yii::$app->request->get('keyword', '');
    	$keyword = trim($keyword);
    	$tagId = \yii::$app->request->get('tagid', 0);
    	$relation = MvTag::find()->where(['like', 'name', "%{$keyword}%", false])->andWhere(['<>', 'id', $tagId])->limit(10);
    	
    	return new ActiveDataProvider([
            'query' => $relation->orderBy('id desc')
    	]);
    }
    
    public function actionTagAddRel(){
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        }
        $relId = \yii::$app->request->post('rel_tag_id');
        $tagId = \yii::$app->request->post('tag_id');
        $relName = \yii::$app->request->post('rel_name');
        if(!empty($relId)){
            $relModel = new MvTagRel();
            $relModel->setAttributes(Yii::$app->request->post());
            if($relModel->save()){
                $ret = ['status'=>0, 'message'=>''];
        
            }
            else{
                $errors = $relModel->getErrors();
                return ['status'=>-1, 'message'=>array_shift($errors)];
            }
        }
        else{
            $name = trim($relName);
            $tagModel = MvTag::find()->where(['name'=>$name])->one();
            if(empty($tagModel)){
                $tagModel = new MvTag();
                $tagModel->setAttributes(
                    ['name' => $name]
                );
                if(!$tagModel->save()){
                    $errors = $tagModel->getErrors();
                    return ['status'=>-1, 'message'=>array_shift($errors)];
                }
            }
            $relModel = new MvTagRel();
            $relModel->setAttributes([
                'tag_id'=>$tagId,
                'rel_tag_id'=>$tagModel->id
            ]);
            if(!$relModel->save()){
                $errors = $relModel->getErrors();
                return ['status'=>-1, 'message'=>array_shift($errors)];
            }
            else{
                $ret = ['status'=>0, 'message'=>''];
            }
        }
        
        return $ret;
    }
    
    public function actionTagRemoveRel(){
        $relId = \yii::$app->request->post('id');
        $rel = MvTagRel::findOne($relId);
        if(!$rel->delete()){
            $errors = $rel->getErrors();
            return ['status'=>-1, 'message'=>array_shift($errors)];
        }
        else{
            $ret = ['status'=>0, 'message'=>''];
        }
        
        return \yii\helpers\Json::encode($ret);
    }
    
    public function actionTagDelete(){
    	$tagId = \yii::$app->request->post('id');
    	$tag = MvTag::findOne($tagId);
    	//删除关联的标签和视频，关键词
    	$relVideo = MvVideoTagRel::find()->where(['mv_tag_id'=>$tagId])->all();
    	foreach($relVideo as $rV){
    	    $rV->delete();
    	}
    	$relKeyword = MvKeywordSearch::find()->where(['tag_id'=>$tagId])->all();
    	foreach($relKeyword as $rK){
    	    $rK->delete();
    	}
    	$relTag = MvTagRel::find()->where("tag_id = {$tagId} or rel_tag_id = {$tagId}")->all();
    	foreach($relTag as $rT){
    		$rT->delete();
    	}
    	if(!$tag->delete()){
    	    $errors = $tag->getErrors();
    	    return ['status'=>-1, 'message'=>array_shift($errors)];
    	}
    	else{
    	    $ret = ['status'=>0, 'message'=>''];
    	}
    	
    	return \yii\helpers\Json::encode($ret);
    }
    
    public function actionVideoTagData(){
    	$keyword = \yii::$app->request->get('keyword');
    	$videoId = \yii::$app->request->get('videoid');
    	$keyword = trim($keyword);
    	$sql = "select t.* from mv_tag as t left join mv_video_tag_rel as r on t.id = r.mv_tag_id ";
    	$sql .= " where t.name like '%{$keyword}%' and (r.mv_video_id is null or r.mv_video_id != {$videoId})";
    	$tag = MvTag::findBySql($sql);
    	
    	return new ActiveDataProvider([
            'query'=>$tag
    	]);
    }
    
    public function actionVideoAddTag(){
        $videoId = \yii::$app->request->post('mv_video_id');
        $tagId = \yii::$app->request->post('mv_tag_id');
        $tagName = \yii::$app->request->post('tag_name', '');
        
        if(!empty($tagId)){
            $relModel = new MvVideoTagRel();
            $relModel->setAttributes(Yii::$app->request->post());
            if($relModel->save()){
                $ret = ['status'=>0, 'message'=>''];
            }
            else{
                $errors = $relModel->getErrors();
                return ['status'=>-1, 'message'=>array_shift($errors)];
            }
        }
        else{
            $tagName = trim($tagName);
            $tagModel = MvTag::find()->where(['name'=>$tagName])->one();
            if(empty($tagModel)){
                $tagModel = new MvTag();
                $tagModel->setAttributes(
                    ['name' => $tagName]
                );
                if(!$tagModel->save()){
                    $errors = $tagModel->getErrors();
                    return ['status'=>-1, 'message'=>array_shift($errors)];
                }
            }
            $relModel = new MvVideoTagRel();
            $relModel->setAttributes([
                'mv_tag_id'=>$tagModel->id,
                'mv_video_id'=>$videoId
            ]);
            if(!$relModel->save()){
                $errors = $relModel->getErrors();
                return ['status'=>-1, 'message'=>array_shift($errors)];
            }
            else{
                $ret = ['status'=>0, 'message'=>''];
            }
        }
        
        return \yii\helpers\Json::encode($ret);
        
    }
    
    public function actionVideoDelTag(){
    	$videoId = \Yii::$app->request->post('mv_video_id');
        $tagId = \Yii::$app->request->post('mv_tag_id');
        
        $rel = MvVideoTagRel::find()->where(['mv_video_id'=>$videoId, 'mv_tag_id'=>$tagId])->one();
        if(!$rel->delete()){
        	$ret = ['status'=>-1, 'message'=>array_shift($rel->getErrors())];
        }
        else{
        	$ret = ['status'=>0, 'message'=>''];
        }
        
        return \yii\helpers\Json::encode($ret);
    }
    
    public function actionVideoDelKeyword(){
        $videoId = \Yii::$app->request->post('video_id');
        $keywordSid = \Yii::$app->request->post('keyword_sid');
        $keywordId = Utility::id($keywordSid);
        
        $rel = MvVideoKeywordRel::find()->where(['video_id'=>$videoId, 'keyword_id'=>$keywordId])->one();
        if(!$rel->delete()){
            $ret = ['status'=>-1, 'message'=>array_shift($rel->getErrors())];
        }
        else{
            $ret = ['status'=>0, 'message'=>''];
        }
        
        return \yii\helpers\Json::encode($ret);
    }
    
    public function actionVideoUpdate(){
    	$id = \Yii::$app->request->post('id');
    	$model = MvVideoSearch::findOne($id);
    	
    	$model->setAttributes(Yii::$app->request->post());
    	if($model->save()){
    	    $ret = ['status'=>0, 'message'=>''];
    	}
    	else{
    	    $ret = ['status'=>-1, 'message'=>array_shift($model->getErrors())];
    	}
    	
    	return \yii\helpers\Json::encode($ret);
    }
    
    public function actionUpdateCoverImg(){
        $id = \Yii::$app->request->post('id');
        $cover = \Yii::$app->request->post('cover');
        $coverId = Utility::id($cover);
        try{
            if(empty($coverId)){
                throw new \Exception('不存在的封面图');
            }
            $mvModel = MvVideoSearch::findOne($id);
            if(empty($mvModel)){
                throw new \Exception('不存在的Mv视频');
            }
            $vModel = VideoSearch::findOne($mvModel->video_id);
            if(empty($vModel)){
            	throw new \Exception('不存在的视频');
            }
            $vModel->setAttributes(['cover_img'=>$coverId]);
            if(!$vModel->save()){
            	throw new \Exception(array_shift($vModel->getFirstErrors()));
            }
            
            $ret = ['status'=>0, 'message'=>''];
        }
        catch(\Exception $e){
            $ret = ['status'=>-1, 'message'=>$e->getMessage()];
        }
        
        return \yii\helpers\Json::encode($ret);
    	
    }
    
    
}
