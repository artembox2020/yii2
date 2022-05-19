<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string $title
 * @property string $descr
 * @property string|null $text
 * @property string $published_at
 * @property string $created_at
 * @property string $updated_at
 * @property string|null $deleted_at
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'descr'], 'required'],
            [['descr', 'text', 'ref'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title', 'ref'], 'string', 'max' => 255],
            [['published_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'descr' => 'Descr',
            'text' => 'Text',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    public function beforeSave($insert)
    {
        if ( ! empty($this->published_at)) {
            $this->published_at = date("Y-m-d H:i:s", strtotime($this->published_at));
        }

        return parent::beforeSave($insert);
    }

    public function getPhrases()
    {
        return $this
            ->hasMany(Phrase::className(), ['id' => 'phrase_id'])
            ->viaTable(PostPhraseRel::className(), ['phrase_id' => 'id']);
    }
}
