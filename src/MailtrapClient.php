<?php

declare(strict_types=1);

namespace Mailtrap;

/**
 * The main entry point to use all possible API layers
 *
 * @method  MailtrapGeneralClient       general
 * @method  MailtrapSandboxClient       sandbox
 * @method  MailtrapSendingClient       sending
 * @method  MailtrapBulkSendingClient   bulkSending
 *
 * Class MailtrapClient
 */
class MailtrapClient extends AbstractMailtrapClient
{
    public const LAYER_GENERAL = 'general';
    public const LAYER_SANDBOX = 'sandbox';
    public const LAYER_TRANSACTIONAL_SENDING = 'sending';
    public const LAYER_BULK_SENDING = 'bulkSending';

    public const API_MAPPING = [
        self::LAYER_GENERAL => MailtrapGeneralClient::class,
        self::LAYER_SANDBOX => MailtrapSandboxClient::class,
        self::LAYER_TRANSACTIONAL_SENDING => MailtrapSendingClient::class,
        self::LAYER_BULK_SENDING => MailtrapBulkSendingClient::class,
    ];
}
