<?php

declare(strict_types=1);

namespace Mailtrap;

use Mailtrap\HttpClient\HttpClientBuilder;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * Class Config
 */
class Config
{
    private string $apiToken;

    private ?ClientInterface $httpClient = null;

    private ?HttpClientBuilder $httpClientBuilder = null;

    private ?RequestFactoryInterface $requestFactory = null;

    private ?StreamFactoryInterface $streamFactory = null;

    private ?string $host = null;

    public function setApiToken(string $apiToken): Config
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setHost(string $host): Config
    {
        $this->host = $host;

        return $this;
    }

    public function getHttpClientBuilder(): HttpClientBuilder
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

    public function setHttpClient(ClientInterface $httpClient): self
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    public function setRequestFactory(RequestFactoryInterface $requestFactory): self
    {
        $this->requestFactory = $requestFactory;

        return $this;
    }

    public function setStreamFactory(StreamFactoryInterface $streamFactory): self
    {
        $this->streamFactory = $streamFactory;

        return $this;
    }
}