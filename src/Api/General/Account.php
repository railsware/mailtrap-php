<?php

declare(strict_types=1);

namespace Mailtrap\Api\General;

use Mailtrap\Api\AbstractApi;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Account
 */
class Account extends AbstractApi implements GeneralInterface
{
    /**
     * Get a list of your Mailtrap accounts.
     *
     * @return ResponseInterface
     */
    public function getList(): ResponseInterface
    {
        return $this->handleResponse(
            $this->httpGet($this->getHost() . '/api/accounts')
        );
    }
}
