<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "phrase_trans".
 *
 * @property int $id
 * @property int $phrase_id
 * @property int $trans
 * @property int $lang
 */
class PhraseTrans extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'phrase_trans';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phrase_id', 'trans', 'lang'], 'required'],
            [['phrase_id', 'trans', 'lang'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phrase_id' => 'Phrase ID',
            'trans' => 'Trans',
            'lang' => 'Lang',
        ];
    }

    public function getPhrase()
    {
        return $this->hasOne(Phrase::className(), ['id' => 'phrase_id']);
    }
}
