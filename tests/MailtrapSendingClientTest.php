<?php

declare(strict_types=1);

namespace Mailtrap\Tests;

use Mailtrap\Api\Sending\SendingInterface;
use Mailtrap\MailtrapSendingClient;

/**
 * @covers MailtrapSendingClient
 *
 * Class MailtrapSendingClientTest
 */
class MailtrapSendingClientTest extends MailtrapClientTestCase
{
    public function getMailtrapClientClassName(): string
    {
        return MailtrapSendingClient::class;
    }

    public function getLayerInterfaceClassName(): string
    {
        return SendingInterface::class;
    }

    public function mapInstancesProvider(): iterable
    {
        foreach (MailtrapSendingClient::API_MAPPING as $item) {
            yield [new $item($this->getConfigMock())];
        }
    }
}
