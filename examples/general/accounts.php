<?php

use Mailtrap\Config;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;

require __DIR__ . '/../vendor/autoload.php';

$mailTrap = new MailtrapClient(
    new Config('23...YOUR_API_KEY_HERE...4c')
);

/**
 * Get a list of your Mailtrap accounts.
 *
 * GET https://mailtrap.io/api/accounts
 */
try {
    $response = $mailTrap->general()->accounts()->getList();

    var_dump(ResponseHelper::toArray($response)); // body (array)
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
