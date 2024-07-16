<?php

declare(strict_types=1);

namespace Mailtrap\Tests\Bridge\Transport;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Bridge\Transport\MailtrapApiTransport;
use Mailtrap\Config;
use Mailtrap\MailtrapBulkSendingClient;
use Mailtrap\MailtrapSandboxClient;
use Mailtrap\MailtrapSendingClient;
use Mailtrap\Tests\MailtrapTestCase;
use Symfony\Component\Mailer\Envelope;

/**
 * @covers MailtrapApiTransport
 */
class MailtrapApiTransportTest extends MailtrapTestCase
{
    protected function setUp(): void
    {
        if (!class_exists(Envelope::class)) {
            $this->markTestSkipped(
                'The "MailtrapApiTransportTest" tests skipped, because "symfony/mailer" package is not installed.'
            );
        }

        parent::setUp();
    }

    /**
     * @dataProvider getTransportData
     */
    public function testToString(MailtrapApiTransport $transport, string $expected): void
    {
        $this->assertSame($expected, (string) $transport);
    }

    public static function getTransportData(): array
    {
        $sendConfig = (new Config('key'))->setHost(AbstractApi::SENDMAIL_TRANSACTIONAL_HOST);
        $sandboxConfig = (new Config('key'))->setHost(AbstractApi::SENDMAIL_SANDBOX_HOST);
        $bulkConfig = (new Config('key'))->setHost(AbstractApi::SENDMAIL_BULK_HOST);
        $inboxId = 1234;

        return [
            [
                new MailtrapApiTransport(
                    (new MailtrapSendingClient($sendConfig))->emails(),
                    $sendConfig
                ),
                sprintf('mailtrap+api://%s', AbstractApi::SENDMAIL_TRANSACTIONAL_HOST),
            ],
            [
                new MailtrapApiTransport(
                    (new MailtrapSandboxClient($sandboxConfig))->emails($inboxId),
                    $sandboxConfig
                ),
                sprintf('mailtrap+api://%s?inboxId=%s', AbstractApi::SENDMAIL_SANDBOX_HOST, $inboxId),
            ],
            [
                new MailtrapApiTransport(
                    (new MailtrapBulkSendingClient($bulkConfig))->emails(),
                    $bulkConfig
                ),
                sprintf('mailtrap+api://%s', AbstractApi::SENDMAIL_BULK_HOST),
            ],
        ];
    }
}
