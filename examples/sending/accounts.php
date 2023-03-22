<?php

use Mailtrap\Config;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapSendingClient;

require __DIR__ . '/../vendor/autoload.php';

$mailTrap = new MailtrapSendingClient(
    new Config('23...YOUR_API_KEY_HERE...4c')
);

/**
 * Get all accounts
 *
 * GET https://mailtrap.io/api/accounts
 */
try {
    $response = $mailTrap->accounts()->getList();

    // print all possible information from the response
    var_dump($response->getHeaders()); //headers (array)
    var_dump($response->getStatusCode()); //status code (int)
    var_dump(ResponseHelper::toArray($response)); // body (array)
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
