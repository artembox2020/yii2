<?php

namespace frontend\models;

use common\models\User;
use Yii;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "company".
 *
 * @property int $id
 * @property string $name
 * @property string $img
 * @property string $description
 * @property string $website
 * @property string $sub_admin
 * @property bool $is_deleted
 * @property integer $deleted_at
 * @property string $address
 * @property User[] $users
 *
 * @property BalanceHolder $balanceHolders
 */
class Company extends ActiveRecord
{
    /**
     * Relations with User table
     * @var integer $sub_admin
     */
    public $sub_admin;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
    }

    public function behaviors()
    {
        return [
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'is_deleted' => true,
                    'deleted_at' => time() + Jlog::TYPE_TIME_OFFSET
                ],
            ],
            'uploadBehavior' => [
                'class' => \frontend\services\company\UploadBehavior::className(),
                'attributes' => [
                    'img' => [
                        'path' => '@storage/logos',
                        'tempPath' => '@storage/tmp',
                        'url' => Yii::getAlias('@storageUrl/logos'),
                    ],
                ],
            ],
            [
                'class' => TimestampBehavior::className(),
                'value' => time() + Jlog::TYPE_TIME_OFFSET
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'address'], 'string'],
            [['name', 'sub_admin'], 'string', 'max' => 100],
            [['img', 'website'], 'string', 'max' => 255],
            ['website', 'url', 'defaultScheme' => 'http', 'validSchemes' => ['http', 'https']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'name' => Yii::t('frontend', 'Name Company'),
            'img' => Yii::t('frontend', 'Img'),
            'description' => Yii::t('frontend', 'Description'),
            'website' => Yii::t('frontend', 'Website'),
            'sub_admin' => Yii::t('frontend', 'Sub Admin'),
            'address' => Yii::t('frontend', 'Address'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function find()
    {
        return parent::find()->where(['is_deleted' => false]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBalanceHolders()
    {
        return $this->hasMany(BalanceHolder::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(AddressBalanceHolder::className(), ['company_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImeis()
    {
        return $this->hasMany(Imei::className(), ['company_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWmMashines()
    {
        return $this->hasMany(WmMashine::className(), ['company_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGdMashines()
    {
        return $this->hasMany(GdMashine::className(), ['company_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountBalanceHolder()
    {
        return $this->getBalanceHolders()->count();
    }
    
    /**
     * @return integer
     */
    public function getCountAddress() {
        
        return $this->getAddresses()->count(); 
    }
    
    /**
     * @return integer
     */
    public function getCountImei()
    {
        return $this->getImeis()->count();
    }
    
    /**
     * @return integer
     */
    public function getCountWmMashine() {
        
        return $this->getWmMashines()->count(); 
    }
    
    /**
     * @return integer
     */
    public function getCountGdMashine() {
        
        return $this->getGdMashines()->count(); 
    }
}
