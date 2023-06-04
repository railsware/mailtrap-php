<?php

namespace Mailtrap\Tests\Api\Sandbox;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Api\Sandbox\Inbox;
use Mailtrap\DTO\Request\Inbox as InboxRequest;
use Mailtrap\Exception\HttpClientException;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\Tests\MailtrapTestCase;
use Nyholm\Psr7\Response;

/**
 * @covers Inbox
 *
 * Class InboxTest
 */
class InboxTest extends MailtrapTestCase
{
    /**
     * @var Inbox
     */
    private $inbox;

    protected function setUp(): void
    {
        parent::setUp();

        $this->inbox = $this->getMockBuilder(Inbox::class)
            ->onlyMethods(['httpGet', 'httpPost',  'httpPatch', 'httpDelete'])
            ->setConstructorArgs([$this->getConfigMock()])
            ->getMock()
        ;
    }

    protected function tearDown(): void
    {
        $this->inbox = null;

        parent::tearDown();
    }

    public function testGetList(): void
    {
        $this->inbox->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/inboxes')
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getExpectedInboxData())));

        $response = $this->inbox->getList(self::FAKE_ACCOUNT_ID);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(2, $responseData);
        $this->assertArrayHasKey('id', array_shift($responseData));
    }

    public function testGetInboxAttributes(): void
    {
        $this->inbox->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/inboxes/' . self::FAKE_INBOX_ID)
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getExpectedInboxData()[0])));

        $response = $this->inbox->getInboxAttributes(self::FAKE_ACCOUNT_ID, self::FAKE_INBOX_ID);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals(self::FAKE_INBOX_ID, $responseData['id']);
        $this->assertEquals('Admin Inbox', $responseData['name']);
        $this->assertCount(4, $responseData['permissions']);
    }

    public function testValidCreate(): void
    {
        $expectedData = [
            "id" => self::FAKE_INBOX_ID,
            "name" => "My new inbox",
            "username" => "24a5f9a9947fc7",
            "password" => "a41e3846e78543",
            "max_size" => 600,
            "status" => "active",
            "email_username" => "d008137b46-4d932f",
            "email_username_enabled" => false,
            "sent_messages_count" => 0,
            "forwarded_messages_count" => 0,
            "used" => false,
            "forward_from_email_address" => "a3336-i3866@forward.mailtrap.info",
            "project_id" => 2424,
            "domain" => "localhost",
            "pop3_domain" => "localhost",
            "email_domain" => "localhost",
            "emails_count" => 0,
            "emails_unread_count" => 0,
            "last_message_sent_at" => null,
            "smtp_ports" => [
                25,
                465,
                587,
                2525
            ],
            "pop3_ports" => [
                1100,
                9950
            ],
            "max_message_size" => 15728640,
            "permissions" => [
                "can_read" => true,
                "can_update" => true,
                "can_destroy" => true,
                "can_leave" => false
            ]
        ];

        $this->inbox->expects($this->once())
            ->method('httpPost')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/projects/' . self::FAKE_PROJECT_ID . '/inboxes',
                [],
                ['inbox' => ['name' => $expectedData['name']]]
            )
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($expectedData)));

        $response = $this->inbox->create(self::FAKE_ACCOUNT_ID, self::FAKE_PROJECT_ID, $expectedData['name']);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals(self::FAKE_INBOX_ID, $responseData['id']);
    }

    public function testInvalidCreate(): void
    {
        $errorMsg = [
            "error" => [
                "name" => [
                    "can't be blank",
                ]
            ]
        ];
        $this->inbox->expects($this->once())
            ->method('httpPost')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/projects/' . self::FAKE_PROJECT_ID . '/inboxes',
            )
            ->willReturn(new Response(422, ['Content-Type' => 'application/json'], json_encode($errorMsg)));

        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage(
            'Errors: name -> can\'t be blank.'
        );

        $this->inbox->create(self::FAKE_ACCOUNT_ID, self::FAKE_PROJECT_ID, 'a');
    }

    public function testValidDelete(): void
    {
        $this->inbox->expects($this->once())
            ->method('httpDelete')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/inboxes/' . self::FAKE_INBOX_ID
            )
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getExpectedInboxData()[0])));

        $response = $this->inbox->delete(self::FAKE_ACCOUNT_ID, self::FAKE_INBOX_ID);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals(self::FAKE_INBOX_ID, $responseData['id']);
    }

    public function testUpdate(): void
    {
        $newName = 'New inbox name';
        $emailUsername = 'new-email-username';
        $inboxRequest = new InboxRequest($newName, $emailUsername);
        $expectedData = [
            "id" => self::FAKE_INBOX_ID,
            "name" => $newName,
            "username" => "24a5f9a9947fc7",
            "password" => "a41e3846e78543",
            "max_size" => 600,
            "status" => "active",
            "email_username" => $emailUsername,
            "email_username_enabled" => false,
            "sent_messages_count" => 0,
            "forwarded_messages_count" => 0,
            "used" => false,
            "forward_from_email_address" => "a3336-i3866@forward.mailtrap.info",
            "project_id" => 2424,
            "domain" => "localhost",
            "pop3_domain" => "localhost",
            "email_domain" => "localhost",
            "emails_count" => 0,
            "emails_unread_count" => 0,
            "last_message_sent_at" => null,
            "smtp_ports" => [
                25,
                465,
                587,
                2525
            ],
            "pop3_ports" => [
                1100,
                9950
            ],
            "max_message_size" => 15728640,
            "permissions" => [
                "can_read" => true,
                "can_update" => true,
                "can_destroy" => true,
                "can_leave" => false
            ]
        ];

        $this->inbox->expects($this->once())
            ->method('httpPatch')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/inboxes/' . self::FAKE_INBOX_ID,
                [],
                ['inbox' => $inboxRequest->toArray()]
            )
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($expectedData)));

        $response = $this->inbox->update(self::FAKE_ACCOUNT_ID, self::FAKE_INBOX_ID, $inboxRequest);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals(self::FAKE_INBOX_ID, $responseData['id']);
        $this->assertEquals($newName, $responseData['name']);
        $this->assertEquals($emailUsername, $responseData['email_username']);
    }

    public function testClean(): void
    {
        $this->inbox->expects($this->once())
            ->method('httpPatch')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/inboxes/' . self::FAKE_INBOX_ID . '/clean',
            )
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getExpectedInboxData()[0])));

        $response = $this->inbox->clean(self::FAKE_ACCOUNT_ID, self::FAKE_INBOX_ID);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals(self::FAKE_INBOX_ID, $responseData['id']);
    }

    public function testMarkAsRead(): void
    {
        $this->inbox->expects($this->once())
            ->method('httpPatch')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/inboxes/' . self::FAKE_INBOX_ID . '/all_read',
            )
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getExpectedInboxData()[0])));

        $response = $this->inbox->markAsRead(self::FAKE_ACCOUNT_ID, self::FAKE_INBOX_ID);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals(self::FAKE_INBOX_ID, $responseData['id']);
    }

    public function testResetSmtpCredentials(): void
    {
        $expectedData = $this->getExpectedInboxData()[0];
        $expectedData['username'] = 'new_username';
        $expectedData['password'] = 'new_password';
        $this->inbox->expects($this->once())
            ->method('httpPatch')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/inboxes/' . self::FAKE_INBOX_ID . '/reset_credentials',
            )
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($expectedData)));

        $response = $this->inbox->resetSmtpCredentials(self::FAKE_ACCOUNT_ID, self::FAKE_INBOX_ID);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals(self::FAKE_INBOX_ID, $responseData['id']);
        $this->assertEquals($expectedData['username'], $responseData['username']);
        $this->assertEquals($expectedData['password'], $responseData['password']);
    }

    public function testToggleEmailAddress(): void
    {
        $expectedData = $this->getExpectedInboxData()[0];
        $expectedData['email_username_enabled'] = true;

        $this->inbox->expects($this->once())
            ->method('httpPatch')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/inboxes/' . self::FAKE_INBOX_ID . '/toggle_email_username',
            )
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($expectedData)));

        $response = $this->inbox->toggleEmailAddress(self::FAKE_ACCOUNT_ID, self::FAKE_INBOX_ID);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals(self::FAKE_INBOX_ID, $responseData['id']);
        $this->assertEquals($expectedData['email_username_enabled'], $responseData['email_username_enabled']);
    }

    public function testResetEmailAddress(): void
    {
        $expectedData = $this->getExpectedInboxData()[0];
        $expectedData['email_username'] = 'new_email_username';

        $this->inbox->expects($this->once())
            ->method('httpPatch')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/inboxes/' . self::FAKE_INBOX_ID . '/reset_email_username',
            )
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($expectedData)));

        $response = $this->inbox->resetEmailAddress(self::FAKE_ACCOUNT_ID, self::FAKE_INBOX_ID);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals(self::FAKE_INBOX_ID, $responseData['id']);
        $this->assertEquals($expectedData['email_username'], $responseData['email_username']);
    }

    private function getExpectedInboxData(): array
    {
        return [
            [
                "id" => self::FAKE_INBOX_ID,
                "name" => "Admin Inbox",
                "username" => "b3a87978452ae1",
                "password" => "6be9fcfc613a7c",
                "max_size" => 0,
                "status" => "active",
                "email_username" => "b7eae548c3-54c542",
                "email_username_enabled" => false,
                "sent_messages_count" => 52,
                "forwarded_messages_count" => 0,
                "used" => false,
                "forward_from_email_address" => "a3538-i4088@forward.mailtrap.info",
                "project_id" => 2293,
                "domain" => "localhost",
                "pop3_domain" => "localhost",
                "email_domain" => "localhost",
                "emails_count" => 0,
                "emails_unread_count" => 0,
                "last_message_sent_at" => null,
                "smtp_ports" => [
                    25,
                    465,
                    587,
                    2525
                ],
                "pop3_ports" => [
                    1100,
                    9950
                ],
                "max_message_size" => 5242880,
                "permissions" => [
                    "can_read" => true,
                    "can_update" => true,
                    "can_destroy" => true,
                    "can_leave" => true
                ]
            ],
            [
                "id" => 4089,
                "name" => "Viewer Inbox",
                "username" => "1d0aa0282f7712",
                "password" => "5667a20f611ae7",
                "max_size" => 0,
                "status" => "active",
                "email_username" => "f25402f8df-b77899",
                "email_username_enabled" => false,
                "sent_messages_count" => 527,
                "forwarded_messages_count" => 0,
                "used" => false,
                "forward_from_email_address" => "a3538-i4089@forward.mailtrap.info",
                "project_id" => 2293,
                "domain" => "localhost",
                "pop3_domain" => "localhost",
                "email_domain" => "localhost",
                "emails_count" => 0,
                "emails_unread_count" => 0,
                "last_message_sent_at" => null,
                "smtp_ports" => [
                    25,
                    465,
                    587,
                    2525
                ],
                "pop3_ports" => [
                    1100,
                    9950
                ],
                "max_message_size" => 5242880,
                "permissions" => [
                    "can_read" => true,
                    "can_update" => false,
                    "can_destroy" => false,
                    "can_leave" => true
                ]
            ]
        ];
    }
}
