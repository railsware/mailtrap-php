<?php

declare(strict_types=1);

namespace Mailtrap\Api\Sending;

use Mailtrap\Api\AbstractEmails;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Mime\Email;

/**
 * Class SendingEmails
 */
class SendingEmails extends AbstractEmails implements SendingInterface
{
    public function send(Email $email): ResponseInterface
    {
        return $this->handleResponse(
            $this->post($this->getHost() . '/api/send', [], $this->getPayload($email))
        );
    }

    protected function getHost(): string
    {
        return $this->config->getHost() ?: self::SENDMAIL_HOST;
    }
}
