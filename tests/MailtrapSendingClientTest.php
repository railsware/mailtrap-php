<?php

declare(strict_types=1);

namespace Mailtrap\Tests;

use Mailtrap\Api\Sending\SendingInterface;
use Mailtrap\ConfigInterface;
use Mailtrap\Exception\BadMethodCallException;
use Mailtrap\MailtrapSendingClient;
use ReflectionClass;

/**
 * @covers MailtrapSendingClient
 *
 * Class MailtrapSendingClientTest
 */
class MailtrapSendingClientTest extends MailtrapTestCase
{
    private ?MailtrapSendingClient $mailTrapSendingClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mailTrapSendingClient = new MailtrapSendingClient($this->createMock(ConfigInterface::class));
    }

    protected function tearDown(): void
    {
        $this->mailTrapSendingClient = null;

        parent::tearDown();
    }

    /**
     * @dataProvider invalidApiClassNameProvider
     */
    public function testInvalidApiClassName($name): void
    {
        $this->expectExceptionObject(
            new BadMethodCallException(
                sprintf('%s -> undefined method called: "%s"', MailtrapSendingClient::class, $name)
            )
        );

        $this->mailTrapSendingClient->{$name}();
    }

    /**
     * @dataProvider mapInstancesProvider
     */
    public function testMapInstance($instance): void
    {
        $this->assertInstanceOf(SendingInterface::class, $instance);
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
        $oClass = new ReflectionClass(MailtrapSendingClient::class);
        $constants = $oClass->getConstants();

        return $constants['API_MAPPING'];
    }
}
