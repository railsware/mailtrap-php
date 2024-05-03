<?php

use Mailtrap\Config;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;

require __DIR__ . '/../vendor/autoload.php';

// your API token from here https://mailtrap.io/api-tokens
$apiKey = getenv('MAILTRAP_API_KEY');
$mailtrap = new MailtrapClient(new Config($apiKey));

/**
 * Get messages
 * Note: if you want to get all messages you need to use "page" param (by default will return only 30 messages per page)
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/inboxes/{inbox_id}/messages
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $inboxId = getenv('MAILTRAP_INBOX_ID');

    // not required parameters
    $page = 1; // by default  30 messages per page
    $search = 'hello'; // it works like case insensitive pattern matching by subject, to_email, to_name
    $lastMessageId = 3000000003; // get emails, where primary key is less then this param (does not work with page param)

    $response = $mailtrap->sandbox()->messages()->getList($accountId, $inboxId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Show email message
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/inboxes/{inbox_id}/messages/{message_id}
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $inboxId = getenv('MAILTRAP_INBOX_ID');
    $messageId = getenv('MAILTRAP_INBOX_MESSAGE_ID');

    $response = $mailtrap->sandbox()->messages()->getById($accountId, $inboxId, $messageId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Get message source
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/inboxes/{inbox_id}/messages/{message_id}/body.htmlsource
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $inboxId = getenv('MAILTRAP_INBOX_ID');
    $messageId = getenv('MAILTRAP_INBOX_MESSAGE_ID');

    $response = $mailtrap->sandbox()->messages()->getSource($accountId, $inboxId, $messageId);

    // print the response body (string)
    var_dump(ResponseHelper::toString($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Get message as .eml
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/inboxes/{inbox_id}/messages/{message_id}/body.eml
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $inboxId = getenv('MAILTRAP_INBOX_ID');
    $messageId = getenv('MAILTRAP_INBOX_MESSAGE_ID');

    $response = $mailtrap->sandbox()->messages()->getEml($accountId, $inboxId, $messageId);

    // print the response body (string)
    var_dump(ResponseHelper::toString($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Get HTML message
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/inboxes/{inbox_id}/messages/{message_id}/body.html
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $inboxId = getenv('MAILTRAP_INBOX_ID');
    $messageId = getenv('MAILTRAP_INBOX_MESSAGE_ID');

    $response = $mailtrap->sandbox()->messages()->getHtml($accountId, $inboxId, $messageId);

    // print the response body (string)
    var_dump(ResponseHelper::toString($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Get raw message
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/inboxes/{inbox_id}/messages/{message_id}/body.raw
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $inboxId = getenv('MAILTRAP_INBOX_ID');
    $messageId = getenv('MAILTRAP_INBOX_MESSAGE_ID');

    $response = $mailtrap->sandbox()->messages()->getRaw($accountId, $inboxId, $messageId);

    // print the response body (string)
    var_dump(ResponseHelper::toString($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Get text message
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/inboxes/{inbox_id}/messages/{message_id}/body.txt
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $inboxId = getenv('MAILTRAP_INBOX_ID');
    $messageId = getenv('MAILTRAP_INBOX_MESSAGE_ID');

    $response = $mailtrap->sandbox()->messages()->getText($accountId, $inboxId, $messageId);

    // print the response body (string)
    var_dump(ResponseHelper::toString($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Get message HTML analysis
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/inboxes/{inbox_id}/messages/{message_id}/analyze
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $inboxId = getenv('MAILTRAP_INBOX_ID');
    $messageId = getenv('MAILTRAP_INBOX_MESSAGE_ID');

    $response = $mailtrap->sandbox()->messages()->getHtmlAnalysis($accountId, $inboxId, $messageId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Get message spam score
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/inboxes/{inbox_id}/messages/{message_id}/spam_report
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $inboxId = getenv('MAILTRAP_INBOX_ID');
    $messageId = getenv('MAILTRAP_INBOX_MESSAGE_ID');

    $response = $mailtrap->sandbox()->messages()->getSpamScore($accountId, $inboxId, $messageId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Update message (mark as read)
 *
 * PATCH https://mailtrap.io/api/accounts/{account_id}/inboxes/{inbox_id}/messages/{message_id}
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $inboxId = getenv('MAILTRAP_INBOX_ID');
    $messageId = getenv('MAILTRAP_INBOX_MESSAGE_ID');

    $response = $mailtrap->sandbox()->messages()->markAsRead($accountId, $inboxId, $messageId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Delete message
 *
 * DELETE https://mailtrap.io/api/accounts/{account_id}/inboxes/{inbox_id}/messages/{message_id}
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $inboxId = getenv('MAILTRAP_INBOX_ID');
    $messageId = getenv('MAILTRAP_INBOX_MESSAGE_ID');

    $response = $mailtrap->sandbox()->messages()->delete($accountId, $inboxId, $messageId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
