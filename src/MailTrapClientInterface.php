<?php

declare(strict_types=1);

namespace Mailtrap;

interface MailTrapClientInterface
{
    public function __construct(ConfigInterface $config);

    public function getApiClassByName(string $name): ?string;
}