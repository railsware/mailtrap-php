<?php

declare(strict_types=1);

namespace Mailtrap;

use Mailtrap\Api;

/**
 * @method  Api\Sending\SendingAccount  accounts
 * @method  Api\Sending\SendingEmails   emails
 * @method  Api\Sending\SendingUser     users
 *
 * Class MailtrapClient
 */
class MailtrapSendingClient extends AbstractMailtrapClient
{
    private const API_MAPPING = [
        'accounts' => Api\Sending\SendingAccount::class,
        'emails' => Api\Sending\SendingEmails::class,
        'users' => Api\Sending\SendingUser::class,
    ];

    public function getApiClassByName(string $name): ?string
    {
        return !empty(self::API_MAPPING[$name]) ? self::API_MAPPING[$name] : null;
    }
}
