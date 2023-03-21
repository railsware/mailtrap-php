<?php

declare(strict_types=1);

namespace Mailtrap\Tests\Bridge\Transport;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Bridge\Transport\MailTrapApiTransport;
use Mailtrap\Config;
use Mailtrap\MailTrapSendingClient;
use Mailtrap\Tests\MailTrapTestCase;
use Symfony\Component\Mailer\Envelope;

/**
 * @covers MailTrapApiTransport
 */
class MailTrapApiTransportTest extends MailTrapTestCase
{
    protected function setUp(): void
    {
        if (!class_exists(Envelope::class)) {
            $this->markTestSkipped(
                'The "MailTrapApiTransportTest" tests skipped, because "symfony/mailer" package is not installed.'
            );
        }

        parent::setUp();
    }

    /**
     * @dataProvider getTransportData
     */
    public function testToString(MailTrapApiTransport $transport, string $expected): void
    {
        $this->assertSame($expected, (string) $transport);
    }

    public static function getTransportData(): array
    {
        return [
            [
                new MailTrapApiTransport(
                    new MailTrapSendingClient(
                        (new Config('key'))->setHost(AbstractApi::SENDMAIL_HOST)
                    )
                ),
                'mailtrap+api://send.api.mailtrap.io',
            ],
            [
                new MailTrapApiTransport(
                    new MailTrapSendingClient(
                        (new Config('key'))->setHost(AbstractApi::SENDMAIL_SANDBOX_HOST)
                    )
                ),
                'mailtrap+api://sandbox.api.mailtrap.io',
            ],
        ];
    }
}
