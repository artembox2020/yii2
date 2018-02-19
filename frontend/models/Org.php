<?php

namespace frontend\models;

use Yii;
use vova07\fileapi\behaviors\UploadBehavior;
use common\models\User;

/**
 * This is the model class for table "org".
 *
 * @property int $id
 * @property string $name_org
 */
class Org extends \yii\db\ActiveRecord
{

	/**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'uploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'logo_path' => [
                        'path' => '@storage/logos',
                        'tempPath' => '@storage/tmp',
                        'url' => Yii::getAlias('@storageUrl/logos'),
                    ],
                ],
            ],
        ];
    }
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'org';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_org'], 'string', 'max' => 100],
			[['logo_path'], 'string', 'max' => 255],
			[['desc'], 'string', 'max' => 200],
			[['user_id'], 'string', 'max' => 200],
			[['admin_id'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name_org' => 'Name Org',
			'logo_path' => 'Logo Company',
			'desc' => 'Description',
			'user_id' => 'Description',
			'admin_id' => 'Description',
        ];
    }
	
	public static function get_by_id($id){
		$query = Org::find()->select('*')->where("id = '" . $id . "'")->one();
		$query->delete();
        return $query;
    }
	
	public static function get_org_name($id){
		$query = Org::find()->select('*');
		$get = $query
				->where("`user_id` = '".$id."'")
				->one();
		return $get;
	
	}

	public static function get_org_user(){
		$query_org = Org::find()->select('*')->all();
		$get = User::find()->all($query_org->user_id);
		return $get;
	}
}
