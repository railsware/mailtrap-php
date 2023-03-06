<?php

declare(strict_types=1);

namespace Mailtrap\Tests;

use Mailtrap\ConfigInterface;
use Mailtrap\HttpClient\HttpClientBuilderInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;

/**
 * Class MailtrapTestCase
 */
abstract class MailtrapTestCase extends TestCase
{
    public const DEFAULT_API_KEY = 'some_api_key';

    protected function getHttpClientMock(): ClientInterface
    {
        $client = $this->getMockBuilder(ClientInterface::class)
            ->onlyMethods(['sendRequest'])
            ->getMock();

        $client
            ->expects($this->any())
            ->method('sendRequest');

        return $client;
    }

    protected function getConfigMock(): ConfigInterface
    {
        $config = $this->getMockBuilder(ConfigInterface::class)
            ->onlyMethods(['getHttpClientBuilder', 'getApiToken', 'getHost'])
            ->getMock();

        $config
            ->method('getHttpClientBuilder')
            ->willReturn($this->getHttpClientBuilderMock());

        $config
            ->method('getApiToken')
            ->willReturn(self::DEFAULT_API_KEY);

        return $config;
    }

    protected function getHttpClientBuilderMock(): HttpClientBuilderInterface
    {
        $builder = $this->getMockBuilder(HttpClientBuilderInterface::class)
            ->onlyMethods(['getHttpClient'])
            ->disableOriginalConstructor()
            ->getMock();

        $builder
            ->method('getHttpClient')
            ->willReturn($this->getHttpClientMock());

        return $builder;
    }
}