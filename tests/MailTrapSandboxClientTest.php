<?php

declare(strict_types=1);

namespace Mailtrap\Tests;

use Mailtrap\Api\Sandbox\SandboxAccount;
use Mailtrap\Api\Sandbox\SandboxEmails;
use Mailtrap\Api\Sandbox\SandboxInterface;
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

    /**
     * @dataProvider mapInstancesProvider
     */
    public function testMapInstance($instance): void
    {
        $this->assertInstanceOf(SandboxInterface::class, $instance);
    }

    public function invalidApiClassNameProvider(): array
    {
        return [['fakeclass1', 'fakeclass2']];
    }

    public function mapInstancesProvider(): array
    {
        $instances = [];
        $mapping = $this->getApiMappingConstant();

        foreach ($mapping as $item) {
            $instances[] = new $item($this->getConfigMock());
        }

        return [$instances];
    }

    private function getApiMappingConstant(): array
    {
        $oClass = new ReflectionClass(MailTrapSandboxClient::class);
        $constants = $oClass->getConstants();

        return $constants['API_MAPPING'];
    }
}
