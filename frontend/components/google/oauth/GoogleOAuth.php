<?php

namespace frontend\components\google\oauth;

use Yii;
use yii\base\Component;
use yii\helpers\Html;
use frontend\components\OAuthBase;
use frontend\modules\account\models\dto\GoogleOAuthDto;

/**
 * Class GoogleOAuth
 * @package frontend\components\google\oauth
 */
class GoogleOAuth extends OAuthBase {

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        $this->clientId = env('GOOGLE_OAUTH_CLIENT_ID');
        $this->clientSecret = env('GOOGLE_OAUTH_CLIENT_SECRET');
        $this->redirectUri = Yii::$app->homeUrl.'/account/sign-in/sign-in-via-google';
        $this->authorizeUri = 'https://accounts.google.com/o/oauth2/auth';
        $this->authorizeTokenUri = 'https://accounts.google.com/o/oauth2/token';
        $this->userInfoUri = 'https://www.googleapis.com/oauth2/v1/userinfo';
    }

    /**
     * Generates link to service authorization page
     * 
     * @param string $caption
     * @param array $params
     * 
     * @return string
     */
    public function makeOauthLink($caption, $params = [])
    {
        $params = array(
            'redirect_uri'  => $this->redirectUri,
            'response_type' => 'code',
            'client_id'     => $this->clientId,
            'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'
        );

        $img = Html::img(Yii::$app->homeUrl."/static/img/google.png");
        $text = $img.' '.$caption;

        return parent::makeOAuthLink($text, $params);
    }

    /**
     * Finds user data
     *
     * @return GoogleOAuthDto|null
     */
    public function findUserData()
    {
        if (!isset($_GET['code'])) {

            return null;
        }

        $result = false;
        $params = array(
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri'  => $this->redirectUri,
            'grant_type'    => 'authorization_code',
            'code'          => $_GET['code']
        );

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->authorizeTokenUri);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        curl_close($curl);
        $tokenInfo = json_decode($result, true);

        if (empty($tokenInfo) || !isset($tokenInfo['access_token'])) {

            return null;
        }

        $params['access_token'] = $tokenInfo['access_token'];
        $contents = file_get_contents($this->userInfoUri . '?' . urldecode(http_build_query($params)));
        $userInfo = json_decode($contents, true);

        if (isset($userInfo['id'])) {

            return new GoogleOAuthDto($userInfo);
        }

        return null;
    }
}
