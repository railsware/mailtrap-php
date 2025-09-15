<?php

declare(strict_types=1);

namespace Mailtrap\Tests\Api\General;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Api\General\Billing;
use Mailtrap\Exception\HttpClientException;
use Mailtrap\Tests\MailtrapTestCase;
use Nyholm\Psr7\Response;
use Mailtrap\Helper\ResponseHelper;

/**
 * @covers Billing
 *
 * Class BillingTest
 */
class BillingTest extends MailtrapTestCase
{
    /**
     * @var Billing|null
     */
    private ?Billing $billing;

    protected function setUp(): void
    {
        parent::setUp();

        $this->billing = $this->getMockBuilder(Billing::class)
            ->onlyMethods(['httpGet'])
            ->setConstructorArgs([$this->getConfigMock(), self::FAKE_ACCOUNT_ID])
            ->getMock();
    }

    protected function tearDown(): void
    {
        $this->billing = null;

        parent::tearDown();
    }

    public function testGetBillingUsage(): void
    {
        $this->billing->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/billing/usage')
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getExpectedBillingUsageData())));

        $response = $this->billing->getBillingUsage();
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertArrayHasKey('billing', $responseData);
        $this->assertArrayHasKey('testing', $responseData);
        $this->assertArrayHasKey('sending', $responseData);
        
        // Check billing structure
        $this->assertArrayHasKey('cycle_start', $responseData['billing']);
        $this->assertArrayHasKey('cycle_end', $responseData['billing']);
        
        // Check testing structure
        $this->assertArrayHasKey('plan', $responseData['testing']);
        $this->assertArrayHasKey('usage', $responseData['testing']);
        $this->assertEquals('Individual', $responseData['testing']['plan']['name']);
        
        // Check testing usage
        $testingUsage = $responseData['testing']['usage'];
        $this->assertArrayHasKey('sent_messages_count', $testingUsage);
        $this->assertArrayHasKey('forwarded_messages_count', $testingUsage);
        $this->assertEquals(1234, $testingUsage['sent_messages_count']['current']);
        $this->assertEquals(5000, $testingUsage['sent_messages_count']['limit']);
        
        // Check sending structure
        $this->assertArrayHasKey('plan', $responseData['sending']);
        $this->assertArrayHasKey('usage', $responseData['sending']);
        $this->assertEquals('Basic 10K', $responseData['sending']['plan']['name']);
        
        // Check sending usage
        $sendingUsage = $responseData['sending']['usage'];
        $this->assertArrayHasKey('sent_messages_count', $sendingUsage);
        $this->assertEquals(6789, $sendingUsage['sent_messages_count']['current']);
        $this->assertEquals(10000, $sendingUsage['sent_messages_count']['limit']);
    }

    public function testGetBillingUsageForbidden(): void
    {
        $this->billing->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/billing/usage')
            ->willReturn(
                new Response(403, ['Content-Type' => 'application/json'], json_encode(['errors' => 'Access forbidden']))
            );

        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage(
            'Forbidden. Make sure domain verification process is completed or check your permissions. Errors: Access forbidden.'
        );

        $this->billing->getBillingUsage();
    }

    private function getExpectedBillingUsageData(): array
    {
        return [
            'billing' => [
                'cycle_start' => '2024-02-15T21:11:59.624Z',
                'cycle_end' => '2024-02-15T21:11:59.624Z'
            ],
            'testing' => [
                'plan' => [
                    'name' => 'Individual'
                ],
                'usage' => [
                    'sent_messages_count' => [
                        'current' => 1234,
                        'limit' => 5000
                    ],
                    'forwarded_messages_count' => [
                        'current' => 0,
                        'limit' => 100
                    ]
                ]
            ],
            'sending' => [
                'plan' => [
                    'name' => 'Basic 10K'
                ],
                'usage' => [
                    'sent_messages_count' => [
                        'current' => 6789,
                        'limit' => 10000
                    ]
                ]
            ]
        ];
    }
}
