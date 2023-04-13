<?php

namespace Mailtrap;

use Mailtrap\HttpClient\HttpClientBuilderInterface;

interface ConfigInterface
{
    public function getApiToken(): string;

    public function getHost(): ?string;

    public function getHttpClientBuilder(): HttpClientBuilderInterface;

    public function isResponseThrowOnError(): bool;
}
