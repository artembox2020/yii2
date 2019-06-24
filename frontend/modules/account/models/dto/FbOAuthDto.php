<?php

namespace frontend\modules\account\models\dto;

/**
 * Class FbOAuthDto
 * @package frontend\modules\account\models\dto
 */
class FbOAuthDto {
    public $id;
    public $email;
    public $name;
    public $first_name;
    public $last_name;

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

        if (array_key_exists('name', $data)) {
            $this->name = (string)$data['name'];
        }

        if (array_key_exists('first_name', $data)) {
            $this->first_name = (string)$data['first_name'];
        }

        if (array_key_exists('last_name', $data)) {
            $this->last_name = (string)$data['last_name'];
        }
    }
}