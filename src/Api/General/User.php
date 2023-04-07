<?php

declare(strict_types=1);

namespace Mailtrap\Api\General;

use Mailtrap\Api\AbstractApi;
use Psr\Http\Message\ResponseInterface;

/**
 * Class User
 */
class User extends AbstractApi implements GeneralInterface
{
    /**
     * Get list of all account users. You need to have account admin or owner permissions for this endpoint to work.
     * If you specify project_ids, inbox_ids, the endpoint returns users filtered by these resources.
     *
     * @param int   $accountId
     * @param array $inboxIds
     * @param array $projectIds
     *
     * @return ResponseInterface
     */
    public function getList(int $accountId, array $inboxIds = [], array $projectIds = []): ResponseInterface
    {
        $parameters = [];
        if (count($inboxIds) > 0) {
            $parameters['inbox_ids'] = $inboxIds;
        }

        if (count($projectIds) > 0) {
            $parameters['project_ids'] = $projectIds;
        }

        return $this->handleResponse($this->httpGet(
            sprintf('%s/api/accounts/%s/account_accesses', $this->getHost(), $accountId),
            $parameters
        ));
    }

    /**
     * Remove user by their ID. You need to be an account admin/owner for this endpoint to work.
     *
     * @param int $accountId
     * @param int $accountAccessId
     *
     * @return ResponseInterface
     */
    public function delete(int $accountId, int $accountAccessId): ResponseInterface
    {
        return $this->handleResponse($this->httpDelete(
            sprintf('%s/api/accounts/%s/account_accesses/%s', $this->getHost(), $accountId, $accountAccessId)
        ));
    }
}
