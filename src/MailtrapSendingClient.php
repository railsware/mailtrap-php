<?php

declare(strict_types=1);

namespace Mailtrap;

use Mailtrap\Api;

/**
 * @method  Api\Sending\SendingEmails   emails
 *
 * Class MailtrapSendingClient
 */
final class MailtrapSendingClient extends AbstractMailtrapClient
{
    public const API_MAPPING = [
        'emails' => Api\Sending\SendingEmails::class,
    ];
}
