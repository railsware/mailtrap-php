<?php

declare(strict_types=1);

namespace Mailtrap;

use Mailtrap\Api;

/**
 * @method Api\General\Account      accounts
 * @method Api\General\User         users
 * @method Api\General\Permission   permissions
 *
 * Class MailtrapGeneralClient
 */
final class MailtrapGeneralClient extends AbstractMailtrapClient
{
    public const API_MAPPING = [
        'accounts' => Api\General\Account::class,
        'users' => Api\General\User::class,
        'permissions' => Api\General\Permission::class
    ];
}
