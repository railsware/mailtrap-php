<?php

declare(strict_types=1);

namespace Mailtrap;

use Mailtrap\HttpClient\HttpClientBuilder;
use Mailtrap\HttpClient\HttpClientBuilderInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * Class Config
 */
class Config implements ConfigInterface
{
    private string $apiToken;
    private ?ClientInterface $httpClient = null;
    private ?HttpClientBuilderInterface $httpClientBuilder = null;
    private ?RequestFactoryInterface $requestFactory = null;
    private ?StreamFactoryInterface $streamFactory = null;
    private ?string $host = null;
    private bool $responseThrowOnError = true;

    public function __construct(string $apiToken)
    {
        $this->apiToken = $apiToken;
    }

    public function getApiToken(): string
    {
        return $this->apiToken;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setHost(?string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getHttpClientBuilder(): HttpClientBuilderInterface
    {
        if (null === $this->httpClientBuilder) {
            $this->httpClientBuilder = new HttpClientBuilder(
                $this->apiToken,
                $this->httpClient,
                $this->requestFactory,
                $this->streamFactory
            );
        }

        return $this->httpClientBuilder;
    }

    public function setHttpClientBuilder(?HttpClientBuilderInterface $httpClientBuilder): self
    {
        $this->httpClientBuilder = $httpClientBuilder;

        return $this;
    }

    public function setHttpClient(?ClientInterface $httpClient): self
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    public function setRequestFactory(?RequestFactoryInterface $requestFactory): self
    {
        $this->requestFactory = $requestFactory;

        return $this;
    }

    public function setStreamFactory(?StreamFactoryInterface $streamFactory): self
    {
        $this->streamFactory = $streamFactory;

        return $this;
    }

    public function isResponseThrowOnError(): bool
    {
        return $this->responseThrowOnError;
    }

    /**
     * Throw an HttpException if the response returns a status other than 20x (default: true)
     * otherwise a response will be returned
     * 
     * @param bool $responseThrowOnError
     *
     * @return $this
     */
    public function setResponseThrowOnError(bool $responseThrowOnError): self
    {
        $this->responseThrowOnError = $responseThrowOnError;

        return $this;
    }
}
