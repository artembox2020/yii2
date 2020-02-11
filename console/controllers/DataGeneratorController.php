<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use frontend\storages\AddressStatStorage;
use frontend\components\data\packets\Init;
use frontend\components\data\packets\Encashment;
use frontend\components\data\packets\State;
use frontend\components\data\packets\CbLog;
use frontend\components\data\packets\WmLog;
use frontend\components\data\packets\Command;

/**
 * Class DataGeneratorController
 * @package console\controllers
 */
class DataGeneratorController extends Controller
{
    /**
     * Generates packets from database
     *
     * @param int $numberOfLines
     */
    public function actionGeneratePackets(int $numberOfLines): void
    {
        echo 'Packages are being generated, please wait..';
        ob_start();
        $init = new Init();
        $encashment = new Encashment();
        $state = new State();
        $cbLog = new CbLog();
        $wmLog = new WmLog();
        $command = new Command();

        $lines =
            $init->getLastLinesAsJson($numberOfLines) +
            $encashment->getLastLinesAsJson($numberOfLines) +
            $state->getLastLinesAsJson($numberOfLines) +
            $cbLog->getLastLinesAsJson($numberOfLines) +
            $wmLog->getLastLinesAsJson($numberOfLines) +
            $command->getLastLinesAsJson($numberOfLines);

        ksort($lines);

        $this->printLines(array_reverse($lines, true));

        $content = ob_get_clean();

        $fileName = 'dump/packets-'.date("Y-m-d H:i:s").'.dump';

        $fp = fopen($fileName, 'w+');
        fwrite($fp, $content);
        fclose($fp);
        echo "\nDone! Packages have been written into '{$fileName}'\n";
    }

    /**
     * Prints lines like {timestamp}:{body} format
     * 
     * @param array $lines
     */
    public function printLines(array $lines): void
    {
        foreach ($lines as $key => $line) {
            echo $key.':'.$line."\n";
        }
    }
}