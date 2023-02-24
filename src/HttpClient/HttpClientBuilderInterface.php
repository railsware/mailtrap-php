<?php

namespace Mailtrap\HttpClient;

use Http\Client\Common\HttpMethodsClientInterface;

interface HttpClientBuilderInterface
{
    public function getHttpClient(): HttpMethodsClientInterface;
}