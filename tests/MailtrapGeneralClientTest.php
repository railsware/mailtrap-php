<?php

declare(strict_types=1);

namespace Mailtrap\Tests;

use Mailtrap\Api\General\GeneralInterface;
use Mailtrap\MailtrapGeneralClient;

/**
 * @covers MailtrapGeneralClient
 *
 * Class MailtrapGeneralClientTest
 */
class MailtrapGeneralClientTest extends MailtrapClientTestCase
{
    public function getMailtrapClientClassName(): string
    {
        return MailtrapGeneralClient::class;
    }

    public function getLayerInterfaceClassName(): string
    {
        return GeneralInterface::class;
    }

    public function mapInstancesProvider(): iterable
    {
        foreach (MailtrapGeneralClient::API_MAPPING as $item) {
            yield [new $item($this->getConfigMock())];
        }
    }
}
