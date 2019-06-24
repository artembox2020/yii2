<?php

namespace api\modules\v1\models;

use \yii\db\ActiveRecord;

/**
 * Addresses Model
 */
class Addresses extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address_balance_holder';
    }

    /**
     * Define rules for validation
     */
    public function rules()
    {
        return [
            [['name']]
        ];
    }
}
