<?php

use Mailtrap\Config;
use Mailtrap\DTO\Request\Inbox;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;

require __DIR__ . '/../vendor/autoload.php';

// your API token from here https://mailtrap.io/api-tokens
$apiKey = getenv('MAILTRAP_API_KEY');
$mailtrap = new MailtrapClient(new Config($apiKey));


/**
 * Get a list of inboxes.
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/inboxes
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');

    $response = $mailtrap->sandbox()->inboxes()->getList($accountId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Create an inbox
 *
 * POST https://mailtrap.io/api/accounts/{account_id}/projects/{project_id}/inboxes
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $projectId = getenv('MAILTRAP_PROJECT_ID');
    $inboxName = 'First inbox';

    $response = $mailtrap->sandbox()->inboxes()->create($accountId, $projectId, $inboxName);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Get inbox attributes
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/inboxes/{inbox_id}
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $inboxId = getenv('MAILTRAP_INBOX_ID');

    $response = $mailtrap->sandbox()->inboxes()->getInboxAttributes($accountId, $inboxId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Delete an inbox
 *
 * DELETE https://mailtrap.io/api/accounts/{account_id}/inboxes/{inbox_id}
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $inboxId = getenv('MAILTRAP_INBOX_ID');

    $response = $mailtrap->sandbox()->inboxes()->delete($accountId, $inboxId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Reset email address
 *
 * PATCH https://mailtrap.io/api/accounts/{account_id}/inboxes/{inbox_id}/reset_email_username
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $inboxId = getenv('MAILTRAP_INBOX_ID');

    $response = $mailtrap->sandbox()->inboxes()->resetEmailAddress($accountId, $inboxId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Enable/Disable email address
 *
 * PATCH https://mailtrap.io/api/accounts/{account_id}/inboxes/{inbox_id}/toggle_email_username
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $inboxId = getenv('MAILTRAP_INBOX_ID');

    $response = $mailtrap->sandbox()->inboxes()->toggleEmailAddress($accountId, $inboxId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Reset credentials
 *
 * PATCH https://mailtrap.io/api/accounts/{account_id}/inboxes/{inbox_id}/reset_credentials
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $inboxId = getenv('MAILTRAP_INBOX_ID');

    $response = $mailtrap->sandbox()->inboxes()->resetSmtpCredentials($accountId, $inboxId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Mark as read
 *
 * PATCH https://mailtrap.io/api/accounts/{account_id}/inboxes/{inbox_id}/all_read
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $inboxId = getenv('MAILTRAP_INBOX_ID');

    $response = $mailtrap->sandbox()->inboxes()->markAsRead($accountId, $inboxId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Clean inbox
 *
 * PATCH https://mailtrap.io/api/accounts/{account_id}/inboxes/{inbox_id}/clean
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $inboxId = getenv('MAILTRAP_INBOX_ID');

    $response = $mailtrap->sandbox()->inboxes()->clean($accountId, $inboxId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Update an inbox
 *
 * PATCH https://mailtrap.io/api/accounts/{account_id}/inboxes/{inbox_id}
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $inboxId = getenv('MAILTRAP_INBOX_ID');
    $newInboxName = 'New inbox name';
    $newEmailUsername = 'new-email-username';

    $response = $mailtrap->sandbox()->inboxes()->update(
        $accountId,
        $inboxId,
        new Inbox($newInboxName, $newEmailUsername)
    );

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
