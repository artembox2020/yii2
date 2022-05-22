<?php

namespace common\components\SmsGate\Native;

interface SmsGateInterface
{
    public function send(array $to, string $text): array;
    public function getStatus(array $messageIds): array;
    public function getBalance();
}