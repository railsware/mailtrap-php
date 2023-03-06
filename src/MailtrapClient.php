<?php

declare(strict_types=1);

namespace Mailtrap;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Api\Account;
use Mailtrap\Api\Emails;
use Mailtrap\Exception\BadMethodCallException;
use Mailtrap\Exception\InvalidArgumentException;

/**
 * @method  Api\Account  accounts
 * @method  Api\Emails   emails
 *
 * Class MailtrapClient
 */
class MailtrapClient
{
    private const API_MAPPING = [
        'accounts' => Account::class,
        'emails' => Emails::class,
    ];
    private ConfigInterface $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function __call(string $name, array $arguments)
    {
        try {
            return $this->initByName($name);
        } catch (InvalidArgumentException $e) {
            throw new BadMethodCallException(sprintf('Undefined method called: "%s"', $name));
        }
    }

    private function initByName(string $name): AbstractApi
    {
        $className = !empty(self::API_MAPPING[$name]) ? self::API_MAPPING[$name] : null;
        if (null === $className) {
            throw new InvalidArgumentException(sprintf('Undefined api instance called: "%s"', $name));
        }

        return new $className($this->config);
    }
}