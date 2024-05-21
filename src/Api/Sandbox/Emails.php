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

    protected function getHost(): string
    {
        return $this->config->getHost() ?: self::SENDMAIL_SANDBOX_HOST;
    }

    public function getInboxId(): int
    {
        return $this->inboxId;
    }
}
