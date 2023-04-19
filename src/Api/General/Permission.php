<?php

declare(strict_types=1);

namespace Mailtrap\Api\General;

use Mailtrap\Api\AbstractApi;
use Mailtrap\DTO\Request\Permission\Permissions;
use Mailtrap\Exception\RuntimeException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Permission
 */
class Permission extends AbstractApi implements GeneralInterface
{
    /**
     * Get all resources in your account (Inboxes, Projects, Domains, Billing and Account itself) to which the token has admin access.
     *
     * @param int $accountId
     *
     * @return ResponseInterface
     */
    public function getResources(int $accountId): ResponseInterface
    {
        return $this->handleResponse($this->httpGet(
            sprintf('%s/api/accounts/%s/permissions/resources', $this->getHost(), $accountId)
        ));
    }

    /**
     * Manage user or token permissions.
     * If you send a combination of resource_type and resource_id that already exists, the permission is updated.
     * If the combination doesn’t exist, the permission is created.
     *
     * @param int         $accountId
     * @param int         $accountAccessId
     * @param Permissions $permissions
     *
     * @return ResponseInterface
     */
    public function update(int $accountId, int $accountAccessId, Permissions $permissions): ResponseInterface
    {
        return $this->handleResponse($this->httpPut(
            sprintf('%s/api/accounts/%s/account_accesses/%s/permissions/bulk', $this->getHost(), $accountId, $accountAccessId),
            [],
            ['permissions' => $this->getPayload($permissions)]
        ));
    }

    private function getPayload(Permissions $permissions): array
    {
        $payload = [];
        foreach ($permissions->getAll() as $permission) {
            $payload[] = $permission->toArray();
        }

        if (count($payload) === 0) {
            throw new RuntimeException('At least one "permission" object should be added to manage user or token');
        }

        return $payload;
    }
}
