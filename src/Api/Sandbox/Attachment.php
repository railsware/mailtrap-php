<?php

declare(strict_types=1);

namespace Mailtrap\Api\Sandbox;

use Mailtrap\Api\AbstractApi;
use Mailtrap\ConfigInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Attachment
 */
class Attachment extends AbstractApi implements SandboxInterface
{
    public function __construct(
        ConfigInterface $config,
        private int $accountId,
        private int $inboxId,
    ) {
        parent::__construct($config);
    }

    /**
     * Get message attachments by inboxId and messageId.
     *
     * @param int         $messageId
     * @param string|null $attachmentType
     *
     * @return ResponseInterface
     */
    public function getMessageAttachments(
        int $messageId,
        ?string $attachmentType = null
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
                $this->getAccountId(),
                $this->getInboxId(),
                $messageId
            ),
            $parameters
        ));
    }

    /**
     * Get message single attachment by id.
     *
     * @param int $messageId
     * @param int $attachmentId
     *
     * @return ResponseInterface
     */
    public function getMessageAttachment(int $messageId, int $attachmentId): ResponseInterface
    {
        return $this->handleResponse($this->httpGet(sprintf(
            '%s/api/accounts/%s/inboxes/%s/messages/%s/attachments/%s',
            $this->getHost(),
            $this->getAccountId(),
            $this->getInboxId(),
            $messageId,
            $attachmentId
        )));
    }

    public function getAccountId(): int
    {
        return $this->accountId;
    }

    public function getInboxId(): int
    {
        return $this->inboxId;
    }
}
