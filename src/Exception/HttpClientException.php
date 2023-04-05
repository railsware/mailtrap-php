<?php

declare(strict_types=1);

namespace Mailtrap\Exception;

use JsonException;
use Mailtrap\Helper\ResponseHelper;
use Psr\Http\Message\ResponseInterface;

/**
 * Class HttpClientException
 */
class HttpClientException extends HttpException
{
    public const ERROR_PREFIXES = [
        400 => 'Bad request. Fix errors listed in response before retrying.',
        401 => 'Unauthorized. Make sure you are sending correct credentials with the request before retrying.',
        403 => 'Forbidden. Make sure domain verification process is completed or check your permissions.',
        404 => 'Not found.',
    ];

    public static function createFromResponse(ResponseInterface $response): HttpClientException
    {
        $errorMsg = '';
        $statusCode = $response->getStatusCode();

        try {
            $body = ResponseHelper::toArray($response);
        } catch (JsonException|InvalidTypeException $e) {
            $body['error'] = $response->getBody()->__toString();
        }

        if (isset(self::ERROR_PREFIXES[$statusCode])) {
            $errorMsg .= self::ERROR_PREFIXES[$statusCode] . ' Errors: ';
        }

        $errorMsg .= !empty($body['errors'])
            ? (is_array($body['errors']) ? implode(' & ', $body['errors']) : $body['errors'])
            : $body['error'];

        return new self (
            !empty($errorMsg)
                ? $errorMsg
                : sprintf('HTTP response code ("%d") received from the API server (no error info)', $statusCode),
            $statusCode
        );
    }
}
