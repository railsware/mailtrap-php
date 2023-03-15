<?php

declare(strict_types=1);

namespace Mailtrap\Integration\Symfony\Transport;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Config;
use Mailtrap\Exception\RuntimeException;
use Mailtrap\MailTrapSandboxClient;
use Mailtrap\MailTrapSendingClient;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Component\Mailer\Exception\UnsupportedSchemeException;
use Symfony\Component\Mailer\Transport\AbstractTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\TransportInterface;

/**
 * Class MailTrapTransportFactory
 */
final class MailTrapTransportFactory extends AbstractTransportFactory
{
    public function create(Dsn $dsn): TransportInterface
    {
        if (!in_array($dsn->getScheme(), $this->getSupportedSchemes())) {
            throw new UnsupportedSchemeException($dsn, 'mailtrap', $this->getSupportedSchemes());
        }

        $inboxId = !empty($dsn->getOption('inboxId')) ? (int) $dsn->getOption('inboxId') : null;
        $config = (new Config($this->getUser($dsn)))
            ->setHost('default' === $dsn->getHost() ? AbstractApi::SENDMAIL_HOST : $dsn->getHost())
            ->setHttpClient(null === $this->client ? null : new Psr18Client($this->client))
        ;
        $mailTrapClient = str_contains($config->getHost(), AbstractApi::SENDMAIL_HOST)
            ? new MailTrapSendingClient($config)
            : new MailTrapSandboxClient($config);

        if ($mailTrapClient instanceof MailTrapSandboxClient && null === $inboxId) {
            throw new RuntimeException(
                'You cannot send email to the SanBox without "inboxId" param. Example -> "MAILER_DSN=mailtrap+api://APIKEY@sandbox.api.mailtrap.io?inboxId=1234"'
            );
        }

        return new MailTrapApiTransport($mailTrapClient, $inboxId, $this->dispatcher, $this->logger);
    }

    protected function getSupportedSchemes(): array
    {
        return ['mailtrap', 'mailtrap+api'];
    }
}
