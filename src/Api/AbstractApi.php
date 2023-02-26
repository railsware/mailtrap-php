<?php

declare(strict_types=1);

namespace Mailtrap\Api;

use Mailtrap\ConfigInterface;
use Mailtrap\Exception\InvalidArgumentException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AbstractApi
 */
abstract class AbstractApi
{
    public const DEFAULT_HOST = 'https://mailtrap.io';
    public const SENDMAIL_HOST = 'https://send.api.mailtrap.io';
    public const SENDMAIL_SANDBOX_HOST = 'https://sandbox.api.mailtrap.io';

    protected ConfigInterface $config;
    protected ClientInterface $httpClient;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
        $this->httpClient = $this->config->getHttpClientBuilder()->getHttpClient();
    }

    protected function get(string $path, array $requestHeaders = []): ResponseInterface
    {
        return $this->httpClient->get($path, $requestHeaders);
    }

    protected function post(string $path, array $requestHeaders = [], ?array $body = null): ResponseInterface
    {
        return $this->httpClient->post(
            $path,
            $requestHeaders,
            !empty($body) ? $this->jsonEncode($body) : null
        );
    }

    protected function getHost(): string
    {
        return $this->config->getHost() ?: self::DEFAULT_HOST;
    }

    /**
     * @param mixed    $value
     * @param int|null $flags
     * @param int      $maxDepth
     *
     * @return string
     */
    private function jsonEncode($value, int $flags = null, int $maxDepth = 512): string
    {
        $flags ??= \JSON_HEX_TAG | \JSON_HEX_APOS | \JSON_HEX_AMP | \JSON_HEX_QUOT | \JSON_PRESERVE_ZERO_FRACTION;

        try {
            $value = json_encode($value, $flags | \JSON_THROW_ON_ERROR, $maxDepth);
        } catch (\JsonException $e) {
            throw new InvalidArgumentException('Invalid value for "json" option: ' . $e->getMessage());
        }

        return $value;
    }
}