<?php

declare(strict_types=1);

namespace Mailtrap\Api\Sandbox;

use Mailtrap\Api\AbstractApi;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Project
 */
class Project extends AbstractApi implements SandboxInterface
{
    /**
     * List projects and their inboxes to which the API token has access.
     *
     * @param int $accountId
     *
     * @return ResponseInterface
     */
    public function getList(int $accountId): ResponseInterface
    {
        return $this->handleResponse($this->httpGet(
            sprintf('%s/api/accounts/%s/projects', $this->getHost(), $accountId)
        ));
    }

    /**
     * Get the project and its inboxes.
     *
     * @param int $accountId
     * @param int $projectId
     *
     * @return ResponseInterface
     */
    public function getById(int $accountId, int $projectId): ResponseInterface
    {
        return $this->handleResponse($this->httpGet(
            sprintf('%s/api/accounts/%s/projects/%s', $this->getHost(), $accountId, $projectId)
        ));
    }

    /**
     * Create project
     *
     * @param int    $accountId
     * @param string $projectName
     *
     * @return ResponseInterface
     */
    public function create(int $accountId, string $projectName): ResponseInterface
    {
        return $this->handleResponse($this->httpPost(
            sprintf('%s/api/accounts/%s/projects', $this->getHost(), $accountId),
            [],
            ['project' => ['name' => $projectName]]
        ));
    }

    /**
     * Delete project and its inboxes.
     *
     * @param int $accountId
     * @param int $projectId
     *
     * @return ResponseInterface
     */
    public function delete(int $accountId, int $projectId): ResponseInterface
    {
        return $this->handleResponse($this->httpDelete(
            sprintf('%s/api/accounts/%s/projects/%s', $this->getHost(), $accountId, $projectId)
        ));
    }

    /**
     * Update project name.
     *
     * @param int    $accountId
     * @param int    $projectId
     * @param string $projectName
     *
     * @return ResponseInterface
     */
    public function updateName(int $accountId, int $projectId, string $projectName): ResponseInterface
    {
        return $this->handleResponse($this->httpPatch(
            sprintf('%s/api/accounts/%s/projects/%s', $this->getHost(), $accountId, $projectId),
            [],
            ['project' => ['name' => $projectName]]
        ));
    }
}
