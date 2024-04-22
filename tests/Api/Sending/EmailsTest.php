<?php

declare(strict_types=1);

namespace Mailtrap\Tests\Api\Sending;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Api\Sending\Emails;
use Mailtrap\Tests\Api\AbstractEmailsTest;

/**
 * @covers Emails
 *
 * Class EmailsTest
 */
final class EmailsTest extends AbstractEmailsTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->email = $this->getMockBuilder(Emails::class)
            ->onlyMethods(['httpPost'])
            ->setConstructorArgs([$this->getConfigMock()])
            ->getMock()
        ;
    }

    protected function tearDown(): void
    {
        $this->email = null;

        parent::tearDown();
    }

    protected function getHost(): string
    {
        return AbstractApi::SENDMAIL_TRANSACTIONAL_HOST;
    }
}
