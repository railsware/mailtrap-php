<?php

declare(strict_types=1);

namespace Mailtrap\Api;

use Psr\Http\Message\ResponseInterface;

/**
 * Class Account
 */
class Account extends AbstractApi
{
    public function getAll(): ResponseInterface
    {
        return $this->handleResponse(
            $this->get($this->getHost() . '/api/accounts')
        );
    }
}