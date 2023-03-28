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
        $this->mergeConfigFrom(__DIR__ . '/config/mailtrap.php', 'services');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // https://laravel.com/docs/9.x/upgrade#symfony-mailer
        if ((int) $this->app->version() >= 9) {
            Mail::extend('mailtrap', function () {
                return (new MailTrapTransportFactory)->create(
                    new Dsn(
                        'mailtrap+api',
                        config('services.mailtrap.host'),
                        config('services.mailtrap.apiKey'),
                        null,
                        null,
                        config('services.mailtrap', [])
                    )
                );
            });
        }
    }
}
