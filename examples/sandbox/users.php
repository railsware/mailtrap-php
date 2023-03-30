<?php

use Mailtrap\Config;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapSandboxClient;

require __DIR__ . '/../vendor/autoload.php';

$mailTrap = new MailtrapSandboxClient(
    new Config('23...YOUR_API_KEY_HERE...4c')
);

/**
 * List all users in account
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/account_accesses
 */
try {
    $accountId = 1000001;
    $response = $mailTrap->users()->getList($accountId);

    // OR with query parameters (not required)
    $inboxIds = [2000005, 2000006];
    $projectIds = [1005001];
    $response = $mailTrap->users()->getList($accountId, $inboxIds, $projectIds);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

/**
 * Remove user from the account
 *
 * DELETE https://mailtrap.io/api/accounts/{account_id}/account_accesses/{account_access_id}
 */
try {
    $accountId = 1000001;
    $accountAccessId = 10000009;

    $response = $mailTrap->users()->remove($accountId, $accountAccessId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
