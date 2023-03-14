<?php

declare(strict_types=1);

namespace Mailtrap\Api;

use Mailtrap\ConfigInterface;
use Mailtrap\Exception\HttpClientException;
use Mailtrap\Exception\HttpException;
use Mailtrap\Exception\HttpServerException;
use Mailtrap\Exception\InvalidArgumentException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AbstractApi
 */
abstract class AbstractApi
{
    public const DEFAULT_HOST = 'mailtrap.io';
    public const SENDMAIL_HOST = 'send.api.mailtrap.io';
    public const SENDMAIL_SANDBOX_HOST = 'sandbox.api.mailtrap.io';

    protected ConfigInterface $config;
    protected ClientInterface $httpClient;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
        $this->httpClient = $this->config->getHttpClientBuilder()->getHttpClient();
    }

    protected function get(string $path, array $requestHeaders = []): ResponseInterface
    {
        return $this->httpClient->get($this->addDefaultScheme($path), $requestHeaders);
    }

    protected function post(string $path, array $requestHeaders = [], ?array $body = null): ResponseInterface
    {
        return $this->httpClient->post(
            $this->addDefaultScheme($path),
            $requestHeaders,
            !empty($body) ? $this->jsonEncode($body) : null
        );
    }

    protected function put(string $path, array $requestHeaders = [], ?array $body = null): ResponseInterface
    {
        return $this->httpClient->put(
            $this->addDefaultScheme($path),
            $requestHeaders,
            !empty($body) ? $this->jsonEncode($body) : null
        );
    }

    protected function patch(string $path, array $requestHeaders = [], ?array $body = null): ResponseInterface
    {
        return $this->httpClient->patch(
            $this->addDefaultScheme($path),
            $requestHeaders,
            !empty($body) ? $this->jsonEncode($body) : null
        );
    }

    protected function delete(string $path, array $requestHeaders = [], ?array $body = null): ResponseInterface
    {
        return $this->httpClient->delete(
            $this->addDefaultScheme($path),
            $requestHeaders,
            !empty($body) ? $this->jsonEncode($body) : null
        );
    }

    protected function getHost(): string
    {
        return $this->config->getHost() ?: self::DEFAULT_HOST;
    }

    protected function handleResponse(ResponseInterface $response): ResponseInterface
    {
        $statusCode = $response->getStatusCode();
        switch (true) {
            case $statusCode >= 200 && $statusCode < 300:
                // Everything fine
                break;
            case $statusCode >= 400 && $statusCode < 500:
                throw HttpClientException::createFromResponse($response);
            case $statusCode >= 500:
                throw new HttpServerException(
                    sprintf('Internal Server Error. HTTP response code ("%d") received from the API server. Retry later or contact support.', $statusCode),
                    $statusCode
                );
            default:
                throw new HttpException(
                    sprintf('An unexpected error occurred. HTTP response code ("%d") received.', $statusCode),
                    $statusCode
                );

        }

        return $response;
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

    private function addDefaultScheme(string $path): string
    {
        return empty(parse_url($path, PHP_URL_SCHEME)) ? 'https://' . $path : $path;
    }
}
