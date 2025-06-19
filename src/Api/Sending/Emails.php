<?php

declare(strict_types=1);

namespace Mailtrap\Api\Sending;

use Mailtrap\Api\AbstractEmails;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Mime\Email;

/**
 * Class Emails
 */
class Emails extends AbstractEmails implements SendingInterface
{
    public function send(Email $email): ResponseInterface
    {
        return $this->handleResponse(
            $this->httpPost($this->getHost() . '/api/send', [], $this->getPayload($email))
        );
    }

    public function batchSend(Email $baseEmail, array $recipientEmails): ResponseInterface
    {
        return $this->handleResponse(
            $this->httpPost(
                sprintf('%s/api/batch', $this->getHost()),
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
        return $this->config->getHost() ?: self::SENDMAIL_TRANSACTIONAL_HOST;
    }
}
