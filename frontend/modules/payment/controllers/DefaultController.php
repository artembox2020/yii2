<?php

namespace app\modules\payment\controllers;

use frontend\models\CustomerCards;
use app\models\Orders;
use frontend\models\Transactions;
use Ramsey\Uuid\Uuid;
use yii\web\Controller;
use yii\base\DynamicModel;
use LiqPay;
use Yii;
/**
 * Default controller for the `payment` module
 */
class DefaultController extends Controller
{
    private const SIGN_FAIL = 'Signature fail';
    private const DATA_FAIL = 'Data not found';
    private const SUCCESS = 'Payment success';
    private const FAIL = 'Payment fail';

    private const FALSE = 0;
    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if ($action->id === 'callback') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    /**
     * Form for card refund
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $model = new DynamicModel(['card_no','amount']);

        $cards = CustomerCards::find(['is_deleted' => self::FALSE])->select('card_no')->asArray()->column();

        $model
            ->addRule(['card_no', 'amount'],  'required')
            ->addRule(['card_no'], 'in', ['range' => $cards])
            ->addRule(['amount'], 'number', ['min' => 0,'max' => 200]);

        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $order = new Orders();
            $uuid = Uuid::uuid4();
            $order->order_uuid = $uuid->toString();
            $order->card_no = $model->card_no;
            $order->amount = $model->amount;
            $order->status = Orders::STATUS_PENDING;
            $order->save();
            $button = $this->createPaymentButton($model, $order);
            return $this->render('confirm', ['payment_button' => $button]);
        }


        return $this->render('index', ['model' => $model]);
    }

    /**
     * Получение статуса и информации о платеже
     *
     */
    public function actionCallback()
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
     * Переход после успешного платежа
     * @return string
     */
    public function actionSuccess()
    {
        $request = Yii::$app->request;

        return $this->render('success');
    }

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
     * @param DynamicModel $model
     * @return string
     */
    protected function createPaymentButton(DynamicModel $model, Orders $order): string
    {
        //https://www.liqpay.ua/documentation/ru/api/aquiring/checkout/doc
        $liqpay = new LiqPay(env('PUBLIC_KEY'), env('PRIVATE_KEY'));
        $payment_button = $liqpay->cnb_form(array(
            'action'         => 'pay',
            'amount'         => $model->amount,
            'currency'       => 'UAH',
            'description'    => Yii::t('payment','description'),
            'order_id'       => $order->order_uuid,
            'result_url'     => env('RESULT_URL'),
            'server_url'     => env('SERVER_URL'),
            'verifycode'     => 'Y',
            'paytypes'       => 'card',
            'version'        => '3'
        ));

        return $payment_button;
    }
}
