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
### Sandbox
Add or change MAILER_DSN variable inside your `.env` file. Also, you need to change the `YOUR_API_KEY_HERE` placeholder and put correct `inboxId`.
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

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route(name="send-test-email", path="/test", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function sendTestEmail(): JsonResponse
    {
        $message = (new Email())
            ->to('to@xample.com')
            ->from('from@xample.com')
            ->subject('Test email')
            ->text('text')
        ;

        $response = $this->transport->send($message);

        return JsonResponse::create(['messageId' => $response->getMessageId()]);
    }
}
```

## Resources

* [Symfony mailer documentation](https://symfony.com/doc/current/mailer.html)
