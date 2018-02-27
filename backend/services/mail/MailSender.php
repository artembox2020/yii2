<?php

namespace backend\services\mail;

use backend\models\Company;
use common\models\User;
use Yii;

/**
 * Class MailSender
 * @package backend\services\mail
 */
class MailSender
{
    /**
     * @param User $user
     * @param Company $company
     * @param $password
     */
    public function sendInviteToCompany(
        User $user,
        Company $company,
        $password
    )
    {
        $textBody = [$password, $company->name];

        Yii::$app->mailer->compose('home-link', [
            'textBody' => $textBody,
        ])
            ->setFrom('sense.servers@gmail.com')
            ->setTo($user->email)
            ->setSubject('Hello, ' . $user->username . '!')
            ->setTextBody('')
            ->send();
    }
}
