<?php

declare(strict_types=1);

namespace Mailtrap\Api\Sandbox;

use Mailtrap\Api\AbstractApi;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Message
 */
class Message extends AbstractApi implements SandboxInterface
{
    /**
     * Get all messages in the inbox
     * Note: if you want to get all messages you need to use "page" param (by default will return only 30 messages per
     * page)
     *
     * @param int         $accountId
     * @param int         $inboxId
     * @param int|null    $page          page of emails (per page = 30 messages, does not work with last_id param)
     * @param string|null $search        filter emails by this key; it works like case insensitive
     *                                   pattern matching by subject, to_email, to_name, namely if any of these fields
     *                                   at least start with the given search query, the response will return a result
     * @param int|null    $lastMessageId get emails, where primary key is less then this param (does not work with page
     *                                   param)
     *
     * @return ResponseInterface
     */
    public function getList(
        int $accountId,
        int $inboxId,
        ?int $page = null,
        ?string $search = null,
        ?int $lastMessageId = null
    ): ResponseInterface {
        $parameters = [];

        if ($page !== null) {
            $parameters['page'] = $page;
        }

        if ($search !== null) {
            $parameters['search'] = $search;
        }

        if ($lastMessageId !== null) {
            $parameters['last_id'] = $lastMessageId;
        }

        return $this->handleResponse($this->httpGet(
            sprintf('%s/api/accounts/%s/inboxes/%s/messages', $this->getHost(), $accountId, $inboxId),
            $parameters
        ));
    }

    /**
     * Get email message by ID.
     *
     * @param int $accountId
     * @param int $inboxId
     * @param int $messageId
     *
     * @return ResponseInterface
     */
    public function getById(int $accountId, int $inboxId, int $messageId): ResponseInterface
    {
        return $this->handleResponse($this->httpGet(
            sprintf('%s/api/accounts/%s/inboxes/%s/messages/%s', $this->getHost(), $accountId, $inboxId, $messageId)
        ));
    }

    /**
     * Get a brief spam report by message ID.
     *
     * @param int $accountId
     * @param int $inboxId
     * @param int $messageId
     *
     * @return ResponseInterface
     */
    public function getSpamScore(int $accountId, int $inboxId, int $messageId): ResponseInterface
    {
        return $this->handleResponse($this->httpGet(sprintf(
            '%s/api/accounts/%s/inboxes/%s/messages/%s/spam_report',
            $this->getHost(),
            $accountId,
            $inboxId,
            $messageId
        )));
    }

    /**
     * Get a brief HTML report by message ID.
     *
     * @param int $accountId
     * @param int $inboxId
     * @param int $messageId
     *
     * @return ResponseInterface
     */
    public function getHtmlAnalysis(int $accountId, int $inboxId, int $messageId): ResponseInterface
    {
        return $this->handleResponse($this->httpGet(sprintf(
            '%s/api/accounts/%s/inboxes/%s/messages/%s/analyze',
            $this->getHost(),
            $accountId,
            $inboxId,
            $messageId
        )));
    }

    /**
     * Get text email body, if it exists.
     *
     * @param int $accountId
     * @param int $inboxId
     * @param int $messageId
     *
     * @return ResponseInterface
     */
    public function getText(int $accountId, int $inboxId, int $messageId): ResponseInterface
    {
        return $this->handleResponse($this->httpGet(sprintf(
            '%s/api/accounts/%s/inboxes/%s/messages/%s/body.txt',
            $this->getHost(),
            $accountId,
            $inboxId,
            $messageId
        )));
    }

    /**
     * Get raw email body.
     *
     * @param int $accountId
     * @param int $inboxId
     * @param int $messageId
     *
     * @return ResponseInterface
     */
    public function getRaw(int $accountId, int $inboxId, int $messageId): ResponseInterface
    {
        return $this->handleResponse($this->httpGet(sprintf(
            '%s/api/accounts/%s/inboxes/%s/messages/%s/body.raw',
            $this->getHost(),
            $accountId,
            $inboxId,
            $messageId
        )));
    }

    /**
     * Get formatted HTML email body. Not applicable for plain text emails.
     *
     * @param int $accountId
     * @param int $inboxId
     * @param int $messageId
     *
     * @return ResponseInterface
     */
    public function getHtml(int $accountId, int $inboxId, int $messageId): ResponseInterface
    {
        return $this->handleResponse($this->httpGet(sprintf(
            '%s/api/accounts/%s/inboxes/%s/messages/%s/body.html',
            $this->getHost(),
            $accountId,
            $inboxId,
            $messageId
        )));
    }

    /**
     * Get email message in .eml format.
     *
     * @param int $accountId
     * @param int $inboxId
     * @param int $messageId
     *
     * @return ResponseInterface
     */
    public function getEml(int $accountId, int $inboxId, int $messageId): ResponseInterface
    {
        return $this->handleResponse($this->httpGet(sprintf(
            '%s/api/accounts/%s/inboxes/%s/messages/%s/body.eml',
            $this->getHost(),
            $accountId,
            $inboxId,
            $messageId
        )));
    }

    /**
     * Get HTML source of email.
     *
     * @param int $accountId
     * @param int $inboxId
     * @param int $messageId
     *
     * @return ResponseInterface
     */
    public function getSource(int $accountId, int $inboxId, int $messageId): ResponseInterface
    {
        return $this->handleResponse($this->httpGet(sprintf(
            '%s/api/accounts/%s/inboxes/%s/messages/%s/body.htmlsource',
            $this->getHost(),
            $accountId,
            $inboxId,
            $messageId
        )));
    }

    /**
     * Update message attributes (right now only the is_read attribute is available for modification).
     *
     * @param int  $accountId
     * @param int  $inboxId
     * @param int  $messageId
     * @param bool $isRead
     *
     * @return ResponseInterface
     */
    public function markAsRead(int $accountId, int $inboxId, int $messageId, bool $isRead = true): ResponseInterface
    {
        return $this->handleResponse($this->httpPatch(
            sprintf('%s/api/accounts/%s/inboxes/%s/messages/%s', $this->getHost(), $accountId, $inboxId, $messageId),
            [],
            [
                'message' => [
                    'is_read' => $isRead
                ]
            ]
        ));
    }

    /**
     * Delete message from inbox.
     *
     * @param int $accountId
     * @param int $inboxId
     * @param int $messageId
     *
     * @return ResponseInterface
     */
    public function delete(int $accountId, int $inboxId, int $messageId): ResponseInterface
    {
        return $this->handleResponse($this->httpDelete(
            sprintf('%s/api/accounts/%s/inboxes/%s/messages/%s', $this->getHost(), $accountId, $inboxId, $messageId)
        ));
    }
}
