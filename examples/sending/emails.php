<?php

use Mailtrap\Config;
use Mailtrap\EmailHeader\CategoryHeader;
use Mailtrap\EmailHeader\CustomVariableHeader;
use Mailtrap\EmailHeader\Template\TemplateUuidHeader;
use Mailtrap\EmailHeader\Template\TemplateVariableHeader;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;
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
    // your API token from here https://mailtrap.io/api-tokens
    $apiKey = getenv('MAILTRAP_API_KEY');
    $mailtrap = new MailtrapClient(new Config($apiKey));

    $email = (new Email())
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

    $response = $mailtrap->sending()->emails()->send($email);

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
    // your API token from here https://mailtrap.io/api-tokens
    $apiKey = getenv('MAILTRAP_API_KEY');
    $mailtrap = new MailtrapClient(new Config($apiKey));

    $email = (new Email())
        ->from(new Address('example@YOUR-DOMAIN-HERE.com', 'Mailtrap Test')) // <--- you should use your domain here that you installed in the mailtrap.io admin area (otherwise you will get 401)
        ->replyTo(new Address('reply@YOUR-DOMAIN-HERE.com'))
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

    $response = $mailtrap->sending()->emails()->send($email);

    var_dump(ResponseHelper::toArray($response)); // body (array)
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
