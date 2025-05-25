<?php

use Mailtrap\Config;
use Mailtrap\DTO\Request\Contact\CreateContact;
use Mailtrap\DTO\Request\Contact\UpdateContact;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapGeneralClient;

require __DIR__ . '/../vendor/autoload.php';

$accountId = getenv('MAILTRAP_ACCOUNT_ID');
$config = new Config(getenv('MAILTRAP_API_KEY')); #your API token from here https://mailtrap.io/api-tokens
$contacts = (new MailtrapGeneralClient($config))->contacts($accountId);

/**
 * Get all Contact Lists.
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/contacts/lists
 */
try {
    $response = $contacts->getAllContactLists();

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), PHP_EOL;
}


/**
 * Create a new Contact
 *
 * POST https://mailtrap.io/api/accounts/{account_id}/contacts
 */
try {
    $response = $contacts->createContact(
        CreateContact::init(
            'john.smith@example.com',
            ['first_name' => 'John', 'last_name' => 'Smith'], // Fields
            [1, 2] // List IDs to which the contact will be added
        )
    );

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), PHP_EOL;
}


/**
 * Update contact by ID or Email.
 *
 * PATCH https://mailtrap.io/api/accounts/{account_id}/contacts/{id_or_email}
 */
try {
    // Update contact by ID
    $response = $contacts->updateContactById(
        '019706a8-0000-0000-0000-4f26816b467a',
        UpdateContact::init(
            'john.smith@example.com',
            ['first_name' => 'John', 'last_name' => 'Smith'], // Fields
            [3], // List IDs to which the contact will be added
            [1, 2], // List IDs from which the contact will be removed
            true // Unsubscribe the contact
        )
    );

    // OR update contact by email
    $response = $contacts->updateContactByEmail(
        'john.smith@example.com',
        UpdateContact::init(
            'john.smith@example.com',
            ['first_name' => 'John', 'last_name' => 'Smith'], // Fields
            [3], // List IDs to which the contact will be added
            [1, 2], // List IDs from which the contact will be removed
            true // Unsubscribe the contact
        )
    );

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), PHP_EOL;
}


/**
 * Delete contact
 *
 * Delete https://mailtrap.io/api/accounts/{account_id}/contacts/{id_or_email}
 */
try {
    // Delete contact by ID
    $response = $contacts->deleteContactById('019706a8-0000-0000-0000-4f26816b467a');

    // OR delete contact by email
    $response = $contacts->deleteContactByEmail('john.smith@example.com');

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), PHP_EOL;
}
