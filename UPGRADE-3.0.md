UPGRADE FROM 2.x to 3.0
=======================

Version 3.0 introduces **breaking changes** for `Laravel` and `Symfony` frameworks. 
This guide helps you upgrade your application accordingly.


### Laravel Integration

1. Change mailtrap transport inside your `config/mail.php` file.

__Before__: 
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
];
```
  __After__:
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
            'mailtrap-sdk' => [
                'transport' => 'mailtrap-sdk'
            ],
            // end mailtrap transport
    
    ]
];
```

2. Set `mailtrap-sdk` transport as a default instead `mailtrap` inside your `.env` file.

```bash
MAIL_MAILER="mailtrap-sdk"
```

3. Rename mailtrap config file and variables

__Before__:
```php
# /config/mailtrap.php

<?php

return [
    'mailtrap' => [
        'host' => env('MAILTRAP_HOST', 'send.api.mailtrap.io'),
        'apiKey' => env('MAILTRAP_API_KEY'),
        'inboxId' => env('MAILTRAP_INBOX_ID'),
    ],
];
```
__After__:
```php
# /config/mailtrap-sdk.php

<?php

return [
    'mailtrap-sdk' => [
        'host' => env('MAILTRAP_HOST', 'send.api.mailtrap.io'),
        'apiKey' => env('MAILTRAP_API_KEY'),
        'inboxId' => env('MAILTRAP_INBOX_ID'),
    ],
];
```

### Symfony Integration
1. Change configuration inside your `config/services.yaml` file

__Before__:
```yaml
...
    # add more service definitions when explicit configuration is needed
    # please note that last definitions always *replace* previous ones

    Mailtrap\Bridge\Transport\MailtrapTransportFactory:
        tags:
            - { name: 'mailer.transport_factory' }
```
__After__:
```yaml
...
    # add more service definitions when explicit configuration is needed
    # please note that last definitions always *replace* previous ones

    Mailtrap\Bridge\Transport\MailtrapSdkTransportFactory:
        tags:
            - { name: 'mailer.transport_factory' }
```

2. Change MAILER_DSN variable inside your `.env`

__Before__:
```bash
MAILER_DSN=mailtrap://YOUR_API_KEY_HERE@default
# or
MAILER_DSN=mailtrap+api://YOUR_API_KEY_HERE@send.api.mailtrap.io
```
__After__:
```bash
MAILER_DSN=mailtrap+sdk://YOUR_API_KEY_HERE@default
# or
MAILER_DSN=mailtrap+sdk://YOUR_API_KEY_HERE@send.api.mailtrap.io
```
