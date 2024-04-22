<?php

declare(strict_types=1);

namespace Mailtrap\Bridge\Transport;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Config;
use Mailtrap\Exception\RuntimeException;
use Mailtrap\MailtrapClient;
use Mailtrap\MailtrapClientInterface;
use Mailtrap\MailtrapSandboxClient;
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
            ->setHost('default' === $dsn->getHost() ? AbstractApi::SENDMAIL_TRANSACTIONAL_HOST : $dsn->getHost())
            ->setHttpClient(null === $this->client ? null : new Psr18Client($this->client))
        ;

        $mailtrapClient = $this->getMailtrapClient($config);
        if ($mailtrapClient instanceof MailtrapSandboxClient && null === $inboxId) {
            throw new RuntimeException(
                'You cannot send email to the sandbox with empty "inboxId" param. Example -> "MAILER_DSN=mailtrap+api://APIKEY@sandbox.api.mailtrap.io?inboxId=1234"'
            );
        }

        return new MailtrapApiTransport($mailtrapClient, $inboxId, $this->dispatcher, $this->logger);
    }

    protected function getSupportedSchemes(): array
    {
        return ['mailtrap', 'mailtrap+api'];
    }

    private function getMailtrapClient(Config $config): MailtrapClientInterface
    {
        $layer = $this->determineLayerByHost($config->getHost());

        return (new MailtrapClient($config))->{$layer}();
    }

    private function determineLayerByHost(string $host): string
    {
        if (stripos($host, AbstractApi::SENDMAIL_TRANSACTIONAL_HOST) !== false) {
            return MailtrapClient::LAYER_TRANSACTIONAL_SENDING;
        }

        if (stripos($host, AbstractApi::SENDMAIL_BULK_HOST) !== false) {
            return MailtrapClient::LAYER_BULK_SENDING;
        }

        return MailtrapClient::LAYER_SANDBOX;
    }
}
