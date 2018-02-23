<?php

namespace frontend\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "company".
 *
 * @property int $id
 * @property string $name
 * @property string $img
 * @property string $description
 * @property string $website
 * @property string $sub_admin
 *
 * @property User[] $users
 */
class Company extends \yii\db\ActiveRecord
{
    public $sub_admin;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['sub_admin'], 'string', 'max' => 100],
            [['img', 'website'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'name' => Yii::t('frontend', 'Name'),
            'img' => Yii::t('frontend', 'Img'),
            'description' => Yii::t('frontend', 'Description'),
            'website' => Yii::t('frontend', 'Website'),
            'sub_admin' => Yii::t('frontend', 'Sub Admin'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['company_id' => 'id']);
    }
}
