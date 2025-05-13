<?php

declare(strict_types=1);

namespace Mailtrap\Bridge\Laravel;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Mailtrap\Bridge\Transport\MailtrapSdkTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

/**
 * Class MailtrapSdkProvider
 */
class MailtrapSdkProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/mailtrap-sdk.php', 'services');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // https://laravel.com/docs/9.x/upgrade#symfony-mailer
        if ((int) $this->app->version() >= 9) {
            Mail::extend('mailtrap-sdk', function () {
                return (new MailtrapSdkTransportFactory)->create(
                    new Dsn(
                        'mailtrap+sdk',
                        config('services.mailtrap-sdk.host'),
                        config('services.mailtrap-sdk.apiKey'),
                        null,
                        null,
                        config('services.mailtrap-sdk', [])
                    )
                );
            });
        }
    }
}
