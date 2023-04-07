<?php

declare(strict_types=1);

namespace Mailtrap\Bridge\Transport;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Config;
use Mailtrap\Exception\RuntimeException;
use Mailtrap\MailtrapClient;
use Mailtrap\MailtrapSandboxClient;
use Mailtrap\MailtrapSendingClient;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Component\Mailer\Exception\UnsupportedSchemeException;
use Symfony\Component\Mailer\Transport\AbstractTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\TransportInterface;

/**
 * Class MailtrapTransportFactory
 */
final class MailtrapTransportFactory extends AbstractTransportFactory
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
        $mailtrapClient = stripos($config->getHost(), AbstractApi::SENDMAIL_HOST) !== false
            ? (new MailtrapClient($config))->sending()
            : (new MailtrapClient($config))->sandbox();

        if ($mailtrapClient instanceof MailtrapSandboxClient && null === $inboxId) {
            throw new RuntimeException(
                'You cannot send email to the SanBox with empty "inboxId" param. Example -> "MAILER_DSN=mailtrap+api://APIKEY@sandbox.api.mailtrap.io?inboxId=1234"'
            );
        }

        return new MailtrapApiTransport($mailtrapClient, $inboxId, $this->dispatcher, $this->logger);
    }

    protected function getSupportedSchemes(): array
    {
        return ['mailtrap', 'mailtrap+api'];
    }
}
