<?php

namespace Mailtrap\Api\Sandbox;

use Mailtrap\Api\AbstractEmails;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Inbox
 */
class Inbox extends AbstractEmails implements SandboxInterface
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
}
