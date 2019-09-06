<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use frontend\models\Company;
use frontend\models\Imei;
use frontend\models\CustomerCards;
use frontend\models\Transactions;
use common\models\User;
use yii\helpers\Console;
use yii\helpers\ArrayHelper;

class MapController extends Controller
{
    const ZERO = NULL;
    const CARD_NO_PRFX = '862';
    const MAX_BALANCE = 20000;
    const MAX_DISCOUNT = 25;
    const MIN_TRANSACTION_AMOUNT = 10;
    const MAX_TRANSACTION_AMOUNT = 1000;

    /**
     * Generates test `customer_cards` and `transactions` data 
     * 
     * @param int $testCompanyId
     * @param int $limit
     * 
     * @return int 
     */
    public function actionGenerateTestData($testCompanyId, $limit)
    {
        if ((int)$limit == 0) {
            echo 'Parameter "'.$limit.'" is not valid integer value'.PHP_EOL;

            return parent::EXIT_CODE_NORMAL;
        }

        if (empty(Company::findOne($testCompanyId))) {
            echo 'Company with index "'.$testCompanyId.'" not found'.PHP_EOL;

            return parent::EXIT_CODE_NORMAL;
        }

        $availableUserIds = ArrayHelper::getColumn(
            User::find()->select(['id'])->all(),
            'id'
        );

        //$availableUserIds = Yii::$app->authManager->getUserIdsByRole(User::ROLE_CUSTOMER);

        $availableImeiIds = ArrayHelper::getColumn(
            Imei::find()->select(['id'])->andWhere(['company_id' => $testCompanyId])->all(),
            'id'
        );

        $card = new CustomerCards();
        $transaction = new Transactions();

        $result = $this->prompt('Start generating test data Y\N?');

        if (in_array($result, ['y', 'Y'])) {

            Console::startProgress(0, $limit);

            for ($i = 0; $i < $limit; ++$i) {
                unset($card->id);
                $cardNo = self::CARD_NO_PRFX.rand(100000, 999999);
                $card->card_no = (int)$cardNo;
                $card->balance = rand(0, self::MAX_BALANCE);
                $card->discount = rand(0, self::MAX_DISCOUNT);
                $card->status = array_rand([CustomerCards::STATUS_INACTIVE, CustomerCards::STATUS_ACTIVE]);
                $card->user_id = rand(0, 2) == 1 ? $availableUserIds[rand(0, count($availableUserIds)-1)] : null;
                $card->company_id = $testCompanyId;
                $card->created_at = ($randCreated = rand(strtotime("-1 months"), time() - 3600*24));
                $card->is_deleted = 0;
                $card->isNewRecord = true;
                $card->save();

                for ($j = 0; $j < rand(1, $limit); ++$j) {
                    unset($transaction->id);
                    $transaction->card_no = $card->card_no;
                    $transaction->imei = Imei::findOne($availableImeiIds[rand(0, count($availableImeiIds)-1)])->imei;
                    $transaction->operation = array_rand(
                        [Transactions::OPERATION_PAYMENT, Transactions::OPERATION_INCOME, Transactions::OPERATION_FAIL]
                    );

                    if ($transaction->operation != Transactions::OPERATION_FAIL) {
                        $transaction->amount = rand(self::MIN_TRANSACTION_AMOUNT, self::MAX_TRANSACTION_AMOUNT);
                    } else {
                        $transaction->amount = null;
                    }

                    $transaction->isNewRecord = true;
                    $transaction->save();

                    $transaction->created_at = rand($randCreated, time());
                    $transaction->update(false);
                }
                Console::updateProgress($i+1, $limit);
            }

            Console::endProgress('Done!'.PHP_EOL);
        } elseif (in_array($result, ['n', 'N'])) {
            echo 'Operation cancelled'.PHP_EOL;
        } else {
            echo 'Unknown response "'.$result.'"'.PHP_EOL;
        }

        return parent::EXIT_CODE_NORMAL;
    }

    /**
     * Removes test generated data
     * 
     * @param int $testCompanyId
     * 
     * @return int
     */
    public function actionRemoveTestData($testCompanyId)
    {
        if (empty(Company::findOne($testCompanyId))) {
            echo 'Company with index "'.$testCompanyId.'" not found'.PHP_EOL;

            return parent::EXIT_CODE_NORMAL;
        }

        $result = $this->prompt('Remove permanently test data Y\N?');

        if (in_array($result, ['y', 'Y'])) {

            Console::startProgress(0, 100);

            $cardNos = ArrayHelper::getColumn(
                CustomerCards::find()->select(['card_no'])->andWhere(['company_id' => $testCompanyId])->all(),
                'card_no'
            );

            CustomerCards::deleteAll(['card_no' => $cardNos]);
            Transactions::deleteAll(['card_no' => $cardNos]);

            Console::endProgress('Done!'.PHP_EOL);
        } elseif (in_array($result, ['n', 'N'])) {
            echo 'Operation cancelled'.PHP_EOL;
        }  else {
            echo 'Unknown response "'.$result.'"'.PHP_EOL;
        }

        return parent::EXIT_CODE_NORMAL;
    }
}