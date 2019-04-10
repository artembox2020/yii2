<?php

namespace frontend\services\logger\src\Logger;

use frontend\services\custom\Debugger;
use Yii;

class Logger implements LoggerInterface
{
    public $company_id;
    public $type;
    public $name;
    public $number = [];
    public $event_row = [];
    public $new_state;
    public $old_state;
    public $address;
    public $who_is;
    public $created_at;
    public $updated_at;
    public $deleted_at;
    public $is_deleted;
    private $loggerDtoManager;

    public function __construct(LoggerDtoManagerInterface $loggerDtoManager)
    {
        $this->loggerDtoManager = $loggerDtoManager;
        $this->company_id = $loggerDtoManager->company_id;
        $this->type = $this->loggerDtoManager->type;
        $this->name = $this->loggerDtoManager->name;
        $this->event_row = $this->loggerDtoManager->event_row;
        $this->new_state = $this->loggerDtoManager->new_sate;
    }

    public function execute()
    {
        Yii::$app->db->createCommand()->insert('logger', [
            'company_id' => $this->company_id,
        ])->execute();
    }
}
