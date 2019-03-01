<?php

namespace frontend\services\error\db_connection;

use frontend\services\custom\Debugger;
use frontend\services\error\db_connection\services\MessageDbError;
use Yii;
use yii\web\ErrorAction;
use yii\db\Exception;

/**
 * Class DbError
 * @package frontend\services\error\db_connection
 */
class DbError extends ErrorAction
{
    /**
     * create message "Data Base Connection" && find & save event to file (json format) (36 string)
     */
    public function init()
    {
//        if ($this->defaultMessage) {
            try {
                Yii::$app->db->open();
            } catch (Exception $e) {
                $action = new MessageDbError();
                $action->actionRun($this->defaultMessage);
            }
//        }

        $this->exception = $this->findException();

        if ($this->defaultMessage === null) {
            $this->defaultMessage = Yii::t('yii', 'An internal server error occurred.');
        }

        if ($this->defaultName === null) {
            $this->defaultName = Yii::t('yii', 'Error');
        }
    }
}
