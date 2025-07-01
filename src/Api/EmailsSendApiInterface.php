<?php

declare(strict_types=1);

namespace Mailtrap\Api;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Mime\Email;

interface EmailsSendApiInterface
{
    public function send(Email $email): ResponseInterface;

    /**
     * Sends a batch of emails.
     *
     * @param Email[] $recipientEmails The list of emails. Each of them requires recipients (one of to, cc, or bcc). Each email inherits properties from base but can override them.
     * @param Email|null $baseEmail General properties of all emails in the batch. Each of them can be overridden in requests for individual emails.
     *
     * @return ResponseInterface The response from the API.
     */
    public function batchSend(array $recipientEmails, ?Email $baseEmail = null): ResponseInterface;
}

