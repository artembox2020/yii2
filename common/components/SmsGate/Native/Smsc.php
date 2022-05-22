<?php

class Smsc
{
    private $login;
    private $psw;
    private $send_url = 'https://smsc.ru/sys/send.php'; 

    public function __construct($login, $psw)
    {
        $this->login = $login;
        $this->psw = $psw;
    }
    
    public function send($to, $text)
    {
        $phoneDelimiter = ',';

        $curl = curl_init();
        
        $data = http_build_query([
            'login' => $this->login,
            'psw' => $this->psw,
            'phones' => implode($phoneDelimiter, $to),
            'mes' => urlencode($text)
        ]);
        
        curl_setopt($curl, CURLOPT_URL, $this->send_url. '/?'. $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}
