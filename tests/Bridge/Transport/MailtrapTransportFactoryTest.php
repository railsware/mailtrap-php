<?php

declare(strict_types=1);

namespace Mailtrap\Tests\Bridge\Transport;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Bridge\Transport\MailtrapApiTransport;
use Mailtrap\Bridge\Transport\MailtrapTransportFactory;
use Mailtrap\Config;
use Mailtrap\MailtrapClient;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\TransportFactoryInterface;

/**
 * @covers MailtrapTransportFactory
 *
 * Class MailtrapTransportFactoryTest
 */
class MailtrapTransportFactoryTest extends TransportFactoryTestCase
{
    protected function setUp(): void
    {
        if (!class_exists(Dsn::class)) {
            $this->markTestSkipped(
                'The "MailtrapTransportFactoryTest" tests skipped, because "symfony/mailer" package is not installed.'
            );
        }

        parent::setUp();
    }

    public function getFactory(): TransportFactoryInterface
    {
        return new MailtrapTransportFactory($this->getDispatcher(), $this->getClient(), $this->getLogger());
    }

    public function supportsProvider(): iterable
    {
        yield [
            new Dsn('mailtrap+api', 'default'),
            true,
        ];

        yield [
            new Dsn('mailtrap', 'default'),
            true,
        ];
    }

    public function createProvider(): iterable
    {
        $dispatcher = $this->getDispatcher();
        $logger = $this->getLogger();

        yield [
            new Dsn('mailtrap+api', 'default', self::USER),
            new MailtrapApiTransport(
                (new MailtrapClient(
                    (new Config(self::USER))
                        ->setHttpClient(new Psr18Client($this->getClient()))
                        ->setHost(AbstractApi::SENDMAIL_HOST)
                ))->sending(),
                null,
                $dispatcher,
                $logger
            ),
        ];

        yield [
            new Dsn('mailtrap', AbstractApi::SENDMAIL_HOST, self::USER),
            new MailtrapApiTransport(
                (new MailtrapClient(
                    (new Config(self::USER))
                        ->setHttpClient(new Psr18Client($this->getClient()))
                        ->setHost(AbstractApi::SENDMAIL_HOST)
                ))->sending(),
                null,
                $dispatcher,
                $logger
            ),
        ];

        // sandbox
        yield [
            new Dsn('mailtrap', AbstractApi::SENDMAIL_SANDBOX_HOST, self::USER, null, null, ['inboxId' => 1234]),
            new MailtrapApiTransport(
                (new MailtrapClient(
                    (new Config(self::USER))
                        ->setHttpClient(new Psr18Client($this->getClient()))
                        ->setHost(AbstractApi::SENDMAIL_SANDBOX_HOST)
                ))->sandbox(),
                1234,
                $dispatcher,
                $logger
            ),
        ];
    }

    public function unsupportedSchemeProvider(): iterable
    {
        yield [
            new Dsn('mailtrap+foo', 'mailtrap', self::USER),
            'The "mailtrap+foo" scheme is not supported; supported schemes for mailer "mailtrap" are: "mailtrap", "mailtrap+api".',
        ];
    }

    public function incompleteDsnProvider(): iterable
    {
        yield [new Dsn('mailtrap+api', 'default')];
    }
}
