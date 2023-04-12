<?php

namespace Mailtrap\Tests\Api\Sandbox;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Api\Sandbox\Project;
use Mailtrap\Exception\HttpClientException;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\Tests\MailtrapTestCase;
use Nyholm\Psr7\Response;

/**
 * @covers Project
 *
 * Class ProjectTest
 */
class ProjectTest extends MailtrapTestCase
{
    /**
     * @var Project
     */
    private $project;

    protected function setUp(): void
    {
        parent::setUp();

        $this->project = $this->getMockBuilder(Project::class)
            ->onlyMethods(['httpGet', 'httpPost',  'httpPatch', 'httpDelete'])
            ->setConstructorArgs([$this->getConfigMock()])
            ->getMock()
        ;
    }

    protected function tearDown(): void
    {
        $this->project = null;

        parent::tearDown();
    }

    public function testValidGetList(): void
    {
        $this->project->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/projects')
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getExpectedData())));

        $response = $this->project->getList(self::FAKE_ACCOUNT_ID);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(2, $responseData);
        $this->assertArrayHasKey('inboxes', array_shift($responseData));
    }

    public function testValidGetById(): void
    {
        $this->project->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/projects/' . self::FAKE_PROJECT_ID)
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getExpectedData()[1])));

        $response = $this->project->getById(self::FAKE_ACCOUNT_ID, self::FAKE_PROJECT_ID);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals(self::FAKE_PROJECT_ID, $responseData['id']);
        $this->assertEquals('Admin Project', $responseData['name']);
        $this->assertCount(1, $responseData['inboxes']);
    }

    public function testValidCreate(): void
    {
        $expectedData = [
            "id" => self::FAKE_PROJECT_ID,
            "name" => "My New Project",
            "share_links" => [
                "admin" => "https://localhost/projects/2388/share/QEVuQwEAS-hQfYV1hqw15p7p9VcsuCrASDQUkcuV_N5G-zdVBPrd58V8Ce09AUl-JGFEJsQbt-K6IzRIMhjpU7zxAnIo9QpB8mJwuOqBiottfwdDhgKO-ab7t7WDCyyoGeG5dLK_",
                "viewer" => "https://localhost/projects/2388/share/QEVuQwEAS-hQfYV1hqw15p7p9VcsuCrASDQUkcuV_N5G-zdVBPrd58V8Ce09AUl-JGFEJsQbHDFqI6Mk2wRVlVSxLNMgI-3LnWpSSSyCSbjqkQRAMPVoTPYF7WcXfw4H_CwkAc4e"
            ],
            "inboxes" => [
            ],
            "permissions" => [
                "can_read" => true,
                "can_update" => true,
                "can_destroy" => true,
                "can_leave" => false
            ]
        ];

        $this->project->expects($this->once())
            ->method('httpPost')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/projects',
                [],
                ['project' => ['name' => $expectedData['name']]]
            )
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($expectedData)));

        $response = $this->project->create(self::FAKE_ACCOUNT_ID, $expectedData['name']);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals(self::FAKE_PROJECT_ID, $responseData['id']);
    }

    public function testInvalidCreate(): void
    {
        $errorMsg = [
            "error" => [
                "name" => [
                    "is too short (minimum is 2 characters)",
                ]
            ]
        ];
        $this->project->expects($this->once())
            ->method('httpPost')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/projects')
            ->willReturn(new Response(422, ['Content-Type' => 'application/json'], json_encode($errorMsg)));

        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage(
            'Errors: name -> is too short (minimum is 2 characters).'
        );

        $this->project->create(self::FAKE_ACCOUNT_ID, 'a');
    }

    public function testValidDelete(): void
    {
        $this->project->expects($this->once())
            ->method('httpDelete')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/projects/' . self::FAKE_PROJECT_ID)
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode(['id' => self::FAKE_PROJECT_ID])));

        $response = $this->project->delete(self::FAKE_ACCOUNT_ID, self::FAKE_PROJECT_ID);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals(self::FAKE_PROJECT_ID, $responseData['id']);
    }

    public function testValidUpdateName(): void
    {
        $expectedData = [
            "id" => self::FAKE_PROJECT_ID,
            "name" => "Updated name",
            "share_links" => [
                "admin" => "https://localhost/projects/2388/share/QEVuQwEAS-hQfYV1hqw15p7p9VcsuCrASDQUkcuV_N5G-zdVBPrd58V8Ce09AUl-JGFEJsQbt-K6IzRIMhjpU7zxAnIo9QpB8mJwuOqBiottfwdDhgKO-ab7t7WDCyyoGeG5dLK_",
                "viewer" => "https://localhost/projects/2388/share/QEVuQwEAS-hQfYV1hqw15p7p9VcsuCrASDQUkcuV_N5G-zdVBPrd58V8Ce09AUl-JGFEJsQbHDFqI6Mk2wRVlVSxLNMgI-3LnWpSSSyCSbjqkQRAMPVoTPYF7WcXfw4H_CwkAc4e"
            ],
            "inboxes" => [
            ],
            "permissions" => [
                "can_read" => true,
                "can_update" => true,
                "can_destroy" => true,
                "can_leave" => false
            ]
        ];

        $this->project->expects($this->once())
            ->method('httpPatch')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/projects/' . self::FAKE_PROJECT_ID,
                [],
                ['project' => ['name' => $expectedData['name']]]
            )
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($expectedData)));

        $response = $this->project->updateName(self::FAKE_ACCOUNT_ID, self::FAKE_PROJECT_ID, $expectedData['name']);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals(self::FAKE_PROJECT_ID, $responseData['id']);
    }

    public function testInvalidUpdateName(): void
    {
        $longName = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
        $errorMsg = [
            "error" => [
                "name" => [
                    "is too long (maximum is 100 characters)",
                ]
            ]
        ];
        $this->project->expects($this->once())
            ->method('httpPatch')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/projects/' . self::FAKE_PROJECT_ID,
                [],
                ['project' => ['name' => $longName]]
            )
            ->willReturn(new Response(422, ['Content-Type' => 'application/json'], json_encode($errorMsg)));

        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage(
            'Errors: name -> is too long (maximum is 100 characters).'
        );

        $this->project->updateName(self::FAKE_ACCOUNT_ID,  self::FAKE_PROJECT_ID, $longName);
    }

    private function getExpectedData(): array
    {
        return [
            [
                "id" => 2435,
                "name" => "Viewer Project",
                "inboxes" => [
                ],
                "permissions" => [
                    "can_read" => true,
                    "can_update" => false,
                    "can_destroy" => false,
                    "can_leave" => true
                ]
            ],
            [
                "id" => 2436,
                "name" => "Admin Project",
                "share_links" => [
                    "admin" => "https://localhost/projects/2436/share/QEVuQwEAYWN-NIhmrB8qX6ZWzToqdOE9dw1ylIfOQ-_fKwYAD_CCkhRzmAanker219x25PM75TBaHgx1I4dsv058hghY_Ewaa3Vt7ET1aeg3ufVcit919V4baPLzxV9JaRzXWSjK",
                    "viewer" => "https://localhost/projects/2436/share/QEVuQwEAYWN-NIhmrB8qX6ZWzToqdOE9dw1ylIfOQ-_fKwYAD_CCkhRzmAanker219x25PM7YSu12_li4QtOGl363syqCpKX7vlXvIub7b_V1BU2qypVHfspL7qfSIja0edd7hSh"
                ],
                "inboxes" => [
                    [
                        "id" => 3874,
                        "name" => "Admin Inbox",
                        "username" => "f796d276ca4d13",
                        "password" => "293caeb0f100e4",
                        "max_size" => 0,
                        "status" => "active",
                        "email_username" => "bcf11679c1-ad3171",
                        "email_username_enabled" => false,
                        "sent_messages_count" => 316,
                        "forwarded_messages_count" => 0,
                        "used" => false,
                        "forward_from_email_address" => "a3343-i3874@forward.mailtrap.info",
                        "project_id" => 2436,
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
                            "can_leave" => false
                        ]
                    ]
                ],
                "permissions" => [
                    "can_read" => true,
                    "can_update" => true,
                    "can_destroy" => true,
                    "can_leave" => true
                ]
            ]
        ];
    }
}
