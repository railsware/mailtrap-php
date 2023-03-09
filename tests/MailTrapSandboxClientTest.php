<?php

declare(strict_types=1);

namespace Mailtrap\Tests;

use Mailtrap\Api\Sandbox\SandboxAccount;
use Mailtrap\Api\Sandbox\SandboxEmails;
use Mailtrap\ConfigInterface;
use Mailtrap\Exception\BadMethodCallException;
use Mailtrap\MailTrapSandboxClient;
use ReflectionClass;

/**
 * @covers MailTrapSandboxClient
 *
 * Class MailTrapSandboxClientTest
 */
class MailTrapSandboxClientTest extends MailtrapTestCase
{
    private ?MailTrapSandboxClient $mailTrapSandboxClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mailTrapSandboxClient = new MailTrapSandboxClient($this->createMock(ConfigInterface::class));
    }

    protected function tearDown(): void
    {
        $this->mailTrapSandboxClient = null;

        parent::tearDown();
    }

    public function testAccounts(): void
    {
        $this->assertInstanceOf(SandboxAccount::class, $this->mailTrapSandboxClient->accounts());
    }

    public function testEmails(): void
    {
        $this->assertInstanceOf(SandboxEmails::class, $this->mailTrapSandboxClient->emails());
    }

    /**
     * @dataProvider invalidApiClassNameProvider
     */
    public function testInvalidApiClassName($name): void
    {
        $this->expectExceptionObject(
            new BadMethodCallException(
                sprintf('%s -> undefined method called: "%s"', MailTrapSandboxClient::class, $name)
            )
        );

        $this->mailTrapSandboxClient->{$name}();
    }

    public function testMapCount(): void
    {
        $oClass = new ReflectionClass(MailTrapSandboxClient::class);

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
