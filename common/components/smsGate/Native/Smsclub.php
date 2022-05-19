<?php

class Smsclub
{
    private $http_token;
    private $login;
    private $pass;
    private $send_url = 'https://im.smsclub.mobi/sms/send';
    private $balance_url = 'https://im.smsclub.mobi/sms/balance';

    public function __construct($httpToken, $login, $pass)
    {
        $this->http_token = $httpToken;
        $this->login = $login;
        $this->pass = $pass;
    }
    
    public function send($to, $text)
    {
        $curl = curl_init();

        $data = [
            'username' => $this->login,
            'token' => $this->http_token,
            'from' => 'Shop Zakaz',
            'to' => implode(';', $to),
            'text' => $text
        ];
        
        $url = "https://gate.smsclub.mobi/token";

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url. '/?'. http_build_query($data));
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;  
    }

    public function getStatus($messageId)
    {
        $curl = curl_init();

        $data = [
            'username' => $this->login,
            'token' => $this->pass,
            'smscid' => $messageId
        ];

        $url = "https://gate.smsclub.mobi/token/state.php";

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url. '/?'. http_build_query($data));
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    public function getBalance()
    {
        $curl = curl_init();
        $url = "https://gate.smsclub.mobi/token/getbalance.php?username=". $this->login. "&token=". $this->http_token;

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;        
    }
}