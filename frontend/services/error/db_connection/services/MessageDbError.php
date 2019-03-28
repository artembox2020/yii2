<?php

namespace frontend\services\error\db_connection\services;

use DateTime;
use Yii;
use yii\helpers\Json;

/**
 * Class MessageDbError
 * @package frontend\services\error\db_connection\services
 */
class MessageDbError
{
    const HOUR = 1;
    const FORMAT = 'Y-m-d H:i:s';
    const FILE = '../services/error/db_connection/file/data.json';

    public $mail = [
        'Serhii' => 'monstrpro@gmail.com',
        'Dmitro' => 'dmytro.v.kovtun@gmail.com',
        'Sasha' => 'sashabardash@gmail.com',
        'Info' => 'info@postirayka.com.ua'
    ];


    /**
     * Основной метод
     * Сработает при исключении (ошибки) базы данных
     * Запишет в файл дату ошибки и отправит письмо с кодом ($message) ошибки
     * если разница между ошибками больше одного часа.
     *
     * @param $message
     * @throws \Exception
     */
    public function actionRun($message): void
    {
        if ($this->diffHour(date(self::FORMAT), $this->getDateFromFile()) > self::HOUR) {
            $this->save();
            $this->senderMessages($this->mail, $message);
        }
    }

    /**
     * Добавит в массив current дату ошибки db и сохранит в файл
     */
    public function save(): void
    {
        $dateArray = Json::decode(file_get_contents(self::FILE), $asArray = true);
        $dateArray[] = ['date' => date(self::FORMAT)];
        file_put_contents(self::FILE, Json::encode($dateArray));

        unset($dateArray);
    }

    /**
     * Вернёт последнюю дату ошибки из файла data.json
     * Если массива нет (пустой файл) создаст массив и запишет дату первой ошибки
     *
     * @return string|$this->save
     */
    public function getDateFromFile(): string
    {
        if (!$this->getArrayDate()) {
            $this->save();
        }

        $array = $this->getArrayDate();
        $value = array_pop($array);

        return $value->date;
    }

    /**
     * Вернёт разницу между двумя датами, число - в часах
     *
     * @param $current_time
     * @param $time_from_file
     * @return int
     * @throws \Exception
     */
    public function diffHour(string $current_time, string $time_from_file): int
    {
        $currentDate = new DateTime($current_time);
        $difference = $currentDate->diff(new DateTime($time_from_file))->h;

        return $difference;
    }

    /**
     * Вернёт массив дат (дата "ошибки" исключения базы данных)
     * @return array
     */
    public function getArrayDate(): ?array
    {
        return json_decode(file_get_contents(self::FILE));
    }

    /**
     * @param $mail
     * @param $name
     * @param $message
     */
    public function pushMessage($mail, $name, $message): void
    {
        Yii::$app->mailer->compose('db-error', [
            'textBody' => $message,
        ])
            ->setFrom(env('ADMIN_EMAIL'))
            ->setTo($mail)
            ->setSubject('Hello, ' . $name . '!')
            ->setTextBody('')
            ->send();
    }

    /**
     * @param $mails
     * @param $message
     */
    public function senderMessages(array $mails, string $message): void
    {
        foreach ($mails as $name => $email) {
            $this->pushMessage($email, $name, $message);
        }
    }
}
