<?php
namespace frontend\components\fb\oauth;

use Yii;
use yii\base\Component;
use frontend\components\OAuthBase;
use frontend\modules\account\models\dto\FbOAuthDto;

/**
 * Class FbOAuth
 * @package frontend\components\fb\oauth
 */
class FbOAuth extends OAuthBase {

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        $this->clientId = env('FB_OAUTH_CLIENT_ID');
        $this->clientSecret = env('FB_OAUTH_CLIENT_SECRET');
        $sslHomeUrl = str_replace("http:", "https:", Yii::$app->homeUrl);
        $this->redirectUri = $sslHomeUrl.'/account/sign-in/sign-in-via-fb';
        $this->authorizeUri = 'https://www.facebook.com/dialog/oauth';
        $this->authorizeTokenUri = 'https://graph.facebook.com/oauth/access_token';
        $this->userInfoUri = 'https://graph.facebook.com/me';
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
            'scope'         => 'email'
        );
        $img = "<img src=\"".Yii::$app->homeUrl."/static/img/fb.png\"/>";
        $text = $img.' '.$caption;

        return parent::makeOAuthLink($text, $params);
    }

    /**
     * Finds user data
     *
     * @return FbOAuthDto|null
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
            'code'          => $_GET['code']
        );

        $tokenInfo = null;
        $contents = file_get_contents($this->authorizeTokenUri . '?' . http_build_query($params));
        $tokenInfo = json_decode($contents, true);

        if (count($tokenInfo) > 0 && isset($tokenInfo['access_token'])) {
            $fieldsList = 'id,name,email,first_name,last_name';
            $params = [
                'access_token' => $tokenInfo['access_token'], 
                'fields' => $fieldsList
            ];
            $contents = file_get_contents($this->userInfoUri . '?' . urldecode(http_build_query($params)));
            $userInfo = json_decode($contents, true);

            if (isset($userInfo['id'])) {

                return new FbOAuthDto($userInfo);
            }
        }

        return null;
    }
}
