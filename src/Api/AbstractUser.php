<?php

declare(strict_types=1);

namespace Mailtrap\Api;

use Psr\Http\Message\ResponseInterface;

/**
 * Class AbstractUser
 */
abstract class AbstractUser extends AbstractApi
{
    public function getList(int $accountId, array $inboxIds = [], array $projectIds = []): ResponseInterface
    {
        $parameters = [];
        if (count($inboxIds) > 0) {
            $parameters['inbox_ids'] = $inboxIds;
        }

        if (count($projectIds) > 0) {
            $parameters['project_ids'] = $projectIds;
        }

        return $this->handleResponse($this->get(
            sprintf('%s/api/accounts/%s/account_accesses', $this->getHost(), $accountId),
            $parameters
        ));
    }

    public function remove(int $accountId, int $userId): ResponseInterface
    {
        return $this->handleResponse($this->delete(
            sprintf('%s/api/accounts/%s/account_accesses/%s', $this->getHost(), $accountId, $userId)
        ));
    }
}
