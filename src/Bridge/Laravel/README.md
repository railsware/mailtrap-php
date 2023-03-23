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

Before starting, run the clear configuration cache command to apply new variables.
```bash
php artisan config:clear
```

### Send you first email
TODO

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
