<?php

use Mailtrap\Config;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;

require __DIR__ . '/../vendor/autoload.php';

// your API token from here https://mailtrap.io/api-tokens
$apiKey = getenv('MAILTRAP_API_KEY');
$mailtrap = new MailtrapClient(new Config($apiKey));

/**
 * List projects and their inboxes to which the API token has access.
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/projects
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');

    $response = $mailtrap->sandbox()->projects()->getList($accountId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Get the project and its inboxes.
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/projects/{project_id}
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $projectId = getenv('MAILTRAP_PROJECT_ID');

    $response = $mailtrap->sandbox()->projects()->getById($accountId, $projectId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

/**
 * Create project
 * The project name is min 2 characters and max 100 characters long.
 *
 * POST https://mailtrap.io/api/accounts/{account_id}/projects
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $projectName = 'Some project name';

    $response = $mailtrap->sandbox()->projects()->create($accountId, $projectName);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Update project
 * The project name is min 2 characters and max 100 characters long.
 *
 * PATCH https://mailtrap.io/api/accounts/{account_id}/projects/{project_id}
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $projectId = getenv('MAILTRAP_PROJECT_ID');
    $newProjectName = 'New project name';

    $response = $mailtrap->sandbox()->projects()->updateName($accountId, $projectId, $newProjectName);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Delete project and its inboxes.
 *
 * DELETE https://mailtrap.io/api/accounts/{account_id}/projects/{project_id}
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $projectId = getenv('MAILTRAP_PROJECT_ID');

    $response = $mailtrap->sandbox()->projects()->delete($accountId, $projectId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


