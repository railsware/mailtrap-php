<?php

declare(strict_types=1);

namespace Mailtrap;

use Mailtrap\Api;

/**
 * @method  Api\BulkSending\Emails   emails
 *
 * Class MailtrapBulkSendingClient
 */
final class MailtrapBulkSendingClient extends AbstractMailtrapClient
{
    public const API_MAPPING = [
        'emails' => Api\BulkSending\Emails::class,
    ];
}
