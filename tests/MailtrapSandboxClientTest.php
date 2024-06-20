<?php

declare(strict_types=1);

namespace Mailtrap\Tests;

use Mailtrap\Api\Sandbox\Emails as SandboxSendingEmails;
use Mailtrap\Api\Sandbox\SandboxInterface;
use Mailtrap\MailtrapClient;
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
        foreach (MailtrapSandboxClient::API_MAPPING as $key => $item) {
            yield match ($key) {
                'emails' => [new $item($this->getConfigMock(), self::FAKE_INBOX_ID)],
                'projects', 'inboxes' => [new $item($this->getConfigMock(), self::FAKE_ACCOUNT_ID)],
                'messages', 'attachments' => [new $item($this->getConfigMock(), self::FAKE_ACCOUNT_ID, self::FAKE_INBOX_ID)],
                default => [new $item($this->getConfigMock())],
            };
        }
    }

    public function testValidInitSandboxSendingEmails(): void
    {
        $this->assertInstanceOf(
            SandboxSendingEmails::class,
            MailtrapClient::initSendingEmails(apiKey: self::DEFAULT_API_KEY, isSandbox: true, inboxId: self::FAKE_INBOX_ID)
        );
    }
}
