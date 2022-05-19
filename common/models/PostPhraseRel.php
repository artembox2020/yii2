<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "post_phrase_rel".
 *
 * @property int $id
 * @property int $post_id
 * @property int $phrase_id
 */
class PostPhraseRel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post_phrase_rel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['post_id', 'phrase_id'], 'required'],
            [['post_id', 'phrase_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'phrase_id' => 'Phrase ID',
        ];
    }

    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    public function getPhrase()
    {
        return $this->hasOne(Phrase::className(), ['id' => 'phrase_id']);
    }
}
