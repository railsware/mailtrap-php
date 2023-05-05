<?php

namespace Mailtrap\DTO\Request\Permission;

use Mailtrap\DTO\Request\RequestInterface;

interface PermissionInterface extends RequestInterface
{
    public const TYPE_ACCOUNT = 'account';
    public const TYPE_BILLING = 'billing';
    public const TYPE_PROJECT = 'project';
    public const TYPE_INBOX = 'inbox';
    public const TYPE_MAILSEND_DOMAIN = 'mailsend_domain';

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
}
