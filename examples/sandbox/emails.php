<?php

use Mailtrap\Config;
use Mailtrap\EmailHeader\CategoryHeader;
use Mailtrap\EmailHeader\CustomVariableHeader;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Header\UnstructuredHeader;

require __DIR__ . '/../vendor/autoload.php';


/**********************************************************************************************************************
 ******************************************* EMAIL TESTING ************************************************************
 **********************************************************************************************************************
 */

/**
 * Email Testing API
 *
 * POST https://sandbox.api.mailtrap.io/api/send/{inbox_id}
 */
try {
    // your API token from here https://mailtrap.io/api-tokens
    $apiKey = getenv('MAILTRAP_API_KEY');
    $mailtrap = new MailtrapClient(new Config($apiKey));

    $email = (new Email())
        ->from(new Address('mailtrap@example.com', 'Mailtrap Test'))
        ->replyTo(new Address('reply@example.com'))
        ->to(new Address('email@example.com', 'Jon'))
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
    ;

    // Headers
    $email->getHeaders()
        ->addTextHeader('X-Message-Source', '1alf.com')
        ->add(new UnstructuredHeader('X-Mailer', 'Mailtrap PHP Client'))
    ;

    // Custom Variables
    $email->getHeaders()
        ->add(new CustomVariableHeader('user_id', '45982'))
        ->add(new CustomVariableHeader('batch_id', 'PSJ-12'))
    ;

    // Category (should be only one)
    $email->getHeaders()
        ->add(new CategoryHeader('Integration Test'))
    ;

    // Required param -> inbox_id
    $response = $mailtrap->sandbox()->emails()->send($email, 1000001); // <--- you should use your inbox_id here (otherwise you will get 401)

    // print all possible information from the response
    var_dump($response->getHeaders()); //headers (array)
    var_dump($response->getStatusCode()); //status code (int)
    var_dump(ResponseHelper::toArray($response)); // body (array)
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
