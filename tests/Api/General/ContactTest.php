<?php

namespace Mailtrap\Tests\Api\General;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Api\General\Contact;
use Mailtrap\DTO\Request\Contact\CreateContact;
use Mailtrap\DTO\Request\Contact\UpdateContact;
use Mailtrap\DTO\Request\Contact\ImportContact;
use Mailtrap\Exception\HttpClientException;
use Mailtrap\Exception\InvalidArgumentException;
use Mailtrap\Tests\MailtrapTestCase;
use Nyholm\Psr7\Response;
use Mailtrap\Helper\ResponseHelper;

/**
 * @covers Contact
 *
 * Class ContactTest
 */
class ContactTest extends MailtrapTestCase
{
    /**
     * @var Contact|null
     */
    private ?Contact $contact;

    protected function setUp(): void
    {
        parent::setUp();

        $this->contact = $this->getMockBuilder(Contact::class)
            ->onlyMethods(['httpGet', 'httpPost', 'httpPut', 'httpPatch', 'httpDelete'])
            ->setConstructorArgs([$this->getConfigMock(), self::FAKE_ACCOUNT_ID])
            ->getMock();
    }

    protected function tearDown(): void
    {
        $this->contact = null;

        parent::tearDown();
    }

    public function testGetAllContactLists(): void
    {
        $this->contact->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/lists')
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getExpectedContactLists())));

        $response = $this->contact->getAllContactLists();
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(2, $responseData);
        $this->assertArrayHasKey('id', $responseData[0]);
    }

    public function testGetContactList(): void
    {
        $contactListId = 1;

        $this->contact->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/lists/' . $contactListId)
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getExpectedContactLists()[0])));

        $response = $this->contact->getContactList($contactListId);
        $responseData = ResponseHelper::toArray($response);

        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals($contactListId, $responseData['id']);
    }

    public function testGetContactListNotFound(): void
    {
        $contactListId = 999; // Non-existent ID for testing

        $this->contact->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/lists/' . $contactListId)
            ->willReturn(
                new Response(404, ['Content-Type' => 'application/json'], json_encode(['error' => 'Not Found']))
            );

        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage('Errors: Not Found.');

        $this->contact->getContactList($contactListId);
    }

    public function testCreateContactList(): void
    {
        $contactListName = 'List 1';

        $this->contact->expects($this->once())
            ->method('httpPost')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/lists',
                [],
                ['name' => $contactListName]
            )
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getExpectedContactLists()[0])));

        $response = $this->contact->createContactList($contactListName);
        $responseData = ResponseHelper::toArray($response);

        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals($contactListName, $responseData['name']);
    }

    public function testCreateContactListRateLimitExceeded(): void
    {
        $contactListName = 'List 1';

        $this->contact->expects($this->once())
            ->method('httpPost')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/lists',
                [],
                ['name' => $contactListName]
            )
            ->willReturn(
                new Response(429, ['Content-Type' => 'application/json'], json_encode(['errors' => 'Rate limit exceeded']))
            );

        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage('Errors: Rate limit exceeded.');

        $this->contact->createContactList($contactListName);
    }

    public function testUpdateContactList(): void
    {
        $contactListId = 2;
        $newContactListName = 'List 2';

        $this->contact->expects($this->once())
            ->method('httpPatch')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/lists/' . $contactListId,
                [],
                ['name' => $newContactListName]
            )
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getExpectedContactLists()[1])));

        $response = $this->contact->updateContactList($contactListId, $newContactListName);
        $responseData = ResponseHelper::toArray($response);

        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals($newContactListName, $responseData['name']);
    }

    public function testDeleteContactList(): void
    {
        $contactListId = 1;

        $this->contact->expects($this->once())
            ->method('httpDelete')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/lists/' . $contactListId)
            ->willReturn(new Response(204));

        $response = $this->contact->deleteContactList($contactListId);

        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testGetContactById(): void
    {
        $contactId = '019706a8-9612-77be-8586-4f26816b467a';

        $this->contact->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/' . $contactId)
            ->willReturn(
                new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getExpectedContactData()))
            );

        $response = $this->contact->getContactById($contactId);
        $responseData = ResponseHelper::toArray($response)['data'];

        $this->assertInstanceOf(Response::class, $response);
        $this->assertArrayHasKey('email', $responseData);
        $this->assertEquals('test@example.com', $responseData['email']);
    }

    public function testGetContactByEmail(): void
    {
        $contactEmail = 'test@example.com';

        $this->contact->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/' . urlencode($contactEmail))
            ->willReturn(
                new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getExpectedContactData()))
            );

        $response = $this->contact->getContactByEmail($contactEmail);
        $responseData = ResponseHelper::toArray($response)['data'];

        $this->assertInstanceOf(Response::class, $response);
        $this->assertArrayHasKey('email', $responseData);
        $this->assertEquals($contactEmail, $responseData['email']);
    }

    public function testCreateContact(): void
    {
        $fakeEmail = 'test@example.com';
        $contactDTO = new CreateContact('test@example.com', ['first_name' => 'John'], [1, 2]);

        $this->contact->expects($this->once())
            ->method('httpPost')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts',
                [],
                ['contact' => $contactDTO->toArray()]
            )
            ->willReturn(
                new Response(201, ['Content-Type' => 'application/json'], json_encode($this->getExpectedContactData()))
            );

        $response = $this->contact->createContact($contactDTO);
        $responseData = ResponseHelper::toArray($response)['data'];

        $this->assertInstanceOf(Response::class, $response);
        $this->assertArrayHasKey('email', $responseData);
        $this->assertEquals($fakeEmail, $responseData['email']);
    }

    public function testUpdateContactById(): void
    {
        $contactId = '019706a8-9612-77be-8586-4f26816b467a';
        $contactDTO = new UpdateContact('test@example.com', ['last_name' => 'Smith'], [3], [1, 2], true);

        $this->contact->expects($this->once())
            ->method('httpPut')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/' . $contactId,
                [],
                ['contact' => $contactDTO->toArray()]
            )
            ->willReturn(
                new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getExpectedUpdateContactData()))
            );

        $response = $this->contact->updateContactById($contactId, $contactDTO);
        $responseResult = ResponseHelper::toArray($response);
        $responseData = $responseResult['data'];

        $this->assertInstanceOf(Response::class, $response);
        $this->assertArrayHasKey('data', $responseResult);
        $this->assertArrayHasKey('action', $responseResult);
        $this->assertEquals('updated', $responseResult['action']);

        $this->assertEquals($contactId, $responseData['id']);
        $this->assertEquals('Smith', $responseData['fields']['last_name']);
        $this->assertEquals([3], $responseData['list_ids']);
    }

    public function testUpdateContactByEmail(): void
    {
        $contactEmail = 'test@example.com';
        $contactDTO = new UpdateContact('test@example.com', ['last_name' => 'Smith'], [3], [1, 2], true);

        $this->contact->expects($this->once())
            ->method('httpPut')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/' . urlencode($contactEmail),
                [],
                ['contact' => $contactDTO->toArray()]
            )
            ->willReturn(
                new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getExpectedUpdateContactData()))
            );

        $response = $this->contact->updateContactByEmail($contactEmail, $contactDTO);
        $responseData = ResponseHelper::toArray($response)['data'];

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals($contactEmail, $responseData['email']);
    }

    public function testUpdateContactExcludesNullUnsubscribed(): void
    {
        $contactEmail = 'test@example.com';
        $contactDTO = new UpdateContact($contactEmail, ['last_name' => 'Smith'], [3], [1, 2]);

        $this->contact->expects($this->once())
            ->method('httpPut')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/' . urlencode($contactEmail),
                [],
                $this->callback(function (array $payload) {
                    $this->assertArrayHasKey('contact', $payload);
                    $this->assertArrayNotHasKey('unsubscribed', $payload['contact']);
                    return true;
                })
            )
            ->willReturn(
                new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getExpectedUpdateContactData()))
            );

        $response = $this->contact->updateContactByEmail($contactEmail, $contactDTO);
        $responseData = ResponseHelper::toArray($response)['data'];

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('unsubscribed', $responseData['status']);
    }

    public function testDeleteContactById(): void
    {
        $contactId = '019706a8-9612-77be-8586-4f26816b467a';
        $this->contact->expects($this->once())
            ->method('httpDelete')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/' . $contactId)
            ->willReturn(new Response(204));

        $response = $this->contact->deleteContactById($contactId);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testDeleteContactByEmail(): void
    {
        $contactEmail = 'test@example.com';
        $this->contact->expects($this->once())
            ->method('httpDelete')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/' . urlencode($contactEmail))
            ->willReturn(new Response(204));

        $response = $this->contact->deleteContactByEmail($contactEmail);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testInvalidCreateContact(): void
    {
        $invalidContactDTO = new CreateContact('invalid-email');

        $this->contact->expects($this->once())
            ->method('httpPost')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts',
                [],
                ['contact' => $invalidContactDTO->toArray()]
            )
            ->willReturn(
                new Response(
                    422,
                    ['Content-Type' => 'application/json'],
                    json_encode(['errors' => ['email' => ['is invalid']]])
                )
            );

        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage('Errors: email -> is invalid.');

        $this->contact->createContact($invalidContactDTO);
    }

    public function testGetAllContactFields(): void
    {
        $this->contact->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/fields')
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getExpectedContactFields())));

        $response = $this->contact->getAllContactFields();
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(2, $responseData);
        $this->assertArrayHasKey('id', $responseData[0]);
        $this->assertArrayHasKey('name', $responseData[0]);
        $this->assertArrayHasKey('data_type', $responseData[0]);
        $this->assertArrayHasKey('merge_tag', $responseData[0]);
    }

    public function testGetContactField(): void
    {
        $fieldId = 1;

        $this->contact->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/fields/' . $fieldId)
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getExpectedContactFields()[0])));

        $response = $this->contact->getContactField($fieldId);
        $responseData = ResponseHelper::toArray($response);

        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals($fieldId, $responseData['id']);
    }

    public function testCreateContactField(): void
    {
        $fieldData = ['name' => 'Custom Field', 'data_type' => 'text', 'merge_tag' => 'my_contact_field'];

        $this->contact->expects($this->once())
            ->method('httpPost')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/fields',
                [],
                $fieldData
            )
            ->willReturn(new Response(201, ['Content-Type' => 'application/json'], json_encode($this->getExpectedContactFields()[0])));

        $response = $this->contact->createContactField($fieldData['name'], $fieldData['data_type'], $fieldData['merge_tag']);
        $responseData = ResponseHelper::toArray($response);

        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals($fieldData['name'], $responseData['name']);
    }

    public function testCreateContactFieldMultipleValidationErrors(): void
    {
        $fieldData = ['name' => 'Duplicate Field', 'data_type' => 'text', 'merge_tag' => 'duplicate_merge_tag'];
        $errors = [
            'errors' => [
                'name' => ['has already been taken'],
                'merge_tag' => ['has already been taken'],
            ],
        ];

        $this->contact->expects($this->once())
            ->method('httpPost')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/fields',
                [],
                $fieldData
            )
            ->willReturn(
                new Response(422, ['Content-Type' => 'application/json'], json_encode($errors))
            );

        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage('Errors: name -> has already been taken. merge_tag -> has already been taken.');

        $this->contact->createContactField($fieldData['name'], $fieldData['data_type'], $fieldData['merge_tag']);
    }

    public function testUpdateContactField(): void
    {
        $fieldId = 1;
        $fieldData = ['name' => 'Updated Field', 'merge_tag' => 'my_contact_field_new'];

        $this->contact->expects($this->once())
            ->method('httpPatch')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/fields/' . $fieldId,
                [],
                $fieldData
            )
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getExpectedContactFields()[1])));

        $response = $this->contact->updateContactField($fieldId, $fieldData['name'], $fieldData['merge_tag']);
        $responseData = ResponseHelper::toArray($response);

        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals($fieldData['name'], $responseData['name']);
        $this->assertEquals($fieldData['merge_tag'], $responseData['merge_tag']);
    }

    public function testDeleteContactField(): void
    {
        $fieldId = 1;

        $this->contact->expects($this->once())
            ->method('httpDelete')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/fields/' . $fieldId)
            ->willReturn(new Response(204));

        $response = $this->contact->deleteContactField($fieldId);

        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testImportContacts(): void
    {
        $contacts = [
            new ImportContact(
                email: 'customer1@example.com',
                fields: ['first_name' => 'John', 'last_name' => 'Smith', 'zip_code' => 11111],
                listIdsIncluded: [1, 2, 3],
                listIdsExcluded: [4, 5, 6]
            ),
            new ImportContact(
                email: 'customer2@example.com',
                fields: ['first_name' => 'Joe', 'last_name' => 'Doe', 'zip_code' => 22222],
                listIdsIncluded: [1],
                listIdsExcluded: [4]
            ),
        ];

        $expectedResponse = [
            'id' => 1,
            'status' => 'created',
        ];

        $this->contact->expects($this->once())
            ->method('httpPost')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/imports',
                [],
                ['contacts' => array_map(fn(ImportContact $contact) => $contact->toArray(), $contacts)]
            )
            ->willReturn(new Response(201, ['Content-Type' => 'application/json'], json_encode($expectedResponse)));

        $response = $this->contact->importContacts($contacts);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals(1, $responseData['id']);
        $this->assertEquals('created', $responseData['status']);
    }

    public function testGetContactImportInProgress(): void
    {
        $importId = 1;
        $expectedResponse = [
            'id' => $importId,
            'status' => 'created',
        ];

        $this->contact->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/imports/' . $importId)
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($expectedResponse)));

        $response = $this->contact->getContactImport($importId);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals($importId, $responseData['id']);
        $this->assertEquals('created', $responseData['status']);
    }

    public function testGetContactImportFinished(): void
    {
        $importId = 1;
        $expectedResponse = [
            'id' => $importId,
            'status' => 'finished',
            'created_contacts_count' => 2,
            'updated_contacts_count' => 0,
            'contacts_over_limit_count' => 0,
        ];

        $this->contact->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/imports/' . $importId)
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($expectedResponse)));

        $response = $this->contact->getContactImport($importId);
        $responseData = ResponseHelper::toArray($response);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals($importId, $responseData['id']);
        $this->assertEquals('finished', $responseData['status']);
        $this->assertEquals(2, $responseData['created_contacts_count']);
        $this->assertEquals(0, $responseData['updated_contacts_count']);
        $this->assertEquals(0, $responseData['contacts_over_limit_count']);
    }

    public function testGetContactImportNotFound(): void
    {
        $importId = 999;

        $this->contact->expects($this->once())
            ->method('httpGet')
            ->with(AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/imports/' . $importId)
            ->willReturn(
                new Response(404, ['Content-Type' => 'application/json'], json_encode(['error' => 'Not Found']))
            );

        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage('Errors: Not Found.');

        $this->contact->getContactImport($importId);
    }

    public function testImportContactsValidationError(): void
    {
        $contacts = [
            new ImportContact(
                email: 'invalid-email',
                fields: ['first_name' => 'John'],
                listIdsIncluded: [],
                listIdsExcluded: []
            ),
        ];

        $expectedResponse = [
            'errors' => [
                [
                    'email' => 'invalid-email',
                    'errors' => [
                        'email' => [
                            'is invalid',
                            'top level domain is too short',
                        ],
                    ],
                ],
            ],
        ];

        $this->contact->expects($this->once())
            ->method('httpPost')
            ->with(
                AbstractApi::DEFAULT_HOST . '/api/accounts/' . self::FAKE_ACCOUNT_ID . '/contacts/imports',
                [],
                ['contacts' => array_map(fn(ImportContact $contact) => $contact->toArray(), $contacts)]
            )
            ->willReturn(
                new Response(422, ['Content-Type' => 'application/json'], json_encode($expectedResponse))
            );

        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage('Errors: email -> invalid-email. errors -> email -> is invalid. top level domain is too short.');

        $this->contact->importContacts($contacts);
    }

    public function testImportContactsThrowsExceptionForInvalidInput(): void
    {
        $contacts = [
            new ImportContact(
                email: 'valid@example.com',
                fields: ['first_name' => 'John'],
                listIdsIncluded: [1],
                listIdsExcluded: []
            ),
            // Invalid input
            new UpdateContact(
                email: 'valid@example.com',
                fields: ['first_name' => 'John'],
                listIdsIncluded: [1],
                listIdsExcluded: []
            ),
        ];

        $this->contact->expects($this->never())->method('httpPost');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Each contact must be an instance of ImportContact.');

        $this->contact->importContacts($contacts);
    }

    private function getExpectedContactFields(): array
    {
        return [
            ['id' => 1, 'name' => 'Custom Field', 'data_type' => 'text', 'merge_tag' => 'my_contact_field'],
            ['id' => 2, 'name' => 'Updated Field', 'data_type' => 'text', 'merge_tag' => 'my_contact_field_new'],
        ];
    }

    private function getExpectedContactLists(): array
    {
        return [
            ['id' => 1, 'name' => 'List 1'],
            ['id' => 2, 'name' => 'List 2'],
        ];
    }

    private function getExpectedContactData(): array
    {
        return [
            'data' => [
                'id' => '019706a8-9612-77be-8586-4f26816b467a',
                'email' => 'test@example.com',
                'created_at' => 1748163401202,
                'updated_at' => 1748163401202,
                'list_ids' => [1, 2],
                'status' => 'subscribed',
                'fields' => [
                    'first_name' => 'John',
                    'last_name' => null,
                ],
            ],
        ];
    }

    private function getExpectedUpdateContactData(): array
    {
        return [
            'data' => [
                'id' => '019706a8-9612-77be-8586-4f26816b467a',
                'email' => 'test@example.com',
                'created_at' => 1748163401202,
                'updated_at' => 1748163401202,
                'list_ids' => [3],
                'status' => 'unsubscribed',
                'fields' => [
                    'first_name' => 'John',
                    'last_name' => 'Smith',
                ],
            ],
            'action' => 'updated',
        ];
    }
}
