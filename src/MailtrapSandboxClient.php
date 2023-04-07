<?php

declare(strict_types=1);

namespace Mailtrap;

use Mailtrap\Api;

/**
 * @method  Api\Sandbox\Emails   emails
 *
 * Class MailtrapSandboxClient
 */
final class MailtrapSandboxClient extends AbstractMailtrapClient
{
    public const API_MAPPING = [
        'emails' => Api\Sandbox\Emails::class,
    ];
}
