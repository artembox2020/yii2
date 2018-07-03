<?php

namespace frontend\services\globals;
use Yii;
use common\models\User;
use yii\helpers\ArrayHelper;

class Entity
{
    const ZERO = 0;
    
    public $model; // instance of \common\models\User
    
    public function __construct($model) {
        
        $this->model = $model;
    }
    
    /**
     * @return Entity
     * @throws \yii\web\NotFoundHttpException
     */
    public static function findOne($id) {
        
        $user = User::findOne($id);
        
        if(empty($user) || empty($user->company)) {
            
            throw new \yii\web\NotFoundHttpException(Yii::t('common','Entity not found'));
        }
        
        return new Entity($user);
    }
    
    /**
     * Finds entity by id and name
     * @param $id
     * @param $name
     * @param $namePlural
     * @param $fullClassName
     * @return common\models\User
     * @throws \yii\web\ForbiddenHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function getEntity($id, $name, $namePlural = Entity::ZERO, $fullClassName = Entity::ZERO)
    {
        if(!$namePlural) $namePlural = $name."s";
        
        if(!$fullClassName) {
            $commonModels = ['user'];
            if(in_array($name, $commonModels)) $fullClassName = "\\common\\models\\".ucfirst($name);
            else $fullClassName = "\\frontend\\models\\".ucfirst($name);
        }
        
        switch($name) {
            case "otherContactPerson":
                $contactPerson = $fullClassName::findOne($id);
                    
                if(empty($contactPerson)) {
                    $verificationResult = Entity::ZERO;
                    break;
                }
                    
                $balanceHolderId = $contactPerson->balance_holder_id;
                $verificationResult = in_array($balanceHolderId, ArrayHelper::getColumn($this->model->company->balanceHolders, 'id'));
                break;
                    
            default:
                $verificationResult = in_array($id,ArrayHelper::getColumn($this->model->company->$namePlural,'id'));
                break;
        }
        
        if(!$verificationResult) {
            
            throw new \yii\web\ForbiddenHttpException(Yii::t('common','Access Forbidden or user not exist'));
        }
        
        $objectInstance = $fullClassName::findOne($id);
        
        if(empty($objectInstance)) {
            
            throw new \yii\web\NotFoundHttpException(Yii::t('common','Entity not found'));
        }
            
        return $objectInstance;
    }
    
}
