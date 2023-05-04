<?php

namespace Mailtrap\Api\Sandbox;

use Mailtrap\Api\AbstractApi;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Attachment
 */
class Attachment extends AbstractApi
{
    /**
     * Get message attachments by inboxId and messageId.
     *
     * @param int $accountId
     * @param int $inboxId
     * @param int $messageId
     *
     * @return ResponseInterface
     */
    public function getMessageAttachments(int $accountId, int $inboxId, int $messageId): ResponseInterface
    {
        return $this->handleResponse($this->httpGet(sprintf(
            '%s/api/accounts/%s/inboxes/%s/messages/%s/attachments',
            $this->getHost(),
            $accountId,
            $inboxId,
            $messageId
        )));
    }

    /**
     * Get message single attachment by id.
     *
     * @param int $accountId
     * @param int $inboxId
     * @param int $messageId
     * @param int $attachmentId
     *
     * @return ResponseInterface
     */
    public function getMessageAttachment(int $accountId, int $inboxId, int $messageId, int $attachmentId): ResponseInterface
    {
        return $this->handleResponse($this->httpGet(sprintf(
            '%s/api/accounts/%s/inboxes/%s/messages/%s/attachments/%s',
            $this->getHost(),
            $accountId,
            $inboxId,
            $messageId,
            $attachmentId
        )));
    }
}
