<?php

declare(strict_types=1);

namespace Mailtrap\Tests\Api\Sandbox;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Api\Sandbox\Emails;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\Tests\MailtrapTestCase;
use Nyholm\Psr7\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

/**
 * @covers Emails
 *
 * Class EmailsTest
 */
final class EmailsTest extends MailtrapTestCase
{
    /**
     * @var Emails
     */
    private $email;

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

    public function testValidSendToSandBox(): void
    {
        $inboxId = 1000001;
        $expectedData = [
            "success" => true,
            "message_ids" => [
                "0c7fd939-02cf-11ed-88c2-0a58a9feac02"
            ]
        ];

        $email = new Email();
        $email->from(new Address('foo@example.com', 'Ms. Foo Bar'))
            ->replyTo(new Address('reply@example.com'))
            ->to(new Address('bar@example.com', 'Mr. Recipient'))
            ->priority(Email::PRIORITY_HIGH)
            ->bcc('baz@example.com')
            ->subject('Best practices of building HTML emails')
            ->text('Some text')
            ->html('<p>Some text</p>')
        ;
        $email->getHeaders()
            ->addTextHeader('X-Message-Source', 'dev.mydomain.com');

        $this->email
            ->expects($this->once())
            ->method('httpPost')
            ->with(AbstractApi::SENDMAIL_SANDBOX_HOST . '/api/send/' . $inboxId, [], [
                'from' => [
                    'email' => 'foo@example.com',
                    'name' => 'Ms. Foo Bar',
                ],
                'to' => [[
                    'email' => 'bar@example.com',
                    'name' => 'Mr. Recipient',
                ]],
                'subject' => 'Best practices of building HTML emails',
                'bcc' => [[
                    'email' => 'baz@example.com'
                ]],
                'text' => 'Some text',
                'html' => '<p>Some text</p>',
                'headers' => [
                    'X-Message-Source' => 'dev.mydomain.com',
                    'Reply-To' => 'reply@example.com',
                    'X-Priority' => '2 (High)',
                ]
            ])
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($expectedData)));

        $response = $this->email->send($email, $inboxId);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(2, $responseData);
        $this->assertArrayHasKey('success', $responseData);
        $this->assertArrayHasKey('message_ids', $responseData);
    }
}
