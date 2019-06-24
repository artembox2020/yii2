<?php

namespace common\models;

use Yii;
use yii\base\Model;
use frontend\modules\account\models\SignupForm;

/**
 * Login form.
 */
class LoginForm extends Model
{
    public $identity;
    public $password;
    public $rememberMe = true;

    private $user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['identity', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'identity'=>Yii::t('common', 'Username or email'),
            'password'=>Yii::t('common', 'Password'),
            'rememberMe'=>Yii::t('common', 'Remember me'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array  $params    the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * Finds user by [[username]] or [[email]].
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->user === null) {
            $this->user = User::find()->andWhere(['and', ['or', ['username' => $this->identity], ['email' => $this->identity]]])->one();
        }

        return $this->user;
    }

    /**
     * Loads user identity from google account
     * 
     * @param frontend\modules\account\models\dto\GoogleOAuthDto $userData
     * 
     * @return bool
     */
    public function loadFromGoogleOAuth($userData)
    {
        if (empty($userData) || empty($userData->verified_email)) {

            return false;
        }

        $this->identity = $userData->email;

        return true;
    }

    /**
     * Loads user identity from facebook account
     * 
     * @param frontend\modules\account\models\dto\FbOAuthDto $userData
     * 
     * @return bool
     */
    public function loadFromFbOAuth($userData)
    {
        if (empty($userData) || empty($userData->email)) {

            return false;
        }

        $this->identity = $userData->email;

        return true;
    }

    /**
     * Log in user identity from google account
     * 
     * @param frontend\modules\account\models\dto\GoogleOAuthDto $userData
     * 
     * @return bool
     */
    public function loginViaGoogle($userData)
    {
        if (!$this->loadFromGoogleOAuth($userData)) {

            return false;
        }

        if (empty($user=$this->getUser())) {
            $signup = new SignupForm();
            $user = $signup->signupFromGoogle($userData);
        }

        return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
    }

    /**
     * Log in user identity from facebook account
     * 
     * @param frontend\modules\account\models\dto\FbOAuthDto $userData
     * 
     * @return bool
     */
    public function loginViaFb($userData)
    {
        if (!$this->loadFromFbOAuth($userData)) {

            return false;
        }

        if (empty($user=$this->getUser())) {
            $signup = new SignupForm();
            $user = $signup->signupFromFb($userData);
        }

        return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
    }
}
