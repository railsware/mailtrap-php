<?php

declare(strict_types=1);

namespace Mailtrap;

use Mailtrap\Api;

/**
 * @method  Api\Sandbox\SandboxAccount  accounts
 * @method  Api\Sandbox\SandboxEmails   emails
 *
 * Class MailtrapSandboxClient
 */
class MailtrapSandboxClient extends AbstractMailtrapClient
{
    private const API_MAPPING = [
        'accounts' => Api\Sandbox\SandboxAccount::class,
        'emails' => Api\Sandbox\SandboxEmails::class,
    ];

    public function getApiClassByName(string $name): ?string
    {
        return !empty(self::API_MAPPING[$name]) ? self::API_MAPPING[$name] : null;
    }
}
