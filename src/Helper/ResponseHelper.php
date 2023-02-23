<?php

declare(strict_types=1);

namespace Mailtrap\Helper;

use Psr\Http\Message\ResponseInterface;

/**
 * Class ResponseHelper
 */
final class ResponseHelper
{
    public static function toArray(ResponseInterface $response)
    {
        return json_decode($response->getBody()->__toString(), true, 512, JSON_THROW_ON_ERROR);
    }
}