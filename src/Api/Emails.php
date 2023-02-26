<?php

declare(strict_types=1);

namespace Mailtrap\Api;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Mime\Email;

/**
 * Class Emails
 */
class Emails extends AbstractApi
{
    public function send(Email $email, array $headers = []): ResponseInterface
    {
        return $this->post($this->getHost() . '/api/send', $headers, $this->getPayload($email));
    }

    public function sendToSandbox(int $inboxId, Email $email, array $headers = []): ResponseInterface
    {
        return $this->post(
            sprintf('%s/api/send/%s', $this->getSandBoxHost($this->getHost()), $inboxId),
            $headers,
            $this->getPayload($email)
        );
    }

    protected function getHost(): string
    {
        return $this->config->getHost() ?: self::SENDMAIL_HOST;
    }

    /**
     * By default, prod and sandbox have different URLs
     * https://send.api.mailtrap.io/api/send & https://sandbox.api.mailtrap.io/api/send/{inbox_id}
     *
     * Also DEV can use mack server URL
     *
     * @param string $host
     *
     * @return string
     */
    private function getSandBoxHost(string $host): string
    {
        return $host === self::SENDMAIL_HOST ? self::SENDMAIL_SANDBOX_HOST : $host;
    }

    private function getPayload(Email $email): array
    {
        // TODO
        return [];
    }
}