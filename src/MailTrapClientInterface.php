<?php

declare(strict_types=1);

namespace Mailtrap;

/**
 * @method  Api\Sandbox\SandboxEmails|Api\Sending\SendingEmails  emails
 */
interface MailTrapClientInterface
{
    public function __construct(ConfigInterface $config);

    public function getConfig(): ConfigInterface;

    public function getApiClassByName(string $name): ?string;
}
