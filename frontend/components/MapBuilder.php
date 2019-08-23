<?php

namespace frontend\components;

use Yii;
use yii\base\Component;
use frontend\models\Transactions;

/**
 * Class MapBuilder
 * @package frontend\components
 */
class MapBuilder extends Component {

    const CARD_ACTIONS_EXTENDED_DESIGN = 1;
    const CARD_ACTIONS_SIMPLE_DESIGN = 2;

    /**
     * Updates card data: status and balance from post array
     * and returns flash message about operation status
     * 
     * @param array $post
     * @param frontend\models\CustomerCards $card
     * 
     * @return string
     */
    public function updateMapDataFromPost($post, $card)
    {
        // update card status (block, unblock)
        if (!empty($post['to_block'])) {
            $card->updateStatus();
        }

        // refill card
        if (!empty($post['to_refill']) && !empty($post['refill_amount'])) {
            $transaction = new Transactions();
            $refillStatus = $transaction->refillCard($card, (int)$post['refill_amount']);
            if ($refillStatus['status'] == Transactions::STATUS_ERROR) {
                $flashMessage = $refillStatus['description'];

                return $flashMessage;
            }
        }

        $flashMessage = 'Card data have been updated';

        return $flashMessage;
    }
}