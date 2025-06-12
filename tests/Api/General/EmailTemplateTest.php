<?php

declare(strict_types=1);

namespace Mailtrap\Tests\Api\General;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Api\General\EmailTemplate;
use Mailtrap\DTO\Request\EmailTemplate as EmailTemplateDTO;
use Mailtrap\Exception\HttpClientException;
use Mailtrap\Tests\MailtrapTestCase;
use Nyholm\Psr7\Response;
use Mailtrap\Helper\ResponseHelper;

/**
 * @covers EmailTemplate
 *
 * Class EmailTemplateTest
 */
class EmailTemplateTest extends MailtrapTestCase
{
    private ?EmailTemplate $emailTemplate;

    protected function setUp(): void
    {
        parent::setUp();
        $this->emailTemplate = $this->getMockBuilder(EmailTemplate::class)
            ->onlyMethods(['httpGet', 'httpPost', 'httpPatch', 'httpDelete'])
            ->setConstructorArgs([$this->getConfigMock(), self::FAKE_ACCOUNT_ID])
            ->getMock();
    }

    protected function tearDown(): void
    {
        $this->emailTemplate = null;
        parent::tearDown();
    }

    public function testGetAllEmailTemplates(): void
    {
        $this->emailTemplate->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/email_templates')
            ->willReturn(
                new Response(200, ['Content-Type' => 'application/json'], json_encode([$this->getExpectedEmailTemplateResponse()]))
            );

        $response = $this->emailTemplate->getAllEmailTemplates();
        $responseData = ResponseHelper::toArray($response);

        $this->assertCount(1, $responseData);
        $this->assertArrayHasKey('id', $responseData[0]);
        $this->assertEquals(1, $responseData[0]['id']);
    }

    public function testGetEmailTemplateById(): void
    {
        $templateId = 1;

        $this->emailTemplate->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/email_templates/' . $templateId)
            ->willReturn(
                new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getExpectedEmailTemplateResponse()))
            );

        $response = $this->emailTemplate->getEmailTemplate($templateId);
        $responseData = ResponseHelper::toArray($response);

        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals($templateId, $responseData['id']);
    }

    public function testGetEmailTemplateFailsWithNotFoundError(): void
    {
        $templateId = 999; // Non-existent template ID
        $expectedErrorResponse = [
            'error' => 'Not Found',
        ];

        $this->emailTemplate->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/email_templates/' . $templateId)
            ->willReturn(
                new Response(404, ['Content-Type' => 'application/json'], json_encode($expectedErrorResponse))
            );

        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage('The requested entity has not been found. Errors: Not Found.');

        $this->emailTemplate->getEmailTemplate($templateId);
    }

    public function testCreateEmailTemplate(): void
    {
        $emailTemplateDTO = $this->getEmailTemplateDTO();

        $this->emailTemplate->expects($this->once())
            ->method('httpPost')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/email_templates',
                [],
                ['email_template' => $emailTemplateDTO->toArray()]
            )
            ->willReturn(
                new Response(201, ['Content-Type' => 'application/json'], json_encode($this->getExpectedEmailTemplateResponse()))
            );

        $response = $this->emailTemplate->createEmailTemplate($emailTemplateDTO);
        $responseData = ResponseHelper::toArray($response);

        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals($emailTemplateDTO->getName(), $responseData['name']);
    }

    public function testCreateEmailTemplateFailsWithValidationErrors(): void
    {
        $invalidEmailTemplateDTO = EmailTemplateDTO::init(
            '', // Invalid name
            'Promotional', // Valid category
            '', // Invalid subject
            'Template Body',
            '<div>Template Body</div>'
        );

        $expectedErrorResponse = [
            'errors' => [
                'name' => ["can't be blank"],
                'subject' => ["can't be blank"],
            ],
        ];

        $this->emailTemplate->expects($this->once())
            ->method('httpPost')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/email_templates',
                [],
                ['email_template' => $invalidEmailTemplateDTO->toArray()]
            )
            ->willReturn(
                new Response(422, ['Content-Type' => 'application/json'], json_encode($expectedErrorResponse))
            );

        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage(
            'Errors: name -> can\'t be blank. subject -> can\'t be blank.'
        );

        $this->emailTemplate->createEmailTemplate($invalidEmailTemplateDTO);
    }

    public function testUpdateEmailTemplate(): void
    {
        $templateId = 1;
        $emailTemplateDTO = $this->getEmailTemplateDTO();

        $this->emailTemplate->expects($this->once())
            ->method('httpPatch')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/email_templates/' . $templateId,
                [],
                ['email_template' => $emailTemplateDTO->toArray()]
            )
            ->willReturn(
                new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getExpectedEmailTemplateResponse()))
            );

        $response = $this->emailTemplate->updateEmailTemplate($templateId, $emailTemplateDTO);
        $responseData = ResponseHelper::toArray($response);

        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals($templateId, $responseData['id']);
    }

    public function testDeleteEmailTemplate(): void
    {
        $templateId = 1;

        $this->emailTemplate->expects($this->once())
            ->method('httpDelete')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/email_templates/' . $templateId)
            ->willReturn(new Response(204));

        $response = $this->emailTemplate->deleteEmailTemplate($templateId);

        $this->assertEquals(204, $response->getStatusCode());
    }

    private function getEmailTemplateDTO(): EmailTemplateDTO
    {
        return EmailTemplateDTO::init(
            'Template 1',
            'Test Category',
            'Test Subject',
            'Test Content',
            '<p>Test Content</p>'
        );
    }

    private function getExpectedEmailTemplateResponse(): array
    {
        return [
            'id' => 1,
            'uuid' => '019706a8-9612-77be-8586-4f26816b467a',
            'name' => 'Template 1',
            'category' => 'welcome',
            'subject' => 'Welcome to Mailtrap',
            'body_html' => '<p>Welcome to Mailtrap</p>',
            'body_text' => 'Welcome to Mailtrap',
        ];
    }
}
