<?php

declare(strict_types=1);

namespace Mailtrap\Api\Sandbox;

use Mailtrap\Api\AbstractEmails;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Mime\Email;

/**
 * Class Emails
 */
class Emails extends AbstractEmails implements SandboxInterface
{
    public function send(Email $email, int $inboxId): ResponseInterface
    {
        return $this->handleResponse(
            $this->httpPost(sprintf('%s/api/send/%s', $this->getHost(), $inboxId), [], $this->getPayload($email))
        );
    }

    protected function getHost(): string
    {
        return $this->config->getHost() ?: self::SENDMAIL_SANDBOX_HOST;
    }
}
