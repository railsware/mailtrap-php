<?php

use Mailtrap\Config;
use Mailtrap\Header\CategoryHeader;
use Mailtrap\Header\CustomVariableHeader;
use Mailtrap\Header\Template\TemplateUuidHeader;
use Mailtrap\Header\Template\TemplateVariableHeader;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Header\UnstructuredHeader;

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
    $email = (new Email())
        ->from(new Address('mailtrap@example.com', 'Mailtrap Test'))
        //        ->from(new Address('mailtrap@example.com', 'MailTrap'))
        ->to(new Address('email@example.com', 'Jon'))
        ->to(new Address('newuser@example.com', 'testName'))
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

    $response = $mailTrap->emails()->send($email);

    // print all possible information from the response
    var_dump($response->getHeaders()); //headers (array)
    var_dump($response->getStatusCode()); //status code (int)
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
    $email = (new Email())
        ->from(new Address('mailtrap@example.com', 'Mailtrap Test'))
        ->to(new Address('example@gmail.com', 'Jon'))
    ;

    // Template UUID and Variables
    $email->getHeaders()
        ->add(new TemplateUuidHeader('bfa432fd-0000-0000-0000-8493da283a69'))
        ->add(new TemplateVariableHeader('user_name', 'Jon Bush'))
        ->add(new TemplateVariableHeader('next_step_link', 'https://mailtrap.io/'))
        ->add(new TemplateVariableHeader('get_started_link', 'https://mailtrap.io/'))
        ->add(new TemplateVariableHeader('onboarding_video_link', 'some_video_link'))
    ;

    $response = $mailTrap->emails()->send($email);

    // print all possible information from the response
    var_dump($response->getHeaders()); //headers (array)
    var_dump($response->getStatusCode()); //status code (int)
    var_dump(ResponseHelper::toArray($response)); // body (array)
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


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
    $email = (new Email())
        ->from(new Address('mailtrap@example.com', 'Mailtrap Test'))
        //        ->from(new Address('mailtrap@example.com', 'MailTrap'))
        ->to(new Address('email@example.com', 'Jon'))
        ->to(new Address('newuser@example.com', 'testName'))
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
    $response = $mailTrap->emails()->sendToSandbox($email, 1000001);

    // print all possible information from the response
    var_dump($response->getHeaders()); //headers (array)
    var_dump($response->getStatusCode()); //status code (int)
    var_dump(ResponseHelper::toArray($response)); // body (array)
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}