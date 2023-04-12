<?php

declare(strict_types=1);

namespace Mailtrap\Tests\Api\General;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Api\General\Account;
use Mailtrap\Exception\HttpClientException;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\Tests\MailtrapTestCase;
use Nyholm\Psr7\Response;

/**
 * @covers Account
 *
 * Class AccountTest
 */
class AccountTest extends MailtrapTestCase
{
    /**
     * @var AccountTest
     */
    private $account;

    protected function setUp(): void
    {
        parent::setUp();

        $this->account = $this->getMockBuilder(Account::class)
            ->onlyMethods(['httpGet'])
            ->setConstructorArgs([$this->getConfigMock()])
            ->getMock()
        ;
    }

    protected function tearDown(): void
    {
        $this->account = null;

        parent::tearDown();
    }

    public function testValidGetAll(): void
    {
        $expectedData = [
            [
                "id" => 26730,
                "name" => "James",
                "access_levels" => [
                    100
                ]
            ],
            [
                "id" => 26731,
                "name" => "John",
                "access_levels" => [
                    1000
                ]
            ]
        ];

        $this->account->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts')
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($expectedData)));

        $response = $this->account->getList();
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(2, $responseData);
        $this->assertArrayHasKey('access_levels', array_shift($responseData));
    }

    public function testInvalidGetAll(): void
    {
        $expectedData = ['error' => 'Incorrect API token'];

        $this->account->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts')
            ->willReturn(new Response(401, ['Content-Type' => 'application/json'], json_encode($expectedData)));

        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage(
            'Unauthorized. Make sure you are sending correct credentials with the request before retrying. Errors: Incorrect API token.'
        );

        $this->account->getList();
    }
}
