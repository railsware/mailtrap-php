Mailtrap bridge for Symfony framework [API]
===============

Provides mailtrap.io integration for Symfony Mailer.

## Installation
If you just want to get started quickly, you should run the following command:
```bash
composer require railsware/mailtrap-php symfony/http-client nyholm/psr7
```

## Usage

Add MailtrapTransport into your `config/services.yaml` file
```yaml
...
    # add more service definitions when explicit configuration is needed
    # please note that last definitions always *replace* previous ones

    Mailtrap\Bridge\Transport\MailtrapTransportFactory:
        tags:
            - { name: 'mailer.transport_factory' }
```

### Sending
Add or change MAILER_DSN variable inside your `.env` file. Also, you need to change the `YOUR_API_KEY_HERE` placeholder.
```bash
MAILER_DSN=mailtrap+api://YOUR_API_KEY_HERE@default
# or
MAILER_DSN=mailtrap+api://YOUR_API_KEY_HERE@send.api.mailtrap.io
```

### Bulk Sending
Add or change MAILER_DSN variable inside your `.env` file. Also, you need to change the `YOUR_API_KEY_HERE` placeholder.

More info about bulk sending -> https://help.mailtrap.io/article/113-sending-streams
```bash
MAILER_DSN=mailtrap+api://YOUR_API_KEY_HERE@bulk.api.mailtrap.io
```

### Sandbox
Add or change MAILER_DSN variable inside your `.env` file. Also, you need to change the `YOUR_API_KEY_HERE` placeholder and put correct `inboxId`.

More info sandbox -> https://help.mailtrap.io/article/109-getting-started-with-mailtrap-email-testing
```bash
MAILER_DSN=mailtrap+api://YOUR_API_KEY_HERE@sandbox.api.mailtrap.io?inboxId=1000001
```

### Send you first email

#### CLI command (the mailer:test command was introduced only in Symfony 6.2)
```bash
php bin/console mailer:test to@example.com
```

#### Controller (base example)

```php
<?php

use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Mailtrap\Helper\ResponseHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;


final class SomeController extends AbstractController
{
    private TransportInterface $transport;

    public function __construct(TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    /**
     * @Route(name="send-email", path="/send-email", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function sendEmail(): JsonResponse
    {
        $message = (new MailtrapEmail())
            ->from('from@xample.com')
            ->to('to@xample.com')
            ->cc('cc@example.com')
            ->bcc('bcc@example.com')
            ->replyTo('fabien@example.com')
            ->priority(Email::PRIORITY_HIGH)
            ->subject('Test email')
            ->text('text')
            ->category('category')
            ->customVariables([
                'var1' => 'value1',
                'var2' => 'value2'
            ])
        ;

        $response = $this->transport->send($message);

        return JsonResponse::create(['messageId' => $response->getMessageId()]);
    }
    
    /**
     * WARNING! To send using Mailtrap Email Template, you should use the native library and its methods,
     * as mail transport validation does not allow you to send emails without ‘html’ or ‘text’
     *
     * @Route(name="send-template-email", path="/send-template-email", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function sendTemplateEmail(): JsonResponse
    {
        $email = (new MailtrapEmail())
            ->from(new Address('example@YOUR-DOMAIN-HERE.com', 'Mailtrap Test')) // <--- you should use your domain here that you installed in the mailtrap.io admin area (otherwise you will get 401)
            ->replyTo(new Address('reply@YOUR-DOMAIN-HERE.com'))
            ->to(new Address('example@gmail.com', 'Jon'))
            // when using a template, you should not set a subject, text, HTML, category
            // otherwise there will be a validation error from the API side
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

        $response = MailtrapClient::initSendingEmails(
            apiKey: env('MAILTRAP_API_KEY') // your API token from here https://mailtrap.io/api-tokens
        )->send($email);

        return JsonResponse::create(ResponseHelper::toArray($response));
    }
}
```

## Resources

* [Symfony mailer documentation](https://symfony.com/doc/current/mailer.html)
