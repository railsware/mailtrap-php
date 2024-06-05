<?php

use Mailtrap\Config;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapSandboxClient;

require __DIR__ . '/../vendor/autoload.php';

$accountId = getenv('MAILTRAP_ACCOUNT_ID');
$inboxId = getenv('MAILTRAP_INBOX_ID');
$config = new Config(getenv('MAILTRAP_API_KEY')); #your API token from here https://mailtrap.io/api-tokens

$sandboxAttachments = (new MailtrapSandboxClient($config))->attachments($accountId, $inboxId); #required parameters are accountId amd inboxId


/**
 * Get attachments
 *
 * GET https://mailtrap.io/api/accounts/{account_id}/inboxes/{inbox_id}/messages/{message_id}/attachments
 */
try {
    $messageId = getenv('MAILTRAP_MESSAGE_ID');
    $attachmentType = 'inline'; # optional (null|string)

    $response = $sandboxAttachments->getMessageAttachments($messageId, $attachmentType);

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
    $messageId = getenv('MAILTRAP_MESSAGE_ID');
    $attachmentId = getenv('MAILTRAP_MESSAGE_ATTACHMENT_ID');

    $response = $sandboxAttachments->getMessageAttachment($messageId, $attachmentId);

    // print the response body (array)
    var_dump(ResponseHelper::toArray($response));
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
