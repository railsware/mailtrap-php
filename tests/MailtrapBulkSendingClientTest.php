<?php

declare(strict_types=1);

namespace Mailtrap\Tests;

use Mailtrap\Api\BulkSending\BulkSendingInterface;
use Mailtrap\MailtrapBulkSendingClient;

/**
 * @covers MailtrapBulkSendingClientTest
 *
 * Class MailtrapBulkSendingClientTest
 */
class MailtrapBulkSendingClientTest extends MailtrapClientTestCase
{
    public function getMailtrapClientClassName(): string
    {
        return MailtrapBulkSendingClient::class;
    }

    public function getLayerInterfaceClassName(): string
    {
        return BulkSendingInterface::class;
    }

    public function mapInstancesProvider(): iterable
    {
        foreach (MailtrapBulkSendingClient::API_MAPPING as $item) {
            yield [new $item($this->getConfigMock())];
        }
    }
}
