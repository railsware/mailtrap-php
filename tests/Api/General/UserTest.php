<?php

declare(strict_types=1);

namespace Mailtrap\Tests\Api\General;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Api\General\Account;
use Mailtrap\Api\General\User;
use Mailtrap\Exception\HttpClientException;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\Tests\MailtrapTestCase;
use Nyholm\Psr7\Response;

/**
 * @covers Account
 *
 * Class UserTest
 */
class UserTest extends MailtrapTestCase
{
    /**
     * @var User
     */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->getMockBuilder(User::class)
            ->onlyMethods(['httpGet', 'httpDelete'])
            ->setConstructorArgs([$this->getConfigMock()])
            ->getMock()
        ;
    }

    protected function tearDown(): void
    {
        $this->user = null;

        parent::tearDown();
    }

    public function testValidGetListWithoutFilters(): void
    {
        $this->user->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/account_accesses')
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getExpectedData())));

        $response = $this->user->getList(self::FAKE_ACCOUNT_ID);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(3, $responseData);
        $this->assertArrayHasKey('specifier_type', array_shift($responseData));
    }

    public function testValidGetListWithFilters(): void
    {
        $inboxIds = [2090015, 2157025];
        $projectIds = [1515592];
        $expectedResult = $this->getExpectedData();

        $this->user->expects($this->once())
            ->method('httpGet')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/account_accesses',
                ['inbox_ids' => $inboxIds, 'project_ids' => $projectIds]
            )
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode(array_slice($expectedResult, 1))));

        $response = $this->user->getList(self::FAKE_ACCOUNT_ID, $inboxIds, $projectIds);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(2, $responseData);
        $this->assertArrayHasKey('specifier_type', array_shift($responseData));
    }

    public function test401InvalidGetList(): void
    {
        $this->user->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/account_accesses')
            ->willReturn(new Response(401, ['Content-Type' => 'application/json'], json_encode(['error' => 'Incorrect API token'])));

        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage(
            'Unauthorized. Make sure you are sending correct credentials with the request before retrying. Errors: Incorrect API token.'
        );

        $this->user->getList(self::FAKE_ACCOUNT_ID);
    }

    public function test403InvalidGetList(): void
    {
        $this->user->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/account_accesses')
            ->willReturn(new Response(403, ['Content-Type' => 'application/json'], json_encode(['errors' => 'Access forbidden'])));

        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage(
            'Forbidden. Make sure domain verification process is completed or check your permissions. Errors: Access forbidden.'
        );

        $this->user->getList(self::FAKE_ACCOUNT_ID);
    }

    public function testValidRemove()
    {
        $this->user->expects($this->once())
            ->method('httpDelete')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/account_accesses/' . self::FAKE_ACCOUNT_ACCESS_ID)
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode(['id' => self::FAKE_ACCOUNT_ACCESS_ID])));

        $response = $this->user->delete(self::FAKE_ACCOUNT_ID, self::FAKE_ACCOUNT_ACCESS_ID);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals(self::FAKE_ACCOUNT_ACCESS_ID, $responseData['id']);
    }

    public function test401InvalidRemove(): void
    {
        $this->user->expects($this->once())
            ->method('httpDelete')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/account_accesses/' . self::FAKE_ACCOUNT_ACCESS_ID)
            ->willReturn(new Response(401, ['Content-Type' => 'application/json'], json_encode(['error' => 'Incorrect API token'])));

        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage(
            'Unauthorized. Make sure you are sending correct credentials with the request before retrying. Errors: Incorrect API token.'
        );

        $this->user->delete(self::FAKE_ACCOUNT_ID, self::FAKE_ACCOUNT_ACCESS_ID);
    }

    public function test403InvalidRemove(): void
    {
        $this->user->expects($this->once())
            ->method('httpDelete')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/account_accesses/' . self::FAKE_ACCOUNT_ACCESS_ID)
            ->willReturn(new Response(403, ['Content-Type' => 'application/json'], json_encode(['error' => 'Access forbidden'])));

        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage(
            'Forbidden. Make sure domain verification process is completed or check your permissions. Errors: Access forbidden.'
        );

        $this->user->delete(self::FAKE_ACCOUNT_ID, self::FAKE_ACCOUNT_ACCESS_ID);
    }

    public function test404InvalidRemove(): void
    {
        $this->user->expects($this->once())
            ->method('httpDelete')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/account_accesses/' . self::FAKE_ACCOUNT_ACCESS_ID)
            ->willReturn(new Response(404, ['Content-Type' => 'application/json'], json_encode(['error' => 'Not Found'])));

        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage(
            'The requested entity has not been found. Errors: Not Found.'
        );

        $this->user->delete(self::FAKE_ACCOUNT_ID, self::FAKE_ACCOUNT_ACCESS_ID);
    }

    private function getExpectedData(): array
    {
        return [
            [
                "id" => 4773,
                "specifier_type" => "User",
                "resources" => [
                    [
                        "resource_type" => "account",
                        "resource_id" => 3229,
                        "access_level" => 1000
                    ]
                ],
                "specifier" => [
                    "id" => 2077,
                    "email" => "james@mailtrap.io",
                    "name" => "James"
                ],
                "permissions" => [
                    "can_read" => false,
                    "can_update" => false,
                    "can_destroy" => false,
                    "can_leave" => false
                ]
            ],
            [
                "id" => 4775,
                "specifier_type" => "User",
                "resources" => [
                    [
                        "resource_type" => "account",
                        "resource_id" => 3229,
                        "access_level" => 100
                    ]
                ],
                "specifier" => [
                    "id" => 3230,
                    "email" => "john@mailtrap.io",
                    "name" => "John"
                ],
                "permissions" => [
                    "can_read" => false,
                    "can_update" => true,
                    "can_destroy" => true,
                    "can_leave" => false
                ]
            ],
            [
                "id" => 4776,
                "specifier_type" => "Invite",
                "resources" => [
                    [
                        "resource_type" => "project",
                        "resource_id" => 3938,
                        "access_level" => 10
                    ],
                    [
                        "resource_type" => "inbox",
                        "resource_id" => 3757,
                        "access_level" => 100
                    ]
                ],
                "specifier" => [
                    "id" => 64,
                    "email" => "mary@mailtrap.io"
                ],
                "permissions" => [
                    "can_read" => false,
                    "can_update" => true,
                    "can_destroy" => true,
                    "can_leave" => false
                ]
            ]
        ];
    }
}
