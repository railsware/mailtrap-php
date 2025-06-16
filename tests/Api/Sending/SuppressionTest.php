<?php

declare(strict_types=1);

namespace Mailtrap\Tests\Api\Sending;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Api\Sending\Suppression;
use Mailtrap\Exception\HttpClientException;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\Tests\MailtrapTestCase;
use Nyholm\Psr7\Response;

/**
 * @covers Suppression
 *
 * Class SuppressionTest
 */
class SuppressionTest extends MailtrapTestCase
{
    private ?Suppression $suppression;

    protected function setUp(): void
    {
        parent::setUp();
        $this->suppression = $this->getMockBuilder(Suppression::class)
            ->onlyMethods(['httpGet', 'httpDelete'])
            ->setConstructorArgs([$this->getConfigMock(), self::FAKE_ACCOUNT_ID])
            ->getMock();
    }

    protected function tearDown(): void
    {
        $this->suppression = null;
        parent::tearDown();
    }

    public function testGetSuppressionsWithoutEmail(): void
    {
        $expectedResponseBody = $this->getExpectedResponse('john_6832ebeadcf31@example.com');

        $this->suppression->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/suppressions')
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], $expectedResponseBody));

        $response = $this->suppression->getSuppressions();
        $responseData = ResponseHelper::toArray($response);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertArrayHasKey('id', $responseData[0]);
    }

    public function testGetSuppressionsWithEmail(): void
    {
        $email = 'test@mail.com';
        $expectedResponseBody = $this->getExpectedResponse($email);

        $this->suppression->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/suppressions', ['email' => $email])
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], $expectedResponseBody));

        $response = $this->suppression->getSuppressions($email);
        $responseData = ResponseHelper::toArray($response);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertArrayHasKey('id', $responseData[0]);
        $this->assertArrayHasKey('email', $responseData[0]);
        $this->assertSame($email, $responseData[0]['email']);
    }

    public function testDeleteSuppression(): void
    {
        $suppressionId = '25594eef-87e0-49c7-a647-cc316f9fdb42';

        $this->suppression->expects($this->once())
            ->method('httpDelete')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/suppressions/' . $suppressionId)
            ->willReturn(new Response(200));

        $response = $this->suppression->deleteSuppression($suppressionId);
        $this->assertSame(200, $response->getStatusCode());
    }

    public function testDeleteSuppressionNotFound(): void
    {
        $suppressionId = 'non-existent-id';
        $expectedErrorResponse = [
            'error' => 'Suppression not found',
        ];

        $this->suppression->expects($this->once())
            ->method('httpDelete')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/suppressions/' . $suppressionId)
            ->willReturn(
                new Response(404, ['Content-Type' => 'application/json'], json_encode($expectedErrorResponse))
            );

        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage('The requested entity has not been found. Errors: Suppression not found.');

        $this->suppression->deleteSuppression($suppressionId);
    }

    private function getExpectedResponse(string $email): string
    {
        return json_encode([
            [
               "id" => "25594eef-87e0-49c7-a647-cc316f9fdb42",
               "type" => "unsubscription",
               "created_at" => "2025-05-25T10:07:49Z",
               "email" => $email,
               "sending_stream" => "bulk",
               "domain_name" => "",
               "message_bounce_category" => "",
               "message_category" => "",
               "message_client_ip" => "",
               "message_created_at" => "",
               "message_esp_response" => "",
               "message_esp_server_type" => "",
               "message_outgoing_ip" => "",
               "message_recipient_mx_name" => "",
               "message_sender_email" => "",
               "message_subject" => "",
            ],
        ]);
    }
}
