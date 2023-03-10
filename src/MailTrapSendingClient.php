<?php

declare(strict_types=1);

namespace Mailtrap;

use Mailtrap\Api;

/**
 * @method  Api\Sending\SendingAccount  accounts
 * @method  Api\Sending\SendingEmails   emails
 *
 * Class MailTrapClient
 */
class MailTrapSendingClient extends AbstractMailTrapClient
{
    private const API_MAPPING = [
        'accounts' => Api\Sending\SendingAccount::class,
        'emails' => Api\Sending\SendingEmails::class,
    ];

    public function getApiClassByName(string $name): ?string
    {
        return !empty(self::API_MAPPING[$name]) ? self::API_MAPPING[$name] : null;
    }
}
