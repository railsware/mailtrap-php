<?php

declare(strict_types=1);

namespace Mailtrap\Tests;

use Mailtrap\Api\Sandbox\SandboxInterface;
use Mailtrap\MailtrapSandboxClient;

/**
 * @covers MailtrapSandboxClient
 *
 * Class MailtrapSandboxClientTest
 */
class MailtrapSandboxClientTest extends MailtrapClientTestCase
{
    public function getMailtrapClientClassName(): string
    {
        return MailtrapSandboxClient::class;
    }

    public function getLayerInterfaceClassName(): string
    {
        return SandboxInterface::class;
    }

    public function mapInstancesProvider(): iterable
    {
        foreach (MailtrapSandboxClient::API_MAPPING as $item) {
            yield [new $item($this->getConfigMock())];
        }
    }
}
