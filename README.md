Official MailTrap PHP client
===============
![GitHub Actions](https://github.com/railsware/mailtrap-php/actions/workflows/ci.yml/badge.svg) 
![GitHub Actions](https://github.com/railsware/mailtrap-php/actions/workflows/ci-phpunit.yml/badge.svg)
![GitHub Actions](https://github.com/railsware/mailtrap-php/actions/workflows/ci-psalm.yaml/badge.svg)

[![PHP version support](https://img.shields.io/packagist/dependency-v/railsware/mailtrap-php/php?style=flat)](https://packagist.org/packages/railsware/mailtrap-php)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/railsware/mailtrap-php.svg?style=flat)](https://packagist.org/packages/railsware/mailtrap-php)
[![Total Downloads](https://img.shields.io/packagist/dt/railsware/mailtrap-php.svg?style=flat)](https://packagist.org/packages/railsware/mailtrap-php)


## Installation
You can install the package via [composer](http://getcomposer.org/)

The MailTrap API Client is not hard coupled to Guzzle, React, Zend, Symfony HTTP or any other library that sends
HTTP messages. Instead, it uses the [PSR-18](https://www.php-fig.org/psr/psr-18/) client abstraction.

This will give you the flexibility to choose what [HTTP client](https://docs.php-http.org/en/latest/clients.html) you want to use.

If you just want to get started quickly you should run the following command:

```bash
composer require railsware/mailtrap-php symfony/http-client nyholm/psr7
```

## Usage
You should use Composer autoloader in your application to automatically load your dependencies. 
All the examples below assume you've already included this in your file:

Here's how to send a message using the SDK:

```php
<?php

use Mailtrap\Config;
use Mailtrap\EmailHeader\CategoryHeader;
use Mailtrap\EmailHeader\CustomVariableHeader;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailTrapSendingClient;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Header\UnstructuredHeader;

require __DIR__ . 'vendor/autoload.php';

$email = (new Email())
    ->from(new Address('example@you-domain-here.com', 'MailTrap Test'))
    ->to(new Address('email@example.com', 'Jon'))
    ->cc('mailtrapqa@example.com')
    ->addCc('staging@example.com')
    ->bcc('mailtrapdev@example.com')
    ->subject('Best practices of building HTML emails')
    ->text('Hey! Learn the best practices of building HTML emails and play with ready-to-go templates. MailTrap’s Guide on How to Build HTML Email is live on our blog')
    ->html(
        '<html>
        <body>
        <p><br>Hey</br>
        Learn the best practices of building HTML emails and play with ready-to-go templates.</p>
        <p><a href="https://mailtrap.io/blog/build-html-email/">MailTrap’s Guide on How to Build HTML Email</a> is live on our blog</p>
        <img src="cid:logo">
        </body>
    </html>'
    )
    ->embed(fopen('https://mailtrap.io/wp-content/uploads/2021/04/mailtrap-new-logo.svg', 'r'), 'logo', 'image/svg+xml')
    ->attachFromPath('README.md')
    ;
    
    // Headers
    $email->getHeaders()
    ->addTextHeader('X-Message-Source', 'domain.com')
    ->add(new UnstructuredHeader('X-Mailer', 'MailTrap PHP Client')) // the same as addTextHeader
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
    
try {
    $mailTrap = new MailTrapSendingClient(
        new Config('23...YOUR_API_KEY_HERE...4c') // your API toke from here https://mailtrap.io/api-tokens
    );
   
    $response = $mailTrap->emails()->send($email); // Email sending API (real)
    
    var_dump(ResponseHelper::toArray($response)); // body (array)
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

// OR send to the MailTrap SANDBOX

try {
    $mailTrap = new MailTrapSandboxClient(
        new Config('23...YOUR_API_KEY_HERE...4c') // your API toke from here https://mailtrap.io/api-tokens
    );
   
    $response = $mailTrap->emails()->send($email, 1000001); // Required second param -> inbox_id

    var_dump(ResponseHelper::toArray($response)); // body (array)
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
```

### All usage examples

You can find more examples at [examples](examples) folder.


## Framework integration

If you are using a framework you might consider these composer packages to make the framework integration easier.

* [Symfony](src/integration/symfony) (coming soon)
* [Laravel](src/integration/symfony) (coming soon)

## Contributing

Bug reports and pull requests are welcome on [GitHub](https://github.com/railsware/mailtrap-php). This project is intended to be a safe, welcoming space for collaboration, and contributors are expected to adhere to the [code of conduct](CODE_OF_CONDUCT.md).

## License

The package is available as open source under the terms of the [MIT License](https://opensource.org/licenses/MIT).

## Code of Conduct

Everyone interacting in the MailTrap project's codebases, issue trackers, chat rooms and mailing lists is expected to follow the [code of conduct](CODE_OF_CONDUCT.md).
