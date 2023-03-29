<?php

declare(strict_types=1);

namespace Mailtrap\Bridge\Transport;

use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClientInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Exception\RuntimeException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\MessageConverter;

/**
 * Class MailtrapApiTransport
 */
class MailtrapApiTransport extends AbstractTransport
{
    private MailtrapClientInterface $mailTrapClient;
    private ?int $inboxId;

    public function __construct(
        MailtrapClientInterface $mailTrapClient,
        int $inboxId = null,
        EventDispatcherInterface $dispatcher = null,
        LoggerInterface $logger = null
    ) {
        parent::__construct($dispatcher, $logger);

        $this->mailTrapClient = $mailTrapClient;
        $this->inboxId = $inboxId;
    }

    public function __toString(): string
    {
        return sprintf('mailtrap+api://%s', $this->getEndpoint());
    }

    protected function doSend(SentMessage $message): void
    {
        try {
            $email = MessageConverter::toEmail($message->getOriginalMessage());
            $envelope = $message->getEnvelope();

            // overrides from the envelope
            $email->from($envelope->getSender());
            $envelopeRecipients = $this->getEnvelopeRecipients($email, $envelope);
            if (!empty($envelopeRecipients)) {
                foreach ($envelopeRecipients as $envelopeRecipient) {
                    $email->addTo($envelopeRecipient);
                }
            }

            $response = $this->mailTrapClient->emails()->send($email, $this->inboxId);

            $body = ResponseHelper::toArray($response);
            $message->setMessageId(implode(',', $body['message_ids']));
        } catch (\Exception $e) {
            throw new RuntimeException(
                sprintf('Unable to send message with the "%s" transport: ', __CLASS__) . $e->getMessage(), 0, $e
            );
        }
    }

    private function getEndpoint(): string
    {
        return $this->mailTrapClient->getConfig()->getHost() . (null === $this->inboxId ? '' : '?inboxId=' . $this->inboxId);
    }

    private function getEnvelopeRecipients(Email $email, Envelope $envelope): array
    {
        return array_filter(
            $envelope->getRecipients(),
            static fn (Address $address) => false === in_array($address, array_merge($email->getTo(), $email->getCc(), $email->getBcc()), true)
        );
    }
}