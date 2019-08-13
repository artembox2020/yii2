<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use frontend\models\Imei;
use frontend\models\CustomerCards;
use frontend\models\Transactions;
use common\models\User;
use yii\helpers\Console;
use yii\helpers\ArrayHelper;

class MapController extends Controller
{
    const ZERO = NULL;
    const TEST_COMPANY_ID = 2;
    const CARD_NO_PRFX = '862';
    const MAX_BALANCE = 20000;
    const MAX_DISCOUNT = 25;
    const MIN_TRANSACTION_AMOUNT = 10;
    const MAX_TRANSACTION_AMOUNT = 1000;

    /**
     * Generates test `customer_cards` and `transactions` data 
     * 
     * @param int $limit
     */
    public function actionGenerateTestData($limit)
    {
        $availableUserIds = ArrayHelper::getColumn(
            User::find()->select(['id'])->andWhere(['company_id' => null])->all(),
            'id'
        );

        //$availableUserIds = Yii::$app->authManager->getUserIdsByRole(User::ROLE_CUSTOMER);

        $availableImeiIds = ArrayHelper::getColumn(
            Imei::find()->select(['id'])->andWhere(['company_id' => self::TEST_COMPANY_ID])->all(),
            'id'
        );

        $card = new CustomerCards();
        $transaction = new Transactions();

        for ($i = 0; $i < $limit; ++$i) {
            unset($card->id);
            $cardNo = self::CARD_NO_PRFX.rand(100000, 999999);
            $card->card_no = (int)$cardNo;
            $card->balance = rand(0, self::MAX_BALANCE);
            $card->discount = rand(0, self::MAX_DISCOUNT);
            $card->status = array_rand([CustomerCards::STATUS_INACTIVE, CustomerCards::STATUS_ACTIVE]);
            $card->user_id = $availableUserIds[rand(0, count($availableUserIds)-1)];
            $card->company_id = self::TEST_COMPANY_ID;
            $card->created_at = ($randCreated = rand(strtotime("-1 months"), time() - 3600*24));
            $card->isNewRecord = true;
            $card->save();

            for ($j = 0; $j < rand(1, $limit); ++$j) {
                unset($transaction->id);
                $transaction->card_no = $card->card_no;
                $transaction->imei = Imei::findOne($availableImeiIds[rand(0, count($availableImeiIds)-1)])->imei;
                $transaction->operation = array_rand(
                    [Transactions::OPERATION_PAYMENT, Transactions::OPERATION_INCOME, Transactions::OPERATION_FAIL]
                );
                $transaction->amount = rand(self::MIN_TRANSACTION_AMOUNT, self::MAX_TRANSACTION_AMOUNT);
                $transaction->isNewRecord = true;
                $transaction->save();

                $transaction->created_at = rand($randCreated, time());
                $transaction->update(false);
            }
        }
    }

    /**
     * Removes test generated data
     *
     */
    public function actionRemoveTestData()
    {
        $cardNos = ArrayHelper::getColumn(
            CustomerCards::find()->select(['card_no'])->andWhere(['company_id' => self::TEST_COMPANY_ID])->all(),
            'card_no'
        );

        CustomerCards::deleteAll(['card_no' => $cardNos]);
        Transactions::deleteAll(['card_no' => $cardNos]);
    }
}