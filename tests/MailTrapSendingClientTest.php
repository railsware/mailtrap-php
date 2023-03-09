<?php

declare(strict_types=1);

namespace Mailtrap\Tests;

use Mailtrap\Api\Sending\SendingAccount;
use Mailtrap\Api\Sending\SendingEmails;
use Mailtrap\ConfigInterface;
use Mailtrap\Exception\BadMethodCallException;
use Mailtrap\MailTrapSendingClient;
use ReflectionClass;

/**
 * @covers MailTrapSendingClient
 *
 * Class MailTrapSendingClientTest
 */
class MailTrapSendingClientTest extends MailtrapTestCase
{
    private ?MailTrapSendingClient $mailTrapSendingClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mailTrapSendingClient = new MailTrapSendingClient($this->createMock(ConfigInterface::class));
    }

    protected function tearDown(): void
    {
        $this->mailTrapSendingClient = null;

        parent::tearDown();
    }

    public function testAccounts(): void
    {
        $this->assertInstanceOf(SendingAccount::class, $this->mailTrapSendingClient->accounts());
    }

    public function testEmails(): void
    {
        $this->assertInstanceOf(SendingEmails::class, $this->mailTrapSendingClient->emails());
    }

    /**
     * @dataProvider invalidApiClassNameProvider
     */
    public function testInvalidApiClassName($name): void
    {
        $this->expectExceptionObject(
            new BadMethodCallException(
                sprintf('%s -> undefined method called: "%s"', MailTrapSendingClient::class, $name)
            )
        );

        $this->mailTrapSendingClient->{$name}();
    }

    public function testMapCount(): void
    {
        $oClass = new ReflectionClass(MailTrapSendingClient::class);

        $constants = $oClass->getConstants();

        $this->assertNotNull($constants['API_MAPPING']);
        $this->assertIsArray($constants['API_MAPPING']);
        $this->assertCount(2, $constants['API_MAPPING']); // increase value if added new endpoint
    }

    public function invalidApiClassNameProvider(): array
    {
        return [['fakeclass1', 'fakeclass2']];
    }
}
