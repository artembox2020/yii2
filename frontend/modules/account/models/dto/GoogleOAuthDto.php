<?php

namespace frontend\modules\account\models\dto;

/**
 * Class GoogleOAuthDto
 * @package frontend\modules\account\models\dto
 */
class GoogleOAuthDto {
    public $id;
    public $email;
    public $verified_email;
    public $name;
    public $given_name;
    public $family_name;
    public $link;
    public $picture;
    public $locale;

    /**
     * @inheritdoc
     */
    public function __construct($data)
    {
        if (array_key_exists('id', $data)) {
            $this->id = (integer)$data['id'];
        }
        
        if (array_key_exists('email', $data)) {
            $this->email = (string)$data['email'];
        }
        
        if (array_key_exists('verified_email', $data)) {
            $this->verified_email = (boolean)$data['verified_email'];
        }
        
        if (array_key_exists('name', $data)) {
            $this->name = (string)$data['name'];
        }
        
        if (array_key_exists('given_name', $data)) {
            $this->given_name = (string)$data['given_name'];
        }
        
        if (array_key_exists('family_name', $data)) {
            $this->family_name = (string)$data['family_name'];
        }

        if (array_key_exists('link', $data)) {
            $this->link = (string)$data['link'];
        }

        if (array_key_exists('picture', $data)) {
            $this->picture = (string)$data['picture'];
        }
        
        if (array_key_exists('locale', $data)) {
            $this->locale = (string)$data['locale'];
        }
    }
}