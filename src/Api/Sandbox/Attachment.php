<?php

namespace Mailtrap\Api\Sandbox;

use Mailtrap\Api\AbstractApi;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Attachment
 */
class Attachment extends AbstractApi implements SandboxInterface
{
    /**
     * Get message attachments by inboxId and messageId.
     *
     * @param int         $accountId
     * @param int         $inboxId
     * @param int         $messageId
     * @param string|null $attachmentType
     *
     * @return ResponseInterface
     */
    public function getMessageAttachments(
        int $accountId,
        int $inboxId,
        int $messageId,
        string $attachmentType = null
    ): ResponseInterface {
        $parameters = [];
        if (!empty($attachmentType)) {
            $parameters = [
                'attachment_type' => $attachmentType
            ];
        }

        return $this->handleResponse($this->httpGet(
            sprintf(
                '%s/api/accounts/%s/inboxes/%s/messages/%s/attachments',
                $this->getHost(),
                $accountId,
                $inboxId,
                $messageId
            ),
            $parameters
        ));
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
