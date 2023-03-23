<?php

declare(strict_types=1);

namespace Mailtrap\Bridge\Laravel;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Mailtrap\Bridge\Transport\MailtrapTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

/**
 * Class MailtrapApiProvider
 */
class MailtrapApiProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Mail::extend('mailtrap', function ($config) {
            // TODO
        });
    }
}
