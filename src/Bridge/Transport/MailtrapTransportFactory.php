<?php

declare(strict_types=1);

namespace Mailtrap\Bridge\Transport;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Config;
use Mailtrap\EmailsSendMailtrapClientInterface;
use Mailtrap\Exception\RuntimeException;
use Mailtrap\Exception\Transport\UnsupportedHostException;
use Mailtrap\MailtrapClient;
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

        $emailsSendMailtrapClient = $this->getEmailsSendMailTrapClient($config);
        if ($emailsSendMailtrapClient instanceof MailtrapSandboxClient) {
            if (null === $inboxId) {
                throw new RuntimeException(
                    'You cannot send an email to a sandbox with an empty "inboxId" parameter. Example -> "MAILER_DSN=mailtrap+api://APIKEY@sandbox.api.mailtrap.io?inboxId=1234"'
                );
            }

            $emailsSendApiLayer = $emailsSendMailtrapClient->emails($inboxId);
        } else {
            $emailsSendApiLayer = $emailsSendMailtrapClient->emails();
        }

        return new MailtrapApiTransport($emailsSendApiLayer, $config, $this->dispatcher, $this->logger);
    }

    protected function getSupportedSchemes(): array
    {
        return ['mailtrap', 'mailtrap+api'];
    }

    private function getEmailsSendMailTrapClient(Config $config): EmailsSendMailtrapClientInterface
    {
        $layer = $this->determineLayerNameByHost($config->getHost());

        return (new MailtrapClient($config))->{$layer}();
    }

    private function determineLayerNameByHost(string $host): string
    {
        $hostLayers = [
            AbstractApi::SENDMAIL_TRANSACTIONAL_HOST => MailtrapClient::LAYER_TRANSACTIONAL_SENDING,
            AbstractApi::SENDMAIL_BULK_HOST => MailtrapClient::LAYER_BULK_SENDING,
            AbstractApi::SENDMAIL_SANDBOX_HOST => MailtrapClient::LAYER_SANDBOX,
        ];

        foreach ($hostLayers as $hostKey => $layer) {
            if (stripos($host, $hostKey) !== false) {
                return $layer;
            }
        }

        throw new UnsupportedHostException(
            sprintf(
                'The "%s" host is not supported. Only these are available: %s',
                $host,
                implode(
                    ', ',
                    [
                        AbstractApi::SENDMAIL_TRANSACTIONAL_HOST,
                        AbstractApi::SENDMAIL_BULK_HOST,
                        AbstractApi::SENDMAIL_SANDBOX_HOST
                    ]
            ))
        );
    }
}
