<?php

namespace app\modules\payment\controllers;

use app\models\Orders;
use app\models\Transactions;
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
    //Public key LiqPay
    private const PUBLIC_KEY = 'sandbox_i70498452523';
    //Private key LiqPay
    private const PRIVATE_KEY = 'sandbox_KrR2Tix1luE0fvJMDCLglurQD4Aaup2rxqxLGPT4';
    //URL в Вашем магазине на который покупатель будет переадресован после завершения покупки. Максимальная длина 510 символов.
    private const RESULT_URL = 'http://molefirenko.pp.ua/payment/default/success';
    //URL API в Вашем магазине для уведомлений об изменении статуса платежа
    private const SERVER_URL = 'http://molefirenko.pp.ua/payment/default/callback';
    private const SIGN_FAIL = 'Signature fail';
    private const DATA_FAIL = 'Data not found';
    private const SUCCESS = 'Payment success';

    /**
     * Form for card refund
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $model = new DynamicModel(['card_no','amount']);

        $model
            ->addRule(['card_no', 'amount'],  'required')
            ->addRule(['card_no'], 'integer')
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
            $transaction->comment = self::SUCCESS;
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

        return $this->render('success', ['post' => $request->post()]);
    }

    protected function validateSign(string $data, string $signature): bool
    {
        $sign = base64_encode( sha1(
            self::PRIVATE_KEY .
            $data .
            self::PRIVATE_KEY
            , 1 ));

        if ($sign === $signature) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param DynamicModel $model
     * @return string
     */
    protected function createPaymentButton(DynamicModel $model, Orders $order): string
    {
        //https://www.liqpay.ua/documentation/ru/api/aquiring/checkout/doc
        $liqpay = new LiqPay(self::PUBLIC_KEY, self::PRIVATE_KEY);
        $payment_button = $liqpay->cnb_form(array(
            'action'         => 'pay',
            'amount'         => $model->amount,
            'currency'       => 'UAH',
            'description'    => Yii::t('payment','description'),
            'order_no'       => $order->order_uuid,
            'result_url'     => self::RESULT_URL,
            'server_url'     => self::SERVER_URL,
            'verifycode'     => 'Y',
            'paytypes'       => 'card',
            'version'        => '3'
        ));

        return $payment_button;
    }
}
