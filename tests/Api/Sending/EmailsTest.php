<?php

declare(strict_types=1);

namespace Mailtrap\Tests\Api\Sending;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Api\Sending\Emails;
use Mailtrap\EmailHeader\CategoryHeader;
use Mailtrap\EmailHeader\CustomVariableHeader;
use Mailtrap\EmailHeader\Template\TemplateUuidHeader;
use Mailtrap\EmailHeader\Template\TemplateVariableHeader;
use Mailtrap\Exception\HttpClientException;
use Mailtrap\Exception\RuntimeException;
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

    public function testValidSend(): void
    {
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
            ->with(AbstractApi::SENDMAIL_HOST . '/api/send', [], [
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

        $response = $this->email->send($email);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(2, $responseData);
        $this->assertArrayHasKey('success', $responseData);
        $this->assertArrayHasKey('message_ids', $responseData);
    }

    public function testInValidSend(): void
    {
        $expectedData = [
            "errors" => [
                "'to' address is required",
                "'subject' is required",
            ]
        ];

        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage(
            "Bad request. Fix errors listed in response before retrying. Errors: 'to' address is required. 'subject' is required."
        );

        $email = new Email();
        $email->from(new Address('foo@example.com', 'Ms. Foo Bar'))
            ->text('Some text')
            ->html('<p>Some text</p>')
        ;
        $email->getHeaders()
            ->addTextHeader('X-Message-Source', 'dev.mydomain.com');

        $this->email
            ->expects($this->once())
            ->method('httpPost')
            ->with(AbstractApi::SENDMAIL_HOST . '/api/send', [], [
                'from' => [
                    'email' => 'foo@example.com',
                    'name' => 'Ms. Foo Bar',
                ],
                'to' => [],
                'text' => 'Some text',
                'html' => '<p>Some text</p>',
                'headers' => [
                    'X-Message-Source' => 'dev.mydomain.com'
                ]
            ])
            ->willReturn(new Response(400, ['Content-Type' => 'application/json'], json_encode($expectedData)));

        $this->email->send($email);
    }

    public function testValidSendTemplate(): void
    {
        $expectedData = [
            "success" => true,
            "message_ids" => [
                "0c7fd939-02cf-11ed-88c2-0a58a9feac02"
            ]
        ];

        $email = (new Email())
            ->from(new Address('foo@example.com', 'Ms. Foo Bar'))
            ->to(new Address('bar@example.com', 'Mr. Recipient'))
        ;

        $email->getHeaders()
            ->add(new TemplateUuidHeader('bfa432fd-0000-0000-0000-8493da283a69'))
            ->add(new TemplateVariableHeader('user_name', 'Jon Bush'))
            ->add(new TemplateVariableHeader('next_step_link', 'https://mailtrap.io/'))
            ->add(new TemplateVariableHeader('get_started_link', 'https://mailtrap.io/'))
            ->add(new TemplateVariableHeader('onboarding_video_link', 'some_video_link'))
        ;

        $this->email
            ->expects($this->once())
            ->method('httpPost')
            ->with(AbstractApi::SENDMAIL_HOST . '/api/send', [], [
                'from' => [
                    'email' => 'foo@example.com',
                    'name' => 'Ms. Foo Bar',
                ],
                'to' => [[
                    'email' => 'bar@example.com',
                    'name' => 'Mr. Recipient',
                ]],
                'template_uuid' => 'bfa432fd-0000-0000-0000-8493da283a69',
                'template_variables' => [
                    'user_name' => 'Jon Bush',
                    'next_step_link' => 'https://mailtrap.io/',
                    'get_started_link' => 'https://mailtrap.io/',
                    'onboarding_video_link' => 'some_video_link',
                ]
            ])
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($expectedData)));

        $response = $this->email->send($email);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(2, $responseData);
        $this->assertArrayHasKey('success', $responseData);
        $this->assertArrayHasKey('message_ids', $responseData);
    }

    public function testAttachments(): void
    {
        $email = (new Email())
            ->from(new Address('foo@example.com', 'Ms. Foo Bar'))
            ->attach('fake_body', 'fakeFile.jpg', 'image/jpg')
        ;

        $method = new \ReflectionMethod(Emails::class, 'getPayload');
        $method->setAccessible(true);
        $payload = $method->invoke(new Emails($this->getConfigMock()), $email);

        $this->assertArrayHasKey('attachments', $payload);
        $this->assertArrayHasKey('content', $payload['attachments'][0]);
        $this->assertArrayHasKey('type', $payload['attachments'][0]);
        $this->assertArrayHasKey('filename', $payload['attachments'][0]);
        $this->assertEquals('ZmFrZV9ib2R5', $payload['attachments'][0]['content']);
        $this->assertEquals('image/jpg', $payload['attachments'][0]['type']);
        $this->assertEquals('fakeFile.jpg', $payload['attachments'][0]['filename']);
    }

    /**
     * @dataProvider validHeadersDataProvider
     */
    public function testHeaders($name, $value): void
    {
        $email = (new Email())->from(new Address('foo@example.com', 'Ms. Foo Bar'));
        $email->getHeaders()->addTextHeader($name, $value);

        $method = new \ReflectionMethod(Emails::class, 'getPayload');
        $method->setAccessible(true);
        $payload = $method->invoke(new Emails($this->getConfigMock()), $email);

        $this->assertArrayHasKey('headers', $payload);
        $this->assertArrayHasKey($name, $payload['headers']);
        $this->assertEquals($value, $payload['headers'][$name]);
    }

    /**
     * @dataProvider validCustomVariablesDataProvider
     */
    public function testCustomVariables($name, $value): void
    {
        $email = (new Email())->from(new Address('foo@example.com', 'Ms. Foo Bar'));
        $email->getHeaders()
            ->add(new CustomVariableHeader($name, $value));

        $method = new \ReflectionMethod(Emails::class, 'getPayload');
        $method->setAccessible(true);
        $payload = $method->invoke(new Emails($this->getConfigMock()), $email);

        $this->assertArrayHasKey(CustomVariableHeader::VAR_NAME, $payload);
        $this->assertArrayHasKey($name, $payload[CustomVariableHeader::VAR_NAME]);
        $this->assertEquals($value, $payload[CustomVariableHeader::VAR_NAME][$name]);
    }

    /**
     * @dataProvider validEmailCategoryDataProvider
     */
    public function testEmailCategory($value): void
    {
        $email = (new Email())->from(new Address('foo@example.com', 'Ms. Foo Bar'));
        $email->getHeaders()
            ->add(new CategoryHeader($value));

        $method = new \ReflectionMethod(Emails::class, 'getPayload');
        $method->setAccessible(true);
        $payload = $method->invoke(new Emails($this->getConfigMock()), $email);

        $this->assertArrayHasKey(CategoryHeader::VAR_NAME, $payload);
        $this->assertEquals($value, $payload[CategoryHeader::VAR_NAME]);
    }

    public function testInvalidCountOfEmailCategory(): void
    {
        $email = (new Email())->from(new Address('foo@example.com', 'Ms. Foo Bar'));
        $email->getHeaders()
            ->add(new CategoryHeader('category 1'))
            ->add(new CategoryHeader('category 2'))
        ;

        $this->expectExceptionObject(
            new RuntimeException(
                sprintf('Too many "%s" instances present in the email headers. Mailtrap does not accept more than 1 category in the email.', CategoryHeader::class)
            )
        );

        $method = new \ReflectionMethod(Emails::class, 'getPayload');
        $method->setAccessible(true);
        $method->invoke(new Emails($this->getConfigMock()), $email);
    }

    /**
     * @dataProvider validTemplateUuidDataProvider
     */
    public function testTemplateUuid($value): void
    {
        $email = (new Email())->from(new Address('foo@example.com', 'Ms. Foo Bar'));
        $email->getHeaders()
            ->add(new TemplateUuidHeader($value));

        $method = new \ReflectionMethod(Emails::class, 'getPayload');
        $method->setAccessible(true);
        $payload = $method->invoke(new Emails($this->getConfigMock()), $email);

        $this->assertArrayHasKey(TemplateUuidHeader::VAR_NAME, $payload);
        $this->assertEquals($value, $payload[TemplateUuidHeader::VAR_NAME]);
    }

    public function testInvalidCountOfTemplateUuid(): void
    {
        $email = (new Email())->from(new Address('foo@example.com', 'Ms. Foo Bar'));
        $email->getHeaders()
            ->add(new TemplateUuidHeader('11111111-0000-0000-0000-8493da283a69'))
            ->add(new TemplateUuidHeader('22222222-0000-0000-0000-8493da283a69'))
        ;

        $this->expectExceptionObject(
            new RuntimeException(
                sprintf('Too many "%s" instances present in the email headers. Mailtrap does not accept more than 1 template UUID in the email.', TemplateUuidHeader::class)
            )
        );

        $method = new \ReflectionMethod(Emails::class, 'getPayload');
        $method->setAccessible(true);
        $method->invoke(new Emails($this->getConfigMock()), $email);
    }

    /**
     * @dataProvider validTemplateVariablesDataProvider
     */
    public function testTemplateVariables($name, $value): void
    {
        $email = (new Email())->from(new Address('foo@example.com', 'Ms. Foo Bar'));
        $email->getHeaders()
            ->add(new TemplateVariableHeader($name, $value));

        $method = new \ReflectionMethod(Emails::class, 'getPayload');
        $method->setAccessible(true);
        $payload = $method->invoke(new Emails($this->getConfigMock()), $email);

        $this->assertArrayHasKey(TemplateVariableHeader::VAR_NAME, $payload);
        $this->assertArrayHasKey($name, $payload[TemplateVariableHeader::VAR_NAME]);
        $this->assertEquals($value, $payload[TemplateVariableHeader::VAR_NAME][$name]);
    }

    //<editor-fold desc="Data Providers">

    public function validHeadersDataProvider(): array
    {
        return [
            ['X-Message-Source', 'dev.mydomain.com'],
            ['X-Mailer', 'Mailtrap PHP Client'],
        ];
    }

    public function validCustomVariablesDataProvider(): array
    {
        return [
            ['user_id', '45982'],
            ['batch_id', 'PSJ-12'],
        ];
    }

    public function validEmailCategoryDataProvider(): array
    {
        return [
            ['Integration Test'],
            ['Some other category'],
        ];
    }

    public function validTemplateUuidDataProvider(): array
    {
        return [
            ['11111111-0000-0000-0000-8493da283a69'],
            ['22222222-0000-0000-0000-8493da283a69'],
        ];
    }

    public function validTemplateVariablesDataProvider(): array
    {
        return [
            ['user_name', 'Jon Bush'],
            ['next_step_link', 'https://mailtrap.io/'],
            ['onboarding_video_link', 'some_video_link'],
        ];
    }

    //</editor-fold>
}
