<?php

namespace Mailtrap\HttpClient;

use Psr\Http\Client\ClientInterface;

interface HttpClientBuilderInterface
{
    public function getHttpClient(): ClientInterface;
}
