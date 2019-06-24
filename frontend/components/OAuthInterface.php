<?php

namespace frontend\components;

/**
 * Interface OAuthInterface
 * @package frontend\components
 */
interface OAuthInterface {

    /**
     * Generates link to service authorization page
     * 
     * @param string $text
     * @param array $params
     * 
     * @return string
     */
    public function makeOAuthLink($text, $params);

    /**
     * Finds user data
     *
     * @return Object|null
     */
    public function findUserData();
}