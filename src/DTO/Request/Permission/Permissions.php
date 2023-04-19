<?php

declare(strict_types=1);

namespace Mailtrap\DTO\Request\Permission;

/**
 * Class Permissions
 */
final class Permissions
{
    /**
     * @var PermissionInterface[]
     */
    private array $permissions = [];

    public function __construct(PermissionInterface ...$permissions)
    {
        foreach ($permissions as $permission) {
            $this->add($permission);
        }
    }

    public function add(PermissionInterface $permission): Permissions
    {
        $this->permissions[] = $permission;

        return $this;
    }

    /**
     * @return PermissionInterface[]
     */
    public function getAll(): array
    {
        return $this->permissions;
    }
}
