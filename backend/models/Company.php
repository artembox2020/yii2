<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "company".
 *
 * @property int $id
 * @property string $name
 * @property string $img
 * @property string $description
 * @property string $website
 * @property int $is_deleted
 * @property int $deleted_at
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
            [['deleted_at'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['img', 'website'], 'string', 'max' => 255],
            [['is_deleted'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'name' => Yii::t('backend', 'Name Company'),
            'img' => Yii::t('backend', 'Img'),
            'description' => Yii::t('backend', 'Description'),
            'website' => Yii::t('backend', 'Website'),
            'is_deleted' => Yii::t('backend', 'Is Deleted'),
            'deleted_at' => Yii::t('backend', 'Deleted At'),
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
