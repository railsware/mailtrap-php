<?php

declare(strict_types=1);

namespace Mailtrap\Helper;

use JsonException;
use Mailtrap\Exception\InvalidTypeException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ResponseHelper
 */
final class ResponseHelper
{
    /**
     * @throws JsonException|InvalidTypeException
     */
    public static function toArray(ResponseInterface $response): array
    {
        if (strpos($response->getHeaderLine('Content-Type'), 'application/json') === false) {
            // This can happen when the URL structure changes and the response returns a 404 HTML page. (rare case)
            throw new InvalidTypeException(sprintf(
                'Invalid content type in response. "%s" type expected, but received "%s"',
                'application/json',
                $response->getHeaderLine('Content-Type')
            ));
        }

        return json_decode($response->getBody()->__toString(), true, 512, JSON_THROW_ON_ERROR);
    }

    public static function toString(ResponseInterface $response): string
    {
        return $response->getBody()->__toString();
    }
}
