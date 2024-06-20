<?php

declare(strict_types=1);

namespace Mailtrap\HttpClient;

use Psr\Http\Client\ClientInterface;

interface HttpClientBuilderInterface
{
    public function getHttpClient(): ClientInterface;
}
