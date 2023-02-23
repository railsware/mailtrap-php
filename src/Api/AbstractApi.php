<?php

declare(strict_types=1);

namespace Mailtrap\Api;

use Mailtrap\Config;

/**
 * Class AbstractApi
 */
abstract class AbstractApi
{
    public const DEFAULT_HOST = 'https://mailtrap.io';
    public const SENDMAIL_HOST = 'https://send.api.mailtrap.io';
    public const SENDMAIL_SANDBOX_HOST = 'https://sandbox.api.mailtrap.io';

    protected Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    protected function get(string $path, array $requestHeaders = [])
    {
        return $this->config->getHttpClientBuilder()->getHttpClient()->get($path, $requestHeaders);
    }

    protected function getHost(): string
    {
        return $this->config->getHost() ?: self::DEFAULT_HOST;
    }
}