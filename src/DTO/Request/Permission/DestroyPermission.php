<?php

declare(strict_types=1);

namespace Mailtrap\DTO\Request\Permission;

/**
 * Class DestroyPermission
 */
final class DestroyPermission implements PermissionInterface
{
    private string $resourceId;
    private string $resourceType;

    /**
     * @param string|int $resourceId
     * @param string     $resourceType
     */
    public function __construct($resourceId, string $resourceType)
    {
        $this->resourceId = (string) $resourceId;
        $this->resourceType = $resourceType;
    }

    public function getResourceId(): string
    {
        return $this->resourceId;
    }

    public function getResourceType(): string
    {
        return $this->resourceType;
    }

    public function toArray(): array
    {
        return [
            'resource_id' => $this->getResourceId(),
            'resource_type' => $this->getResourceType(),
            '_destroy' => true,
        ];
    }
}
