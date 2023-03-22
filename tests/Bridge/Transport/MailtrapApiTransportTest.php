<?php

declare(strict_types=1);

namespace Mailtrap\Tests\Bridge\Transport;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Bridge\Transport\MailtrapApiTransport;
use Mailtrap\Config;
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
        return [
            [
                new MailtrapApiTransport(
                    new MailtrapSendingClient(
                        (new Config('key'))->setHost(AbstractApi::SENDMAIL_HOST)
                    )
                ),
                'mailtrap+api://send.api.mailtrap.io',
            ],
            [
                new MailtrapApiTransport(
                    new MailtrapSendingClient(
                        (new Config('key'))->setHost(AbstractApi::SENDMAIL_SANDBOX_HOST)
                    )
                ),
                'mailtrap+api://sandbox.api.mailtrap.io',
            ],
        ];
    }
}
