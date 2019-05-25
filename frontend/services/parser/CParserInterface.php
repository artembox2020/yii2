<?php

namespace frontend\services\parser;

use yii\di\Instance;

/**
 * Interface CParserInterface
 * @package frontend\services\parser
 */
interface CParserInterface
{
    /**
     * Parsers the packet type data of TYPE_PACKET_INITIALIZATION
     * 
     * @param $p
     * @return array
     */
    public function iParse($p);
    
    /**
     * Parsers the packet type data of TYPE_PACKET_DATA
     * 
     * @param $p
     * @return array
     */
    public function dParse($p);
}
