<?php

namespace frontend\components;

use Yii;
use yii\base\Component;
use frontend\models\CustomerCards;
use frontend\models\Transactions;
use frontend\models\UserBlacklist;
use yii\base\DynamicModel;
use Ramsey\Uuid\Uuid;
use frontend\models\Orders;
use LiqPay;

/**
 * Class MapBuilder
 * @package frontend\components
 */
class MapBuilder extends Component {

    const CARD_ACTIONS_EXTENDED_DESIGN = 1;
    const CARD_ACTIONS_SIMPLE_DESIGN = 2;

    const STATUS_PENDING_CONFIRMATION = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_ERROR = 3;

    private const SIGN_FAIL = 'Signature fail';
    private const DATA_FAIL = 'Data not found';
    private const SUCCESS = 'Payment success';
    private const FAIL = 'Payment fail';

    private const ZERO = 0;

    /**
     * Updates card data: status and balance from post array
     * and returns dynamic model with status and data info (card_no and amount)
     * 
     * @param array $post
     * @param frontend\models\CustomerCards $card
     * 
     * @return yii\base\DynamicModel
     */
    public function getUpdateMapDataModelFromPost($post, $card)
    {
        $model = $this->getModel($post);
        $model->status = self::STATUS_SUCCESS;

        if (!\Yii::$app->user->can('editCustomer', ['class'=>static::class])) {
            $model->status = self::STATUS_ERROR;

            return $model;
        }

        // update card status (block, unblock)
        if (!empty($post['to_block']) && !$card->updateStatus()) {
            $model->status = self::STATUS_ERROR;
        }

        // refill card
        if (!empty($post['to_refill']) && !empty($post['refill_amount'])) {
            if ($model->validate()) {

                $model->status = self::STATUS_PENDING_CONFIRMATION;
            } else {

                $model->status = self::STATUS_ERROR;
            }
        }

        return $model;
    }

    /** 
     * Gets flash message by operation status
     * 
     * @param int $status
     * 
     * @return string
     */
    public function getFlashMessageByStatus($status)
    {
        switch ($status) {
            case self::STATUS_SUCCESS:
            case self::STATUS_PENDING_CONFIRMATION:

                return 'Your request has been processed';
            case self::STATUS_ERROR:

                return 'Unable to update card data';
        }
    }

    /** 
     * Gets dynamic model by post card operations data
     * 
     * @param array $post
     * 
     * @return yii\base\DynamicModel
     */
    public function getModel($post)
    {
        $model = new DynamicModel(['card_no','amount', 'status']);
        $cards = CustomerCards::find()
            ->andWhere(['status' => CustomerCards::STATUS_ACTIVE])
            ->andWhere(['or', ['is_deleted' => self::ZERO], ['is', 'is_deleted', null]])
            ->select('card_no')
            ->asArray()
            ->column();
        $model
            ->addRule(['card_no', 'amount'],  'required')
            ->addRule(['card_no'], 'in', ['range' => $cards])
            ->addRule(
                ['amount'],
                'number',
                ['min' => Transactions::MIN_REFILL_AMOUNT,'max' => Transactions::MAX_REFILL_AMOUNT]
            );

        $model->card_no = $post['card_no'];

        if (!empty($post['refill_amount'])) {
            $model->amount = $post['refill_amount'];
        } else {
            $model->amount = self::ZERO;
        }

        return $model;
    }

    /** 
     * Creates order and payment button by dynamic model and callback urls($serverUrl, $resultUrl)
     * 
     * @param yii\base\DynamicModel $model
     * @param string $serverUrl
     * @param string $resultUrl
     * 
     * @return string
     */
    public function createOrderAndPaymentButton(DynamicModel $model, $serverUrl, $resultUrl)
    {
        $order = new Orders();
        $uuid = Uuid::uuid4();
        $order->order_uuid = $uuid->toString();
        $order->card_no = $model->card_no;
        $order->amount = $model->amount;
        $order->status = Orders::STATUS_PENDING;
        $order->save();

        return $this->createPaymentButton($model, $order, $serverUrl, $resultUrl);
    }

    /** 
     * Creates payment button by dynamic model, order and callback urls($serverUrl, $resultUrl)
     * 
     * @param yii\base\DynamicModel $model
     * @param app\models\Orders $order
     * @param string $serverUrl
     * @param string $resultUrl
     * 
     * @return string
     */
    public function createPaymentButton($model, $order, $serverUrl, $resultUrl)
    {
        //https://www.liqpay.ua/documentation/ru/api/aquiring/checkout/doc
        $liqpay = new LiqPay(env('PUBLIC_KEY'), env('PRIVATE_KEY'));
        $payment_button = $liqpay->cnb_form(array(
            'action'         => 'pay',
            'amount'         => $model->amount,
            'currency'       => 'UAH',
            'description'    => Yii::t('payment','description'),
            'order_id'       => $order->order_uuid,
            'result_url'     => $resultUrl,
            'server_url'     => $serverUrl,
            'verifycode'     => 'Y',
            'paytypes'       => 'card',
            'version'        => '3'
        ));

        return $payment_button;
    }

    /** 
     * Callback payment method, server_url request handler
     * Changes order status, inserts new transaction and updates card balance
     */
    public function paymentCallback()
    {
        $request = Yii::$app->request;

        $data = $request->post('data');
        $signature = $request->post('signature');

        $transaction = new Transactions();

        if (is_null($data) || is_null($signature)) {
            $transaction->comment = self::DATA_FAIL;
            $transaction->operation = $transaction::OPERATION_FAIL;
            $transaction->raw_data = $data;
            $transaction->save();

            return false;
        }

        if ($this->validateSign($data, $signature)) {

            $data_array = $this->decryptData($data);

            if ($data_array['status'] == 'success') {
                $transaction->comment = self::SUCCESS;

                $order_id = $data_array['order_id'];
                $create_date = $data_array['create_date'];
                $amount = (float)$data_array['amount'];
                //Get order
                $order = Orders::findOne(['order_uuid' => $order_id]);

                if ($order) {
                    $order->status = Orders::STATUS_SUCCESS;
                    //Get card number
                    $card_no = $order->card_no;
                    //If card exists then add ammount to card balance
                    $card = CustomerCards::findOne(['card_no' => $card_no]);
                    if ($card) {
                        $balance = (float)$card->balance;
                        $balance = $balance + $amount;
                        $card->balance = $balance;
                        $card->save();
                        $transaction->card_no = $card_no;
                        $transaction->amount = $amount;
                        $transaction->operation_time = $create_date;
                    } else {
                        $order->status = Orders::STATUS_NO_CARD;
                    }

                    //Update order status
                    $order->save();
                }

            } else {
                $transaction->comment = self::FAIL;
            }

            $transaction->operation = $transaction::OPERATION_INCOME;
            $transaction->raw_data = $data;
            $transaction->save();
        } else {
            $transaction->comment = self::SIGN_FAIL;
            $transaction->operation = $transaction::OPERATION_FAIL;
            $transaction->raw_data = $data;
            $transaction->save();
        }
    }

    /**
     * Validates signature
     * 
     * @param string $data
     * @param string $signature
     * 
     * @return bool
     */
    protected function validateSign(string $data, string $signature): bool
    {
        $sign = base64_encode( sha1(
            env('PRIVATE_KEY') .
            $data .
            env('PRIVATE_KEY')
            , 1 ));

        if ($sign == $signature) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Преобразовывает данные от платежной системы в массив
     * @param string $data
     * @return array
     */
    protected function decryptData(string $data): array
    {
        $json = base64_decode($data);

        return json_decode($json, true);
    }

    /**
     * Generates block/unblock button by user id and company id
     * 
     * @param int $userId
     * @param int $companyId
     * 
     * @return string
     */
    public function getBlockButtonByUser($userId, $companyId)
    {
        $action =  $this->getActionBlockByUser($userId, $companyId);

        $imgUrl = Yii::$app->homeUrl.'/static/img/'.$action.'.png';

        return Yii::$app->view->render(
            '/map/templates/block_button',
            [
                'action' => $action,
                'imgUrl' => $imgUrl,
                'userId' => $userId
            ]
        );
    }

    /**
     * Gets block action by user id and company id
     *
     * @param int $userId
     * @param int $companyId
     *
     * @return string
     */
    public function getActionBlockByUser($userId, $companyId)
    {
        $userBlacklist = new UserBlacklist();

        if ($userBlacklist->getUserItem($userId, $companyId)) {
            $action = 'Unblock';
        } else {
            $action = 'Block';
        }

        return $action;
    }
}