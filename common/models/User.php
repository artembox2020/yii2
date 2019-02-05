<?php

namespace common\models;

use developeruz\db_rbac\interfaces\UserRbacInterface;
use frontend\models\Company;
use frontend\services\globals\Entity;
use frontend\services\custom\Debugger;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\models\query\UserQuery;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $access_token
 * @property string $password_hash
 * @property string $email
 * @property integer $status
 * @property string $ip
 * @property int $company_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $action_at
 * @property boolean $is_deleted
 * @property integer $deleted_at
 * @property string $other
 * @property string $birthday
 * @property array $roles
 *
 * @property UserProfile $userProfile
 *
 * @property Company $company
 */
class User extends ActiveRecord implements IdentityInterface, UserRbacInterface
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BANNED = 2;
    const STATUS_DELETED = 3;

    public $roles;
   
    const ROLE_USER = 'user';
    const ROLE_FINANCIER = 'financier';
    const ROLE_TECHNICIAN = 'technician';
    const ROLE_MANAGER = 'manager';
    const ROLE_ADMINISTRATOR = 'administrator';

    const EVENT_AFTER_SIGNUP = 'afterSignup';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'is_deleted' => true,
                    'deleted_at' => time()
                ],
            ],
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'unique'],
            ['username', 'filter', 'filter' => '\yii\helpers\Html::encode'],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => array_keys(self::statuses())],
            ['ip', 'ip'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('common', 'Username'),
            'email' => Yii::t('common', 'Email'),
            'status' => Yii::t('common', 'Status'),
            'created_at' => Yii::t('common', 'Created at'),
            'updated_at' => Yii::t('common', 'Updated at'),
            'action_at' => Yii::t('common', 'Last action at'),
            'roles' => Yii::t('backend', 'Roles'),
            'company_id' => Yii::t('frontend', 'Company Id')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::className(), ['user_id' => 'id']);
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->username;
    }

    /**
     * @param $id
     * @return User|null
     * @throws \yii\web\ForbiddenHttpException
     */
    public function getUser($id) 
    {
        if (!in_array($id, ArrayHelper::getColumn($this->company->users, 'id'))) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('common','Access Forbidden or user not exist'));
        }

        return User::findOne($id);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username.
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password.
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model.
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key.
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new access token.
     */
    public function generateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString();
    }

    /**
     * Removes access token.
     */
    public function removeAccessToken()
    {
        $this->access_token = null;
    }

    /**
     * Returns user statuses list
     *
     * @param mixed $status
     * @return array|mixed
     */
    public static function statuses($status = null)
    {
        $statuses = [
            self::STATUS_INACTIVE => Yii::t('common', 'Inactive'),
            self::STATUS_ACTIVE => Yii::t('common', 'Active'),
            self::STATUS_BANNED => Yii::t('common', 'Banned'),
            self::STATUS_DELETED => Yii::t('common', 'Deleted'),
        ];

        if ($status === null) {
            return $statuses;
        }

        return $statuses[$status];
    }

    /**
     * Creates user profile and application event.
     *
     * @param array $profileData
     */
    public function afterSignup(array $profileData = [])
    {
        $profile = new UserProfile();
        $profile->load($profileData, '');
        $this->link('userProfile', $profile);

        // Default role
        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole(self::ROLE_USER), $this->getId());
    }

    /**
     * @return $this|\yii\db\ActiveQuery
     */
    public static function find()
    {
//        return new UserQuery(get_called_class());
        return parent::find()->where(['is_deleted' => false])
            ->andWhere(['status' => User::STATUS_ACTIVE]);
//            ->andWhere(['<', '{{%user}}.created_at', time()]);
    }

    /**
     * @param User $id
     * @return string|\yii\rbac\Role
     */
    public function getUserRoleName($id): string
    {
        if (!empty(Yii::$app->authManager->getRolesByUser($id))) {
            foreach (Yii::$app->authManager->getRolesByUser($id) as $role) {
                return $role->description;
            }
        }

        return $role = 'Role not defined';
    }
    
    /**
     * @param string $roleNames
     * @return integer
     */
    public function getUserCountByRoles($roleNames) {
        $entity = new Entity();
        $users = $entity->getUnitsPertainCompany(new User());
        $ids = ArrayHelper::getColumn($users, 'id');
        $authManager = Yii::$app->authManager;
        $userRoleIds = [];
        foreach($roleNames as $role)
        {
            $userIdsByRole = $authManager->getUserIdsByRole($role);
            $arr_diff = array_diff($userIdsByRole, $userRoleIds);
            $userRoleIds = array_merge($userRoleIds, $arr_diff);
        }
        $userIds = array_intersect($ids, $userRoleIds);
        
        return count($userIds);
    }
        
        
    /**
     * @return integer
     */
    public function getUserCount() {
        $entity = new Entity();
        $query = $entity->getUnitsQueryPertainCompany(new User());
                 
        return $query->count();
    }
    
    /**
     * @return integer
     */
    public function getUserOtherCount() {
        $countAll = $this->getUserCount();
        $countRoles = $this->getUserCountByRoles(
            [User::ROLE_FINANCIER, User::ROLE_TECHNICIAN, User::ROLE_MANAGER]
        );
                    
        return $countAll - $countRoles;
    }
}
