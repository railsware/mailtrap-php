<?php

declare(strict_types=1);

namespace Mailtrap\Api\Sandbox;

use Mailtrap\Api\AbstractEmails;
use Mailtrap\ConfigInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Mime\Email;

/**
 * Class Emails
 */
class Emails extends AbstractEmails implements SandboxInterface
{
    public function __construct(ConfigInterface $config, private int $inboxId)
    {
        parent::__construct($config);
    }

    public function send(Email $email): ResponseInterface
    {
        return $this->handleResponse(
            $this->httpPost(sprintf('%s/api/send/%s', $this->getHost(), $this->getInboxId()), [], $this->getPayload($email))
        );
    }

    public function batchSend(Email $baseEmail, array $recipientEmails): ResponseInterface
    {
        return $this->handleResponse(
            $this->httpPost(
                sprintf('%s/api/batch/%s', $this->getHost(), $this->getInboxId()),
                [],
                [
                    'base' => $this->getBatchBasePayload($baseEmail),
                    'requests' => array_map(
                        fn(Email $email) => $this->getPayload($email),
                        $recipientEmails
                    ),
                ]
            )
        );
    }

    protected function getHost(): string
    {
        return $this->config->getHost() ?: self::SENDMAIL_SANDBOX_HOST;
    }

    public function getInboxId(): int
    {
        return $this->inboxId;
    }
}
