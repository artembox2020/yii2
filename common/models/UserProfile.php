<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use vova07\fileapi\behaviors\UploadBehavior;

/**
 * This is the model class for table "{{%user_profile}}".
 *
 * @property integer $user_id
 * @property string $firstname
 * @property string $lastname
 * @property integer $birthday
 * @property string $avatar_path
 * @property integer $gender
 * @property string $other
 * @property string $position
 */
class UserProfile extends ActiveRecord
{
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    const NO_PHOTO_AVATAR_PATH = 'no-photo.png';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_profile}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'uploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'avatar_path' => [
                        'path' => '@storage/avatars',
                        'tempPath' => '@storage/tmp',
                        'url' => Yii::getAlias('@storageUrl/avatars'),
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['birthday', 'filter', 'filter' => 'strtotime', 'skipOnEmpty' => true],
            ['gender', 'in', 'range' => [null, self::GENDER_MALE, self::GENDER_FEMALE]],
            ['other', 'string', 'max' => 1024],
            ['position', 'string', 'max' => 255],
            [['firstname', 'lastname', 'avatar_path'], 'string', 'max' => 255],
            ['firstname', 'match', 'pattern' => '/^[a-zа-яёіїє]+$/iu'],
            ['lastname', 'match', 'pattern' => '/^[a-zа-яёіїє]+(-[a-zа-яё]+)?$/iu'],
            ['user_id', 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['firstname', 'lastname', 'birthday', 'gender', 'other'], 'default', 'value' => null],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'firstname' => Yii::t('common', 'Firstname'),
            'lastname' => Yii::t('common', 'Lastname'),
            'birthday' => Yii::t('common', 'Birthday'),
            'avatar_path' => Yii::t('common', 'Avatar'),
            'gender' => Yii::t('common', 'Gender'),
//            'website' => Yii::t('common', 'Website'),
            'other' => Yii::t('common', 'Other'),
            'position' => Yii::t('common', 'Position'),
        ];
    }

    /**
     * Gets path to avatar
     *
     * @return string
     */
    public function getAvatarPath()
    {

        return !empty($this->avatar_path) ? $this->avatar_path : self::NO_PHOTO_AVATAR_PATH;
    }
}
