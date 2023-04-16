<?php

namespace Mailtrap\DTO\Request\Permission;

interface PermissionInterface
{
    /**
     * The ID of the resource
     *
     * @return string
     */
    public function getResourceId(): string;

    /**
     * Can be account, billing, project, inbox or mailsend_domain.
     *
     * @return string
     */
    public function getResourceType(): string;

    /**
     * Get permission as array
     *
     * @return array
     */
    public function toArray(): array;
}
