<?php

declare(strict_types=1);

namespace Mailtrap\HttpClient;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClientFactory;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\Authentication\Bearer;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * Class Builder
 */
class HttpClientBuilder implements HttpClientBuilderInterface
{
    private ClientInterface $httpClient;
    private RequestFactoryInterface $requestFactory;
    private StreamFactoryInterface $streamFactory;
    private ?HttpMethodsClientInterface $pluginClient = null;
    private string $apiToken;

    public function __construct(
        string $apiToken,
        ClientInterface $httpClient = null,
        RequestFactoryInterface $requestFactory = null,
        StreamFactoryInterface $streamFactory = null
    ) {
        $this->apiToken = $apiToken;
        $this->httpClient = $httpClient ?? HttpClientDiscovery::find();
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
    }

    public function getHttpClient(): ClientInterface
    {
        if (null === $this->pluginClient) {
            $plugins = [
                new Plugin\HeaderDefaultsPlugin([
                    'User-Agent' => 'mailtrap-php (https://github.com/railsware/mailtrap-php)',
                    'Content-Type' => 'application/json',
                ]),
                new Plugin\AuthenticationPlugin(
                    new Bearer($this->apiToken)
                )
            ];
            $this->pluginClient = new HttpMethodsClient(
                (new PluginClientFactory())->createClient($this->httpClient, $plugins),
                $this->requestFactory,
                $this->streamFactory
            );
        }

        return $this->pluginClient;
    }
}
