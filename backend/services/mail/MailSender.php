<?php

namespace backend\services\mail;

use frontend\models\Company;
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
            ->setFrom(env('ADMIN_EMAIL'))
            ->setTo($user->email)
            ->setSubject('Hello, ' . $user->username . '!')
            ->setTextBody('')
            ->send();
    }
}
