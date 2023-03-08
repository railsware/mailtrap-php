<?php

declare(strict_types=1);

namespace Mailtrap\Tests;

use Mailtrap\ConfigInterface;
use Mailtrap\Exception\BadMethodCallException;
use Mailtrap\MailTrapClient;
use Mailtrap\Api;

/**
 * @covers MailTrapClient
 *
 * Class MailTrapClientTest
 */
final class MailTrapClientTest extends MailtrapTestCase
{
    private ?MailTrapClient $mailTrapClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mailTrapClient = new MailTrapClient($this->createMock(ConfigInterface::class));
    }

    protected function tearDown(): void
    {
        $this->mailTrapClient = null;

        parent::tearDown();
    }

    public function testAccounts(): void
    {
        $this->assertInstanceOf(Api\Account::class, $this->mailTrapClient->accounts());
    }

    public function testEmails(): void
    {
        $this->assertInstanceOf(Api\Emails::class, $this->mailTrapClient->emails());
    }

    /**
     * @dataProvider invalidApiClassNameProvider
     */
    public function testInvalidApiClassName($name): void
    {
        $this->expectExceptionObject(new BadMethodCallException(sprintf('Undefined method called: "%s"', $name)));
        $this->mailTrapClient->{$name}();
    }

    public function invalidApiClassNameProvider(): array
    {
        return [['fakeclass1', 'fakeclass2']];
    }
}