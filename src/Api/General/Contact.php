<?php

declare(strict_types=1);

namespace Mailtrap\Api\General;

use Mailtrap\Api\AbstractApi;
use Mailtrap\ConfigInterface;
use Mailtrap\DTO\Request\Contact\CreateContact;
use Mailtrap\DTO\Request\Contact\UpdateContact;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Contact
 */
class Contact extends AbstractApi implements GeneralInterface
{
    public function __construct(ConfigInterface $config, private int $accountId)
    {
        parent::__construct($config);
    }

    /**
     * Get all Contact Lists.
     *
     * @return ResponseInterface
     */
    public function getAllContactLists(): ResponseInterface
    {
        return $this->handleResponse(
            $this->httpGet($this->getBasePath() . '/lists')
        );
    }

    /**
     * Get a specific Contact List by ID.
     *
     * @param int $listId
     * @return ResponseInterface
     */
    public function getContactList(int $listId): ResponseInterface
    {
        return $this->handleResponse(
            $this->httpGet($this->getBasePath() . '/lists/' . $listId)
        );
    }

    /**
     * Create a new Contact List.
     *
     * @param string $name
     * @return ResponseInterface
     */
    public function createContactList(string $name): ResponseInterface
    {
        return $this->handleResponse(
            $this->httpPost(
                path: $this->getBasePath() . '/lists',
                body: ['name' => $name]
            )
        );
    }

    /**
     * Update an existing Contact List by ID.
     *
     * @param int $listId
     * @param string $name
     * @return ResponseInterface
     */
    public function updateContactList(int $listId, string $name): ResponseInterface
    {
        return $this->handleResponse(
            $this->httpPatch(
                path: $this->getBasePath() . '/lists/' . $listId,
                body: ['name' => $name]
            )
        );
    }

    /**
     * Delete a Contact List by ID.
     *
     * @param int $listId
     * @return ResponseInterface
     */
    public function deleteContactList(int $listId): ResponseInterface
    {
        return $this->handleResponse(
            $this->httpDelete($this->getBasePath() . '/lists/' . $listId)
        );
    }

    /**
     * Get a Contact by ID (UUID)
     *
     * @param string $contactId
     * @return ResponseInterface
     */
    public function getContactById(string $contactId): ResponseInterface
    {
        return $this->getContact($contactId);
    }

    /**
     * Get a Contact by Email.
     *
     * @param string $email
     * @return ResponseInterface
     */
    public function getContactByEmail(string $email): ResponseInterface
    {
        return $this->getContact($email);
    }

    /**
     * Create a new Contact.
     *
     * @param CreateContact $contact
     * @return ResponseInterface
     */
    public function createContact(CreateContact $contact): ResponseInterface
    {
        return $this->handleResponse(
            $this->httpPost(path: $this->getBasePath(), body: ['contact' => $contact->toArray()])
        );
    }

    /**
     * Update an existing Contact by ID (UUID).
     *
     * @param string $contactId
     * @param UpdateContact $contact
     * @return ResponseInterface
     */
    public function updateContactById(string $contactId, UpdateContact $contact): ResponseInterface
    {
        return $this->updateContact($contactId, $contact);
    }

    /**
     * Update an existing Contact by Email.
     *
     * @param string $email
     * @param UpdateContact $contact
     * @return ResponseInterface
     */
    public function updateContactByEmail(string $email, UpdateContact $contact): ResponseInterface
    {
        return $this->updateContact($email, $contact);
    }

    /**
     * Delete a Contact by ID (UUID).
     *
     * @param string $contactId
     * @return ResponseInterface
     */
    public function deleteContactById(string $contactId): ResponseInterface
    {
        return $this->deleteContact($contactId);
    }

    /**
     * Delete a Contact by Email.
     *
     * @param string $email
     * @return ResponseInterface
     */
    public function deleteContactByEmail(string $email): ResponseInterface
    {
        return $this->deleteContact($email);
    }

    public function getAccountId(): int
    {
        return $this->accountId;
    }

    /**
     * Get a Contact by ID or Email.
     *
     * @param string $idOrEmail
     * @return ResponseInterface
     */
    private function getContact(string $idOrEmail): ResponseInterface
    {
        return $this->handleResponse(
            $this->httpGet($this->getBasePath() . '/' . urlencode($idOrEmail))
        );
    }

    /**
     * Update an existing Contact.
     *
     * @param string $contactIdOrEmail
     * @param UpdateContact $contact
     * @return ResponseInterface
     */
    private function updateContact(string $contactIdOrEmail, UpdateContact $contact): ResponseInterface
    {
        return $this->handleResponse(
            $this->httpPut(
                path: $this->getBasePath() . '/' . urlencode($contactIdOrEmail),
                body: ['contact' => $contact->toArray()]
            )
        );
    }

    /**
     * Delete a Contact by ID or Email.
     *
     * @param string $idOrEmail
     * @return ResponseInterface
     */
    private function deleteContact(string $idOrEmail): ResponseInterface
    {
        return $this->handleResponse(
            $this->httpDelete($this->getBasePath() . '/' . urlencode($idOrEmail))
        );
    }

    private function getBasePath(): string
    {
        return sprintf('%s/api/accounts/%s/contacts', $this->getHost(), $this->getAccountId());
    }
}
