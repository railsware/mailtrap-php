<?php

declare(strict_types=1);

namespace Mailtrap;

/**
 * @method Api\General\Account       accounts()
 * @method Api\General\User          users(int $accountId)
 * @method Api\General\Permission    permissions(int $accountId)
 * @method Api\General\Contact       contacts(int $accountId)
 * @method Api\General\EmailTemplate emailTemplates(int $accountId)
 *
 * Class MailtrapGeneralClient
 */
final class MailtrapGeneralClient extends AbstractMailtrapClient
{
    public const API_MAPPING = [
        'accounts' => Api\General\Account::class,
        'users' => Api\General\User::class,
        'permissions' => Api\General\Permission::class,
        'contacts' => Api\General\Contact::class,
        'emailTemplates' => Api\General\EmailTemplate::class,
    ];
}
