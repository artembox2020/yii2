<?php

namespace common\components\SmsGate\Native;

class Turbosms implements SmsGateInterface
{
    private $http_token;
    private $login;
    private $pass;
    private $client;
    private $send_url = 'https://api.turbosms.ua/message/send.json';
    private $status_url = 'https://api.turbosms.ua/message/status.json';

    public function __construct($httpToken, $login, $pass, $sender = 'MAGAZIN')
    {
        $this->http_token = $httpToken;
        $this->login = $login;
        $this->pass = $pass;
        $this->sender = $sender;
        echo 'Turbosms created';
    }

    public function send(array $to, string $text): array
    {
        $curl = curl_init();

        $data = http_build_query([
            "sms" => [
                'sender' => $this->sender,
                'text' => $text
            ],
            "recipients" => $to,
            "token" => $this->http_token
        ]);

        curl_setopt($curl, CURLOPT_URL, $this->send_url. '/?'. $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $response = json_decode(curl_exec($curl));
        curl_close($curl);

        $responseObj = [
            'response_code' => $response->response_code,
            'message_id' => $response->response_result[0]->message_id
        ];

        return $responseObj;
    }

    public function getStatus(array $messageIds): array
    {
        $curl = curl_init();

        $data = http_build_query(['messages' => $messageIds, "token" => $this->http_token]);

        curl_setopt($curl, CURLOPT_URL, $this->status_url. '/?'. $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($curl);
        curl_close($curl);

        return [$this->parseStatusCode($response)];
    }

    public function getBalance()
    {
        $client = $this->getAuth();
        //print_r($result);

        $balanceResult = trim($client->GetCreditBalance()->GetCreditBalanceResult);

        return (string)floatval($balanceResult) == (string)$balanceResult ? $balanceResult : false;
    }

    public function getAuth()
    {
        if ($this->client) {
            return $this->client;
        }

        $client = new \SoapClient('http://turbosms.in.ua/api/wsdl.html');
        $auth = ['login' => $this->login, 'password' => $this->pass];
        $client->Auth($auth);
        $this->client = $client;

        return $this->client;
    }

    public function isAuth()
    {
        return $this->getBalance() ? true : false;
    }
    
    private function parseStatusCode($response)
    {
        
        //return $response;
        $response = json_decode($response);

        return $response->response_result[0]->status;
    }
}
