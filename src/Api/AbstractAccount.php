<?php

declare(strict_types=1);

namespace Mailtrap\Api;

use Psr\Http\Message\ResponseInterface;

/**
 * Class AbstractAccount
 */
abstract class AbstractAccount extends AbstractApi
{
    /**
     * Currently the same endpoint and result for Sandbox and Sanding environments
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
