<?php

use Mailtrap\Config;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;

require __DIR__ . '/../vendor/autoload.php';

// your API token from here https://mailtrap.io/api-tokens
$apiKey = getenv('MAILTRAP_API_KEY');
$mailtrap = new MailtrapClient(new Config($apiKey));

/**
 * Get attachments
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/inboxes/{inbox_id}/messages/{message_id}/attachments
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $inboxId = getenv('MAILTRAP_INBOX_ID');
    $messageId = getenv('MAILTRAP_MESSAGE_ID');
    // optional (null|string)
    $attachmentType = 'inline';

    $response = $mailtrap->sandbox()->attachments()->getMessageAttachments($accountId, $inboxId, $messageId, $attachmentType);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

/**
 * Get single attachment
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/inboxes/{inbox_id}/messages/{message_id}/attachments/{attachment_id}
 */
try {
    $accountId = getenv('MAILTRAP_ACCOUNT_ID');
    $inboxId = getenv('MAILTRAP_INBOX_ID');
    $messageId = getenv('MAILTRAP_MESSAGE_ID');
    $attachmentId = getenv('MAILTRAP_MESSAGE_ATTACHMENT_ID');

    $response = $mailtrap->sandbox()->attachments()->getMessageAttachment($accountId, $inboxId, $messageId, $attachmentId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
