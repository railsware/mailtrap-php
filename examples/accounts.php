<?php

use Mailtrap\Config;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;

require __DIR__ . '/../vendor/autoload.php';

/**
 * Config
 *
 * ->setHost(string)
 * ->setHttpClientBuilder(HttpClientBuilderInterface)
 * ->setHttpClient(ClientInterface) # https://docs.php-http.org/en/latest/clients.html
 * ->setRequestFactory(RequestFactoryInterface)
 * ->setStreamFactory(StreamFactoryInterface)
 */
$config = new Config('23...YOUR_API_KEY_HERE...4c');
$mailTrap = new MailtrapClient($config);

/**
 * Get all accounts
 *
 * GET https://mailtrap.io/api/accounts
 */
try {
    $response = $mailTrap->accounts()->getAll();

    // print all possible information from the response
    var_dump($response->getHeaders()); //headers (array)
    var_dump($response->getStatusCode()); //status code (int)
    var_dump(ResponseHelper::toArray($response)); // body (array)
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
