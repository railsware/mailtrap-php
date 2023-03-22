<?php

declare(strict_types=1);

namespace Mailtrap\Tests;

use Mailtrap\Api\Sandbox\SandboxInterface;
use Mailtrap\ConfigInterface;
use Mailtrap\Exception\BadMethodCallException;
use Mailtrap\MailtrapSandboxClient;
use ReflectionClass;

/**
 * @covers MailtrapSandboxClient
 *
 * Class MailtrapSandboxClientTest
 */
class MailtrapSandboxClientTest extends MailtrapTestCase
{
    private ?MailtrapSandboxClient $mailTrapSandboxClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mailTrapSandboxClient = new MailtrapSandboxClient($this->createMock(ConfigInterface::class));
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
                sprintf('%s -> undefined method called: "%s"', MailtrapSandboxClient::class, $name)
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
        $oClass = new ReflectionClass(MailtrapSandboxClient::class);
        $constants = $oClass->getConstants();

        return $constants['API_MAPPING'];
    }
}
