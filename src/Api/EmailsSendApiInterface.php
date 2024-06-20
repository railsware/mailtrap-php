<?php

declare(strict_types=1);

namespace Mailtrap\Api;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Mime\Email;

interface EmailsSendApiInterface
{
    public function send(Email $email): ResponseInterface;
}

