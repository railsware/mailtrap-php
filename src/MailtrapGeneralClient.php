<?php

declare(strict_types=1);

namespace Mailtrap;

use Mailtrap\Api;

/**
 * @method Api\General\Account accounts
 *
 * Class MailtrapGeneralClient
 */
final class MailtrapGeneralClient extends AbstractMailtrapClient
{
    public const API_MAPPING = [
        'accounts' => Api\General\Account::class,
    ];
}
