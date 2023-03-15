<?php

declare(strict_types=1);

namespace Mailtrap\Tests\Symfony\Transport;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Config;
use Mailtrap\Integration\Symfony\Transport\MailTrapApiTransport;
use Mailtrap\Integration\Symfony\Transport\MailTrapTransportFactory;
use Mailtrap\MailTrapSandboxClient;
use Mailtrap\MailTrapSendingClient;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\TransportFactoryInterface;

/**
 * @covers MailTrapTransportFactory
 *
 * Class MailtrapTransportFactoryTest
 */
class MailTrapTransportFactoryTest extends AbstractTransportFactoryTestCase
{
    protected function setUp(): void
    {
        if (!class_exists(Dsn::class)) {
            $this->markTestSkipped(
                'The "MailTrapTransportFactoryTest" tests skipped, because "symfony/mailer" package is not installed.'
            );
        }

        parent::setUp();
    }

    public function getFactory(): TransportFactoryInterface
    {
        return new MailTrapTransportFactory($this->getDispatcher(), $this->getClient(), $this->getLogger());
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
        $config = (new Config(self::USER))
            ->setHttpClient(new Psr18Client($this->getClient()))
            ->setHost(AbstractApi::SENDMAIL_HOST);

        yield [
            new Dsn('mailtrap+api', 'default', self::USER),
            new MailTrapApiTransport(
                new MailTrapSendingClient(
                    (new Config(self::USER))
                        ->setHttpClient(new Psr18Client($this->getClient()))
                        ->setHost(AbstractApi::SENDMAIL_HOST)
                ),
                null,
                $dispatcher,
                $logger
            ),
        ];

        yield [
            new Dsn('mailtrap', 'default', self::USER),
            new MailTrapApiTransport(
                new MailTrapSendingClient(
                    (new Config(self::USER))
                        ->setHttpClient(new Psr18Client($this->getClient()))
                        ->setHost(AbstractApi::SENDMAIL_HOST)
                ),
                null,
                $dispatcher,
                $logger
            ),
        ];

        // sandbox
        yield [
            new Dsn('mailtrap', 'sandbox.api.mailtrap.io', self::USER, null, null, ['inboxId' => 1234]),
            new MailTrapApiTransport(
                new MailTrapSandboxClient(
                    (new Config(self::USER))
                        ->setHttpClient(new Psr18Client($this->getClient()))
                        ->setHost(AbstractApi::SENDMAIL_SANDBOX_HOST)
                ),
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
