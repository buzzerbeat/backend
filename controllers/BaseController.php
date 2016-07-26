<?php

namespace backend\controllers;

use Yii;
use yii\web\Response;
class BaseController extends \yii\rest\Controller
{
    public $formatType = 'html';
    public function behaviors()
    {
        if(strstr($this->action->id, 'data')){
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
    
    public function actionIndex()
    {
        return $this->render('index');
    }

}
