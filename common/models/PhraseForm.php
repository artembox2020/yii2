<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class PhraseForm extends Model
{
    public $phrase;
    public $lang;
    public $trans;
    public $transLang;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['phrase', 'lang', 'trans', 'transLang'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [

        ];
    }
}