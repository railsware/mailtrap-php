<?php

namespace Mailtrap\Api\Sandbox;

use Mailtrap\Api\AbstractApi;
use Mailtrap\DTO\Request\Inbox as InboxRequest;
use Mailtrap\Exception\RuntimeException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Inbox
 */
class Inbox extends AbstractApi implements SandboxInterface
{
    /**
     * Get a list of inboxes.
     *
     * @param int $accountId
     *
     * @return ResponseInterface
     */
    public function getList(int $accountId): ResponseInterface
    {
        return $this->handleResponse($this->httpGet(
            sprintf('%s/api/accounts/%s/inboxes', $this->getHost(), $accountId)
        ));
    }

    /**
     * Get inbox attributes by inbox id. See the list of attributes in the example
     *
     * @param int $accountId
     * @param int $inboxId
     *
     * @return ResponseInterface
     */
    public function getInboxAttributes(int $accountId, int $inboxId): ResponseInterface
    {
        return $this->handleResponse($this->httpGet(
            sprintf('%s/api/accounts/%s/inboxes/%s', $this->getHost(), $accountId, $inboxId)
        ));
    }

    /**
     * Create an inbox in a project.
     *
     * @param int    $accountId
     * @param int    $projectId
     * @param string $inboxName
     *
     * @return ResponseInterface
     */
    public function create(int $accountId, int $projectId, string $inboxName): ResponseInterface
    {
        return $this->handleResponse(
            $this->httpPost(
                sprintf('%s/api/accounts/%s/projects/%s/inboxes', $this->getHost(), $accountId, $projectId),
                [],
                ['inbox' => ['name' => $inboxName]]
            )
        );
    }

    /**
     * Delete an inbox with all its emails.
     *
     * @param int $accountId
     * @param int $inboxId
     *
     * @return ResponseInterface
     */
    public function delete(int $accountId, int $inboxId): ResponseInterface
    {
        return $this->handleResponse($this->httpDelete(
            sprintf('%s/api/accounts/%s/inboxes/%s', $this->getHost(), $accountId, $inboxId)
        ));
    }

    /**
     * Update inbox name and/or inbox email username.
     *
     * @param int          $accountId
     * @param int          $inboxId
     * @param InboxRequest $updateInbox
     *
     * @return ResponseInterface
     */
    public function update(int $accountId, int $inboxId, InboxRequest $updateInbox): ResponseInterface
    {
        return $this->handleResponse($this->httpPatch(
            sprintf('%s/api/accounts/%s/inboxes/%s', $this->getHost(), $accountId, $inboxId),
            [],
            ['inbox' => $this->getUpdatePayload($updateInbox)]
        ));
    }

    /**
     * Delete all messages (emails) from inbox.
     *
     * @param int $accountId
     * @param int $inboxId
     *
     * @return ResponseInterface
     */
    public function clean(int $accountId, int $inboxId): ResponseInterface
    {
        return $this->handleResponse($this->httpPatch(
            sprintf('%s/api/accounts/%s/inboxes/%s/clean', $this->getHost(), $accountId, $inboxId)
        ));
    }

    /**
     * Mark all messages in the inbox as read.
     *
     * @param int $accountId
     * @param int $inboxId
     *
     * @return ResponseInterface
     */
    public function markAsRead(int $accountId, int $inboxId): ResponseInterface
    {
        return $this->handleResponse($this->httpPatch(
            sprintf('%s/api/accounts/%s/inboxes/%s/all_read', $this->getHost(), $accountId, $inboxId)
        ));
    }

    /**
     * Reset SMTP credentials of the inbox.
     *
     * @param int $accountId
     * @param int $inboxId
     *
     * @return ResponseInterface
     */
    public function resetSmtpCredentials(int $accountId, int $inboxId): ResponseInterface
    {
        return $this->handleResponse($this->httpPatch(
            sprintf('%s/api/accounts/%s/inboxes/%s/reset_credentials', $this->getHost(), $accountId, $inboxId)
        ));
    }

    /**
     * Turn the email address of the inbox on/off.
     *
     * @param int $accountId
     * @param int $inboxId
     *
     * @return ResponseInterface
     */
    public function toggleEmailAddress(int $accountId, int $inboxId): ResponseInterface
    {
        return $this->handleResponse($this->httpPatch(
            sprintf('%s/api/accounts/%s/inboxes/%s/toggle_email_username', $this->getHost(), $accountId, $inboxId)
        ));
    }

    /**
     * Reset username of email address per inbox.
     *
     * @param int $accountId
     * @param int $inboxId
     *
     * @return ResponseInterface
     */
    public function resetEmailAddress(int $accountId, int $inboxId): ResponseInterface
    {
        return $this->handleResponse($this->httpPatch(
            sprintf('%s/api/accounts/%s/inboxes/%s/reset_email_username', $this->getHost(), $accountId, $inboxId)
        ));
    }

    private function getUpdatePayload(InboxRequest $updateInbox): array
    {
        $result = $updateInbox->toArray();
        if (empty($result)) {
            throw new RuntimeException('At least one inbox parameter should be populated ("name" or "email_username")');
        }

        return $result;
    }
}
