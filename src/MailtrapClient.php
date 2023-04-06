<?php

declare(strict_types=1);

namespace Mailtrap;

/**
 * The main entry point to use all possible API layers
 *
 * @method  MailtrapGeneralClient   general
 * @method  MailtrapSandboxClient   sandbox
 * @method  MailtrapSendingClient   sending
 *
 * Class MailtrapClient
 */
class MailtrapClient extends AbstractMailtrapClient
{
    public const API_MAPPING = [
        'general' => MailtrapGeneralClient::class,
        'sandbox' => MailtrapSandboxClient::class,
        'sending' => MailtrapSendingClient::class,
    ];
}
