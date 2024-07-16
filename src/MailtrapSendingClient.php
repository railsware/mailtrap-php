<?php

declare(strict_types=1);

namespace Mailtrap;

/**
 * @method  Api\Sending\Emails   emails()
 *
 * Class MailtrapSendingClient
 */
final class MailtrapSendingClient extends AbstractMailtrapClient implements EmailsSendMailtrapClientInterface
{
    public const API_MAPPING = [
        'emails' => Api\Sending\Emails::class,
    ];
}
