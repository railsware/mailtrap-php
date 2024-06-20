<?php

declare(strict_types=1);

namespace Mailtrap\Api\General;

use Mailtrap\Api\AbstractApi;
use Mailtrap\ConfigInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class User
 */
class User extends AbstractApi implements GeneralInterface
{
    public function __construct(ConfigInterface $config, private int $accountId)
    {
        parent::__construct($config);
    }

    /**
     * Get list of all account users. You need to have account admin or owner permissions for this endpoint to work.
     * If you specify project_ids, inbox_ids, the endpoint returns users filtered by these resources.
     *
     * @param array $inboxIds
     * @param array $projectIds
     *
     * @return ResponseInterface
     */
    public function getList(array $inboxIds = [], array $projectIds = []): ResponseInterface
    {
        $parameters = [];
        if (count($inboxIds) > 0) {
            $parameters['inbox_ids'] = $inboxIds;
        }

        if (count($projectIds) > 0) {
            $parameters['project_ids'] = $projectIds;
        }

        return $this->handleResponse($this->httpGet(
            sprintf('%s/api/accounts/%s/account_accesses', $this->getHost(), $this->getAccountId()),
            $parameters
        ));
    }

    /**
     * Remove user by their ID. You need to be an account admin/owner for this endpoint to work.
     *
     * @param int $accountAccessId
     *
     * @return ResponseInterface
     */
    public function delete(int $accountAccessId): ResponseInterface
    {
        return $this->handleResponse($this->httpDelete(
            sprintf('%s/api/accounts/%s/account_accesses/%s', $this->getHost(), $this->getAccountId(), $accountAccessId)
        ));
    }

    public function getAccountId(): int
    {
        return $this->accountId;
    }
}
