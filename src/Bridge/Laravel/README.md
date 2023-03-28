Mailtrap bridge for Laravel framework [API]
===============

Provides mailtrap.io integration for Laravel framework.

## Installation
If you just want to get started quickly, you should run one of the following command (depends on which HTTP client you want to use):
```bash
# With symfony http client (recommend)
composer require railsware/mailtrap-php symfony/http-client nyholm/psr7

# Or with guzzle http client
composer require railsware/mailtrap-php guzzlehttp/guzzle php-http/guzzle7-adapter
```

## Usage

Add mailtrap transport into your `config/mail.php` file.
```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    */
    'mailers' => [
    
            // start mailtrap transport
            'mailtrap' => [
                'transport' => 'mailtrap'
            ],
            // end mailtrap transport
    
    ]
]
```

Set `mailtrap` transport as a default Laravel mailer and change default mailtrap config variables inside your `.env` file.


### Sending
You need to set the API key to the `MAILTRAP_API_KEY` variable.
```bash
MAIL_MAILER="mailtrap"

MAILTRAP_HOST="send.api.mailtrap.io"
MAILTRAP_API_KEY="YOUR_API_KEY_HERE"
```
### Sandbox
You need to set the API key to the `MAILTRAP_API_KEY` variable and set your inboxId to the `MAILTRAP_INBOX_ID`.
```bash
MAIL_MAILER="mailtrap"

MAILTRAP_HOST="sandbox.api.mailtrap.io"
MAILTRAP_API_KEY="YOUR_API_KEY_HERE"
MAILTRAP_INBOX_ID=1000001
```

Before starting, run the clear configuration cache command to set up new variables.
```bash
php artisan config:clear
```

### Send you first email
Firstly you need to generate `Mailable` class. More info [here](https://laravel.com/docs/10.x/mail#generating-mailables)
```bash
php artisan make:mail WelcomeMail
```
After that you can configure you Email as you which. Below will be example.
```php
# app/Mail/WelcomeMail.php
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;
use Mailtrap\EmailHeader\CategoryHeader;
use Mailtrap\EmailHeader\CustomVariableHeader;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Header\UnstructuredHeader;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    private string $name;

    /**
     * Create a new message instance.
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('jeffrey@example.com', 'Jeffrey Way'),
            replyTo: [
                      new Address('taylor@example.com', 'Taylor Otwell'),
                  ],
            subject: 'Welcome Mail',
            using: [
                      function (Email $email) {
                          // Headers
                          $email->getHeaders()
                              ->addTextHeader('X-Message-Source', 'example.com')
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
                      },
                  ]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.welcome-email',
            with: ['name' => $this->name],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath('https://mailtrap.io/wp-content/uploads/2021/04/mailtrap-new-logo.svg')
                ->as('logo.svg')
                ->withMime('image/svg+xml'),
        ];
    }

    /**
     * Get the message headers.
     */
    public function headers(): Headers
    {
        return new Headers(
            'custom-message-id@example.com',
            ['previous-message@example.com'],
            [
                'X-Custom-Header' => 'Custom Value',
            ],
        );
    }
}
```
Email template
```php
# resources/views/mail/welcome-email.blade.php

Hey, {{$name}} and welcome here 😉

<br>
Funny Coder
```

Add CLI router
```php
# app/routes/console.php
<?php

use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
*/

Artisan::command('send-welcome-mail', function () {
    Mail::to('testreceiver@gmail.com')->send(new WelcomeMail("Jon"));
    // Also, you can use specific mailer if your default mailer is not "mailtrap" but you want to use it for welcome mails
    // Mail::mailer('mailtrap')->to('testreceiver@gmail.com')->send(new WelcomeMail("Jon"));
})->purpose('Send welcome mail');
```

After that just call this CLI command, and it will send your email
```bash
php artisan send-welcome-mail
```

## Compatibility
The Mailtrap library is fully compatible with **Laravel 9.x and above**.
> Laravel did one of the largest changes in Laravel 9.x is the transition from SwiftMailer, which is no longer maintained as of December 2021, to Symfony Mailer.
>
> You can find more information from these two URLs:
>
> https://laravel.com/docs/9.x/releases#symfony-mailer and 
> https://laravel.com/docs/9.x/upgrade#symfony-mailer  


But you can still use this library as a standalone. More example how to use, you can find [here](../../../examples)


## Resources

* [Laravel mail documentation](https://laravel.com/docs/master/mail)
