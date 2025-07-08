<?php

use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Header\UnstructuredHeader;

require __DIR__ . '/../vendor/autoload.php';


/**********************************************************************************************************************
 ******************************************* EMAIL SENDING ************************************************************
 **********************************************************************************************************************
 */

/**
 * Email Sending API
 *
 * POST https://send.api.mailtrap.io/api/send
 */
try {
    $mailtrap = MailtrapClient::initSendingEmails(
        apiKey: getenv('MAILTRAP_API_KEY') #your API token from here https://mailtrap.io/api-tokens
    );

    $email = (new MailtrapEmail())
        ->from(new Address('example@YOUR-DOMAIN-HERE.com', 'Mailtrap Test')) // <--- you should use your domain here that you installed in the mailtrap.io admin area (otherwise you will get 401)
        ->replyTo(new Address('reply@YOUR-DOMAIN-HERE.com'))
        ->to(new Address('email@example.com', 'Jon'))
        ->priority(Email::PRIORITY_HIGH)
        ->cc('mailtrapqa@example.com')
        ->addCc('staging@example.com')
        ->bcc('mailtrapdev@example.com')
        ->subject('Best practices of building HTML emails')
        ->text('Hey! Learn the best practices of building HTML emails and play with ready-to-go templates. Mailtrap’s Guide on How to Build HTML Email is live on our blog')
        ->html(
            '<html>
            <body>
            <p><br>Hey</br>
            Learn the best practices of building HTML emails and play with ready-to-go templates.</p>
            <p><a href="https://mailtrap.io/blog/build-html-email/">Mailtrap’s Guide on How to Build HTML Email</a> is live on our blog</p>
            <img src="cid:logo">
            </body>
        </html>'
        )
        ->embed(fopen('https://mailtrap.io/wp-content/uploads/2021/04/mailtrap-new-logo.svg', 'r'), 'logo', 'image/svg+xml')
        ->attachFromPath('README.md')
        ->customVariables([
            'user_id' => '45982',
            'batch_id' => 'PSJ-12'
        ])
        ->category('Integration Test')
    ;

    // Custom email headers (optional)
    $email->getHeaders()
        ->addTextHeader('X-Message-Source', 'test.com')
        ->add(new UnstructuredHeader('X-Mailer', 'Mailtrap PHP Client'))
    ;

    $response = $mailtrap->send($email);

    var_dump(ResponseHelper::toArray($response)); // body (array)
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Email Sending WITH TEMPLATE
 *
 * WARNING! If template is provided then subject, text, html, category  and other params are forbidden.
 *
 * UUID of email template. Subject, text and html will be generated from template using optional template_variables.
 * Optional template variables that will be used to generate actual subject, text and html from email template
 */
try {
    $mailtrap = MailtrapClient::initSendingEmails(
        apiKey: getenv('MAILTRAP_API_KEY') #your API token from here https://mailtrap.io/api-tokens
    );

    $email = (new MailtrapEmail())
        ->from(new Address('example@YOUR-DOMAIN-HERE.com', 'Mailtrap Test')) // <--- you should use your domain here that you installed in the mailtrap.io admin area (otherwise you will get 401)
        ->replyTo(new Address('reply@YOUR-DOMAIN-HERE.com'))
        ->to(new Address('example@gmail.com', 'Jon'))
        ->templateUuid('bfa432fd-0000-0000-0000-8493da283a69')
        ->templateVariables([
            'user_name' => 'Jon Bush',
            'next_step_link' => 'https://mailtrap.io/',
            'get_started_link' => 'https://mailtrap.io/',
            'onboarding_video_link' => 'some_video_link',
            'company' => [
                'name' => 'Best Company',
                'address' => 'Its Address',
            ],
            'products' => [
                [
                    'name' => 'Product 1',
                    'price' => 100,
                ],
                [
                    'name' => 'Product 2',
                    'price' => 200,
                ],
            ],
            'isBool' => true,
            'int' => 123
        ])
    ;

    $response = $mailtrap->send($email);

    var_dump(ResponseHelper::toArray($response)); // body (array)
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**********************************************************************************************************************
 ******************************************* EMAIL BULK SENDING *******************************************************
 **********************************************************************************************************************
 */

/**
 * Email Bulk Sending API
 *
 * POST https://bulk.api.mailtrap.io/api/send
 */
try {
    $bulkMailtrap = MailtrapClient::initSendingEmails(
        apiKey: getenv('MAILTRAP_API_KEY'), #your API token from here https://mailtrap.io/api-tokens
        isBulk: true # Bulk sending (@see https://help.mailtrap.io/article/113-sending-streams)
    );

    $email = (new MailtrapEmail())
        ->from(new Address('example@YOUR-DOMAIN-HERE.com', 'Mailtrap Test')) // <--- you should use your domain here that you installed in the mailtrap.io admin area (otherwise you will get 401)
        ->replyTo(new Address('reply@YOUR-DOMAIN-HERE.com'))
        ->to(new Address('email@example.com', 'Jon'))
        ->priority(Email::PRIORITY_HIGH)
        ->cc('mailtrapqa@example.com')
        ->addCc('staging@example.com')
        ->bcc('mailtrapdev@example.com')
        ->subject('Best practices of building HTML emails')
        ->text('Hey! Learn the best practices of building HTML emails and play with ready-to-go templates. Mailtrap’s Guide on How to Build HTML Email is live on our blog')
        ->html(
            '<html>
            <body>
            <p><br>Hey</br>
            Learn the best practices of building HTML emails and play with ready-to-go templates.</p>
            <p><a href="https://mailtrap.io/blog/build-html-email/">Mailtrap’s Guide on How to Build HTML Email</a> is live on our blog</p>
            <img src="cid:logo">
            </body>
        </html>'
        )
        ->embed(fopen('https://mailtrap.io/wp-content/uploads/2021/04/mailtrap-new-logo.svg', 'r'), 'logo', 'image/svg+xml')
        ->attachFromPath('README.md')
        ->customVariables([
            'user_id' => '45982',
            'batch_id' => 'PSJ-12'
        ])
        ->category('Integration Test')
    ;

    // Custom email headers (optional)
    $email->getHeaders()
        ->addTextHeader('X-Message-Source', 'test.com')
        ->add(new UnstructuredHeader('X-Mailer', 'Mailtrap PHP Client'))
    ;

    $response = $bulkMailtrap->send($email);

    var_dump(ResponseHelper::toArray($response)); // body (array)
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**
 * Email Bulk Sending WITH TEMPLATE
 *
 * WARNING! If a template is provided, then subject, text, html, category and other params are forbidden.
 *
 * UUID of email template. Subject, text and html will be generated from template using optional template_variables.
 * Optional template variables that will be used to generate actual subject, text and html from email template
 */
try {
    $bulkMailtrap = MailtrapClient::initSendingEmails(
        apiKey: getenv('MAILTRAP_API_KEY'), #your API token from here https://mailtrap.io/api-tokens
        isBulk: true # Bulk sending (@see https://help.mailtrap.io/article/113-sending-streams)
    );

    $email = (new MailtrapEmail())
        ->from(new Address('example@YOUR-DOMAIN-HERE.com', 'Mailtrap Test')) // <--- you should use your domain here that you installed in the mailtrap.io admin area (otherwise you will get 401)
        ->replyTo(new Address('reply@YOUR-DOMAIN-HERE.com'))
        ->to(new Address('example@gmail.com', 'Jon'))
        ->templateUuid('bfa432fd-0000-0000-0000-8493da283a69')
        ->templateVariables([
            'user_name' => 'Jon Bush',
            'next_step_link' => 'https://mailtrap.io/',
            'get_started_link' => 'https://mailtrap.io/',
            'onboarding_video_link' => 'some_video_link',
            'company' => [
                'name' => 'Best Company',
                'address' => 'Its Address',
            ],
            'products' => [
                [
                    'name' => 'Product 1',
                    'price' => 100,
                ],
                [
                    'name' => 'Product 2',
                    'price' => 200,
                ],
            ],
            'isBool' => true,
            'int' => 123
        ])
    ;

    $response = $bulkMailtrap->send($email);

    var_dump(ResponseHelper::toArray($response)); // body (array)
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


/**********************************************************************************************************************
 ******************************************* EMAIL BATCH SENDING *******************************************************
 **********************************************************************************************************************
 */

/**
 * Email Batch Sending API (Transactional OR Bulk)
 *
 * Batch send email (text, html, text&html, templates).
 * Please note that the endpoint will return a 200-level http status, even when sending for individual messages may fail.
 * Users of this endpoint should check the success and errors for each message in the response (the results are ordered the same as the original messages - requests).
 * Please note that the endpoint accepts up to 500 messages per API call, and up to 50 MB payload size, including attachments.
 */
try {
    // Choose either Transactional API or Bulk API
    // For Transactional API
    $mailtrap = MailtrapClient::initSendingEmails(
        apiKey: getenv('MAILTRAP_API_KEY'), // Your API token from https://mailtrap.io/api-tokens
    );

    // OR for Bulk API (uncomment the line below and comment out the transactional initialization)
    // $mailtrap = MailtrapClient::initSendingEmails(
    //    apiKey: getenv('MAILTRAP_API_KEY'), // Your API token from https://mailtrap.io/api-tokens
    //    isBulk: true // Enable bulk sending
    //);

    $baseEmail = (new MailtrapEmail())
        ->from(new Address('example@YOUR-DOMAIN-HERE.com', 'Mailtrap Test')) // Use your domain installed in Mailtrap
        ->subject('Batch Email Subject')
        ->text('Batch email text')
        ->html('<p>Batch email text</p>');

    $recipientEmails = [
        (new MailtrapEmail())->to(new Address('recipient1@example.com', 'Recipient 1')),
        (new MailtrapEmail())->to(new Address('recipient2@example.com', 'Recipient 2')),
    ];

    $response = $mailtrap->batchSend($recipientEmails, $baseEmail);

    var_dump(ResponseHelper::toArray($response)); // Output response body as array
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}


/**
 * Email Batch Sending WITH TEMPLATE (Transactional OR Bulk)
 *
 * WARNING! If a template is provided, then subject, text, html, category and other params are forbidden.
 *
 * UUID of email template. Subject, text and html will be generated from template using optional template_variables.
 * Optional template variables that will be used to generate actual subject, text and html from email template
 */
try {
    // Choose either Transactional API or Bulk API
    // For Transactional API
    $mailtrap = MailtrapClient::initSendingEmails(
        apiKey: getenv('MAILTRAP_API_KEY'), // Your API token from https://mailtrap.io/api-tokens
    );

    // OR for Bulk API (uncomment the line below and comment out the transactional initialization)
    // $mailtrap = MailtrapClient::initSendingEmails(
    //    apiKey: getenv('MAILTRAP_API_KEY'), // Your API token from https://mailtrap.io/api-tokens
    //    isBulk: true // Enable bulk sending
    //);

    $baseEmail = (new MailtrapEmail())
        ->from(new Address('example@YOUR-DOMAIN-HERE.com', 'Mailtrap Test')) // Use your domain installed in Mailtrap
        ->templateUuid('bfa432fd-0000-0000-0000-8493da283a69') // Template UUID
        ->templateVariables([
            'user_name' => 'Jon Bush',
            'next_step_link' => 'https://mailtrap.io/',
            'get_started_link' => 'https://mailtrap.io/',
            'company' => [
                'name' => 'Best Company',
                'address' => 'Its Address',
            ],
            'products' => [
                [
                    'name' => 'Product 1',
                    'price' => 100,
                ],
                [
                    'name' => 'Product 2',
                    'price' => 200,
                ],
            ],
        ]);

    $recipientEmails = [
        (new MailtrapEmail())
            ->to(new Address('recipient1@example.com', 'Recipient 1'))
            // Optional: Override template variables for this recipient
            ->templateVariables([
                'user_name' => 'Custom User 1',
            ]),
        (new MailtrapEmail())
            ->to(new Address('recipient2@example.com', 'Recipient 2')),
    ];

    $response = $mailtrap->batchSend($recipientEmails, $baseEmail);

    var_dump(ResponseHelper::toArray($response)); // Output response body as array
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}
