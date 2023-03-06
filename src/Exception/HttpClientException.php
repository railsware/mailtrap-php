<?php

declare(strict_types=1);

namespace Mailtrap\Exception;

use Mailtrap\Helper\ResponseHelper;
use Psr\Http\Message\ResponseInterface;

/**
 * Class HttpClientException
 */
class HttpClientException extends HttpException
{
    public static function createFromResponse(ResponseInterface $response): HttpClientException
    {
        $body = ResponseHelper::toArray($response) ?? [];
        $errorMsg = !empty($body['errors']) ? implode('. ', $body['errors']) : $body['error'];

        return new self (
            !empty($errorMsg)
                ? $errorMsg
                : sprintf('HTTP response code ("%d") received from the API server (no error info)', $response->getStatusCode()),
            $response->getStatusCode()
        );
    }
}