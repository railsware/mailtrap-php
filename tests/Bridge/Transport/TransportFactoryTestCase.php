<?php

declare(strict_types=1);

namespace Mailtrap\Tests\Bridge\Transport;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Exception\Transport\UnsupportedHostException;
use Mailtrap\Tests\MailtrapTestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\IncompleteDsnException;
use Symfony\Component\Mailer\Exception\UnsupportedSchemeException;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\TransportFactoryInterface;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


abstract class TransportFactoryTestCase extends MailtrapTestCase
{
    protected const USER = 'u$er';
    protected const PASSWORD = 'pa$s';

    protected $dispatcher;
    protected $client;
    protected $logger;

    abstract public function getFactory(): TransportFactoryInterface;

    abstract public function supportsProvider(): iterable;

    abstract public function createProvider(): iterable;

    public function unsupportedSchemeProvider(): iterable
    {
        return [];
    }

    public function incompleteDsnProvider(): iterable
    {
        return [];
    }

    public function unsupportedHostsProvider(): iterable
    {
        return [];
    }

    /**
     * @dataProvider supportsProvider
     */
    public function testSupports(Dsn $dsn, bool $supports): void
    {
        $factory = $this->getFactory();

        $this->assertSame($supports, $factory->supports($dsn));
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreate(Dsn $dsn, TransportInterface $transport): void
    {
        $factory = $this->getFactory();

        $this->assertEquals($transport, $factory->create($dsn));

        if ('default' === $dsn->getHost()) {
            $this->assertStringMatchesFormat('mailtrap+sdk://' . AbstractApi::SENDMAIL_TRANSACTIONAL_HOST, (string) $transport);
        } else {
            $this->assertStringStartsWith('mailtrap+sdk://' . $dsn->getHost(), (string) $transport);
        }
    }

    /**
     * @dataProvider unsupportedSchemeProvider
     */
    public function testUnsupportedSchemeException(Dsn $dsn, string $message = null): void
    {
        $factory = $this->getFactory();

        $this->expectException(UnsupportedSchemeException::class);
        if (null !== $message) {
            $this->expectExceptionMessage($message);
        }

        $factory->create($dsn);
    }

    /**
     * @dataProvider unsupportedHostsProvider
     */
    public function testUnsupportedHostsException(Dsn $dsn): void
    {
        $factory = $this->getFactory();

        $this->expectException(UnsupportedHostException::class);

        $factory->create($dsn);
    }

    /**
     * @dataProvider incompleteDsnProvider
     */
    public function testIncompleteDsnException(Dsn $dsn): void
    {
        $factory = $this->getFactory();

        $this->expectException(IncompleteDsnException::class);
        $factory->create($dsn);
    }

    protected function getDispatcher(): EventDispatcherInterface
    {
        return $this->dispatcher ??= $this->createMock(EventDispatcherInterface::class);
    }

    protected function getClient(): HttpClientInterface
    {
        return $this->client ??= $this->createMock(HttpClientInterface::class);
    }

    protected function getLogger(): LoggerInterface
    {
        return $this->logger ??= $this->createMock(LoggerInterface::class);
    }
}
