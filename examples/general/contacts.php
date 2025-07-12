<?php

use Mailtrap\Config;
use Mailtrap\DTO\Request\Contact\CreateContact;
use Mailtrap\DTO\Request\Contact\ImportContact;
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
 * Get a specific Contact List by ID.
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/contacts/lists/{list_id}
 */
try {
    $contactListId = 1; // Replace 1 with the actual list ID
    $response = $contacts->getContactList($contactListId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), PHP_EOL;
}


/**
 * Create a new Contact List.
 *
 * POST https://mailtrap.io/api/accounts/{account_id}/contacts/lists
 */
try {
    $contactListName = 'New Contact List'; // Replace with your desired list name
    $response = $contacts->createContactList($contactListName);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), PHP_EOL;
}


/**
 * Update a Contact List by ID.
 *
 * PATCH https://mailtrap.io/api/accounts/{account_id}/contacts/lists/{list_id}
 */
try {
    $contactListId = 1; // Replace 1 with the actual list ID
    $newContactListName = 'Updated Contact List Name'; // Replace with your desired list name
    $response = $contacts->updateContactList($contactListId, $newContactListName);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), PHP_EOL;
}


/**
 * Delete a Contact List by ID.
 *
 * DELETE https://mailtrap.io/api/accounts/{account_id}/contacts/lists/{list_id}
 */
try {
    $contactListId = 1; // Replace 1 with the actual list ID
    $response = $contacts->deleteContactList($contactListId);

    // Print the response status code
    var_dump($response->getStatusCode());
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), PHP_EOL;
}


/**
 * Get contact
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/contacts/{id_or_email}
 */
try {
    // Get contact by ID
    $response = $contacts->getContactById('019706a8-0000-0000-0000-4f26816b467a');

    // OR get contact by email
    $response = $contacts->getContactByEmail('john.smith@example.com');

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

    // Print the response status code
    var_dump($response->getStatusCode());
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), PHP_EOL;
}


/**
 * Get all Contact Fields existing in your account
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/contacts/fields
 */
try {
    $response = $contacts->getAllContactFields();

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), PHP_EOL;
}


/**
 * Get a specific Contact Field by ID.
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/contacts/fields/{field_id}
 */
try {
    $fieldId = 1; // Replace 1 with the actual field ID
    $response = $contacts->getContactField($fieldId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), PHP_EOL;
}


/**
 * Create new Contact Fields. Please note, you can have up to 40 fields.
 *
 * POST https://mailtrap.io/api/accounts/{account_id}/contacts/fields
 */
try {
    $response = $contacts->createContactField(
        'New Field Name', // <= 80 characters
        'text', // Allowed values: text, integer, float, boolean, date
        'new_field_merge_tag' // Personalize your campaigns by adding a merge tag. This field will be replaced with unique contact details for each recipient.
    );

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), PHP_EOL;
}


/**
 * Update existing Contact Field. Please note, you cannot change data_type of the field.
 *
 * PATCH https://mailtrap.io/api/accounts/{account_id}/contacts/fields/{field_id}
 */
try {
    $fieldId = 1; // Replace 1 with the actual field ID
    $response = $contacts->updateContactField(
        $fieldId,
        'Updated Field Name',
        'updated_field_merge_tag'
    );

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), PHP_EOL;
}

/**
 * Delete Contact Field by ID.
 *
 * DELETE https://mailtrap.io/api/accounts/{account_id}/contacts/fields/{field_id}
 */
try {
    $fieldId = 1; // Replace 1 with the actual field ID
    $response = $contacts->deleteContactField($fieldId);

    // Print the response status code
    var_dump($response->getStatusCode());
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), PHP_EOL;
}


/**
 * Import contacts in bulk with support for custom fields and list management.
 * Existing contacts with matching email addresses will be updated automatically.
 * You can import up to 50,000 contacts per request.
 * The import process runs asynchronously - use the returned import ID to check the status and results.
 *
 * POST https://mailtrap.io/api/accounts/{account_id}/contacts/imports
 */
try {
    $contactsToImport = [
        new ImportContact(
            email: 'customer1@example.com',
            fields: ['first_name' => 'John', 'last_name' => 'Smith', 'zip_code' => 11111],
            listIdsIncluded: [1, 2],
            listIdsExcluded: [4, 5]
        ),
        new ImportContact(
            email: 'customer2@example.com',
            fields: ['first_name' => 'Joe', 'last_name' => 'Doe', 'zip_code' => 22222],
            listIdsIncluded: [1],
            listIdsExcluded: [4]
        ),
    ];

    $response = $contacts->importContacts($contactsToImport);
    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), PHP_EOL;
}


/**
 * Get the status of a contact import by ID.
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/contacts/imports/{import_id}
 */
try {
    $importId = 1; // Replace 1 with the actual import ID
    $response = $contacts->getContactImport($importId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), PHP_EOL;
}
