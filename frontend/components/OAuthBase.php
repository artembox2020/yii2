<?php

namespace frontend\components;

use Yii;
use yii\base\Component;
use yii\helpers\Html;

/**
 * Class OAuthBase
 * @package frontend\components
 */
abstract class OAuthBase extends Component implements OAuthInterface {

    protected $clientId;
    protected $clientSecret;
    public $redirectUri;
    public $authorizeUri;
    public $authorizeTokenUri;
    public $userInfoUri;

    /**
     * Generates link to service authorization page
     * 
     * @param string $text
     * @param array $params
     * 
     * @return string
     */
    public function makeOAuthLink($text, $params)
    {
        $fullUri = $this->authorizeUri."?".urldecode(http_build_query($params));
        $options = [
            'class' => 'form-control form-inline',
            'target' => 'popup',
            'onclick' => "window.open('{$fullUri}','popup','width=600,height=600');"
        ];

        return Html::a($text, $fullUri, $options);  
    }

    /**
     * Finds user data
     *
     * @return Object|null
     */
    public function findUserData()
    {

        return null;
    }
}
