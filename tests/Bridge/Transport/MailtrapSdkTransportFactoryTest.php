<?php

declare(strict_types=1);

namespace Mailtrap\Tests\Bridge\Transport;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Bridge\Transport\MailtrapSdkTransport;
use Mailtrap\Bridge\Transport\MailtrapSdkTransportFactory;
use Mailtrap\Config;
use Mailtrap\MailtrapClient;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\TransportFactoryInterface;

/**
 * @covers MailtrapSdkTransportFactory
 *
 * Class MailtrapSdkTransportFactoryTest
 */
class MailtrapSdkTransportFactoryTest extends TransportFactoryTestCase
{
    protected function setUp(): void
    {
        if (!class_exists(Dsn::class)) {
            $this->markTestSkipped(
                'The "MailtrapSdkTransportFactoryTest" tests skipped, because "symfony/mailer" package is not installed.'
            );
        }

        parent::setUp();
    }

    public function getFactory(): TransportFactoryInterface
    {
        return new MailtrapSdkTransportFactory($this->getDispatcher(), $this->getClient(), $this->getLogger());
    }

    public function supportsProvider(): iterable
    {
        yield [
            new Dsn('mailtrap+sdk', 'default'),
            true,
        ];
    }

    public function createProvider(): iterable
    {
        $dispatcher = $this->getDispatcher();
        $logger = $this->getLogger();
        $psrClient = new Psr18Client($this->getClient());
        $sendConfig = (new Config(self::USER))
            ->setHttpClient($psrClient)
            ->setHost(AbstractApi::SENDMAIL_TRANSACTIONAL_HOST);
        $sandboxConfig = (new Config(self::USER))
            ->setHttpClient($psrClient)
            ->setHost(AbstractApi::SENDMAIL_SANDBOX_HOST);
        $bulkConfig = (new Config(self::USER))
            ->setHttpClient($psrClient)
            ->setHost(AbstractApi::SENDMAIL_BULK_HOST);
        $inboxId = 1234;

        yield [
            new Dsn('mailtrap+sdk', 'default', self::USER),
            new MailtrapSdkTransport(
                (new MailtrapClient($sendConfig))->sending()->emails(),
                $sendConfig,
                $dispatcher,
                $logger
            ),
        ];

        yield [
            new Dsn('mailtrap+sdk', AbstractApi::SENDMAIL_TRANSACTIONAL_HOST, self::USER),
            new MailtrapSdkTransport(
                (new MailtrapClient($sendConfig))->sending()->emails(),
                $sendConfig,
                $dispatcher,
                $logger
            ),
        ];

        // sandbox
        yield [
            new Dsn('mailtrap+sdk', AbstractApi::SENDMAIL_SANDBOX_HOST, self::USER, null, null, ['inboxId' => 1234]),
            new MailtrapSdkTransport(
                (new MailtrapClient($sandboxConfig))->sandbox()->emails($inboxId),
                $sandboxConfig,
                $dispatcher,
                $logger
            ),
        ];

        // bulk sending
        yield [
            new Dsn('mailtrap+sdk', AbstractApi::SENDMAIL_BULK_HOST, self::USER),
            new MailtrapSdkTransport(
                (new MailtrapClient($bulkConfig))->bulkSending()->emails(),
                $bulkConfig,
                $dispatcher,
                $logger
            ),
        ];
    }

    public function unsupportedSchemeProvider(): iterable
    {
        yield [
            new Dsn('mailtrap+foo', 'mailtrap', self::USER)
        ];
    }

    public function incompleteDsnProvider(): iterable
    {
        yield [new Dsn('mailtrap+sdk', 'default')];
    }

    public function unsupportedHostsProvider(): iterable
    {
        yield [new Dsn('mailtrap+sdk', 'invalid_url.api.mailtrap.io', self::USER)];
        yield [new Dsn('mailtrap+sdk', 'mailtrap.io', self::USER)];
    }
}
