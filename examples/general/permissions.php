<?php

use Mailtrap\Config;
use Mailtrap\DTO\Request\Permission\CreateOrUpdatePermission;
use Mailtrap\DTO\Request\Permission\DestroyPermission;
use Mailtrap\DTO\Request\Permission\PermissionInterface;
use Mailtrap\DTO\Request\Permission\Permissions;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;

require __DIR__ . '/../vendor/autoload.php';

// your API token from here https://mailtrap.io/api-tokens
$apiKey = getenv('MAILTRAP_API_KEY');
$mailtrap = new MailtrapClient(new Config($apiKey));

/**
 * Get resources
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/permissions/resources
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $response = $mailtrap->general()->permissions()->getResources($accountId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Manage user or token permissions
 *
 * If you send a combination of resource_type and resource_id that already exists, the permission is updated.
 * If the combination doesn’t exist, the permission is created.
 *
 * PUT https://mailtrap.io/api/accounts/{account_id}/account_accesses/{account_access_id}/permissions/bulk
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $accountAccessId = getenv('MAILTRAP_ACCOUNT_ACCESS_ID');

    // resource IDs
    $projectResourceId = getenv('MAILTRAP_NEW_PROJECT_RESOURCE_ID');
    $inboxResourceId = getenv('MAILTRAP_INBOX_RESOURCE_ID');
    $destroyProjectResourceId = getenv('MAILTRAP_OLD_PROJECT_RESOURCE_ID');

    $permissions = new Permissions(
        new CreateOrUpdatePermission($projectResourceId, PermissionInterface::TYPE_PROJECT, 10), // viewer = 10
        new CreateOrUpdatePermission($inboxResourceId, PermissionInterface::TYPE_INBOX, 100), // admin = 100
        new DestroyPermission($destroyProjectResourceId, PermissionInterface::TYPE_PROJECT),
    );

    $response = $mailtrap->general()->permissions()->update($accountId, $accountAccessId, $permissions);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
