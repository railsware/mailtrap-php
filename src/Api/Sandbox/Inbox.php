<?php

declare(strict_types=1);

namespace Mailtrap\Api\Sandbox;

use Mailtrap\Api\AbstractApi;
use Mailtrap\ConfigInterface;
use Mailtrap\DTO\Request\Inbox as InboxRequest;
use Mailtrap\Exception\RuntimeException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Inbox
 */
class Inbox extends AbstractApi implements SandboxInterface
{
    public function __construct(ConfigInterface $config, private int $accountId)
    {
        parent::__construct($config);
    }

    /**
     * Get a list of inboxes.
     *
     * @return ResponseInterface
     */
    public function getList(): ResponseInterface
    {
        return $this->handleResponse($this->httpGet(
            sprintf('%s/api/accounts/%s/inboxes', $this->getHost(), $this->getAccountId())
        ));
    }

    /**
     * Get inbox attributes by inbox id. See the list of attributes in the example
     *
     * @param int $inboxId
     *
     * @return ResponseInterface
     */
    public function getInboxAttributes(int $inboxId): ResponseInterface
    {
        return $this->handleResponse($this->httpGet(
            sprintf('%s/api/accounts/%s/inboxes/%s', $this->getHost(), $this->getAccountId(), $inboxId)
        ));
    }

    /**
     * Create an inbox in a project.
     *
     * @param int    $projectId
     * @param string $inboxName
     *
     * @return ResponseInterface
     */
    public function create(int $projectId, string $inboxName): ResponseInterface
    {
        return $this->handleResponse(
            $this->httpPost(
                sprintf('%s/api/accounts/%s/projects/%s/inboxes', $this->getHost(), $this->getAccountId(), $projectId),
                [],
                ['inbox' => ['name' => $inboxName]]
            )
        );
    }

    /**
     * Delete an inbox with all its emails.
     *
     * @param int $inboxId
     *
     * @return ResponseInterface
     */
    public function delete(int $inboxId): ResponseInterface
    {
        return $this->handleResponse($this->httpDelete(
            sprintf('%s/api/accounts/%s/inboxes/%s', $this->getHost(), $this->getAccountId(), $inboxId)
        ));
    }

    /**
     * Update inbox name and/or inbox email username.
     *
     * @param int          $inboxId
     * @param InboxRequest $updateInbox
     *
     * @return ResponseInterface
     */
    public function update(int $inboxId, InboxRequest $updateInbox): ResponseInterface
    {
        return $this->handleResponse($this->httpPatch(
            sprintf('%s/api/accounts/%s/inboxes/%s', $this->getHost(), $this->getAccountId(), $inboxId),
            [],
            ['inbox' => $this->getUpdatePayload($updateInbox)]
        ));
    }

    /**
     * Delete all messages (emails) from inbox.
     *
     * @param int $inboxId
     *
     * @return ResponseInterface
     */
    public function clean(int $inboxId): ResponseInterface
    {
        return $this->handleResponse($this->httpPatch(
            sprintf('%s/api/accounts/%s/inboxes/%s/clean', $this->getHost(), $this->getAccountId(), $inboxId)
        ));
    }

    /**
     * Mark all messages in the inbox as read.
     *
     * @param int $inboxId
     *
     * @return ResponseInterface
     */
    public function markAsRead(int $inboxId): ResponseInterface
    {
        return $this->handleResponse($this->httpPatch(
            sprintf('%s/api/accounts/%s/inboxes/%s/all_read', $this->getHost(), $this->getAccountId(), $inboxId)
        ));
    }

    /**
     * Reset SMTP credentials of the inbox.
     *
     * @param int $inboxId
     *
     * @return ResponseInterface
     */
    public function resetSmtpCredentials(int $inboxId): ResponseInterface
    {
        return $this->handleResponse($this->httpPatch(
            sprintf('%s/api/accounts/%s/inboxes/%s/reset_credentials', $this->getHost(), $this->getAccountId(), $inboxId)
        ));
    }

    /**
     * Turn the email address of the inbox on/off.
     *
     * @param int $inboxId
     *
     * @return ResponseInterface
     */
    public function toggleEmailAddress(int $inboxId): ResponseInterface
    {
        return $this->handleResponse($this->httpPatch(
            sprintf('%s/api/accounts/%s/inboxes/%s/toggle_email_username', $this->getHost(), $this->getAccountId(), $inboxId)
        ));
    }

    /**
     * Reset username of email address per inbox.
     *
     * @param int $inboxId
     *
     * @return ResponseInterface
     */
    public function resetEmailAddress(int $inboxId): ResponseInterface
    {
        return $this->handleResponse($this->httpPatch(
            sprintf('%s/api/accounts/%s/inboxes/%s/reset_email_username', $this->getHost(), $this->getAccountId(), $inboxId)
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

    public function getAccountId(): int
    {
        return $this->accountId;
    }
}
