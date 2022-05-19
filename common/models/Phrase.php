<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "phrase".
 *
 * @property int $id
 * @property string $phrase
 * @property string $lang
 * @property string sid
 * @property string $created_at
 * @property string $updated_at
 * @property string|null $deleted_at
 */
class Phrase extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'phrase';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phrase', 'lang'], 'required', 'on' => 'DEFAULT'],
            [['phrase'], 'string', 'max' => 255],
            [['sid'], 'string', 'max' => 80],
            [['lang'], 'string', 'max' => 8],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phrase' => 'Phrase',
            'lang' => 'Lang',
        ];
    }

    public function scenarios()
    {
        return [
            'DEFAULT' => ['phrase', 'lang', 'sid', 'created_at', 'updated_at', 'deleted_at'],
            'SEARCH'  => ['phrase', 'lang', 'sid', 'created_at', 'updated_at', 'deleted_at'],
        ];
    }

    public function getPosts()
    {
        return $this
            ->hasMany(Post::className(), ['id' => 'post_id'])
            ->viaTable(PostPhraseRel::className(), ['post_id' => 'id']);

    }
}
