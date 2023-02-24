<?php

declare(strict_types=1);

namespace Mailtrap;

use Mailtrap\Api\Account;
use Mailtrap\Exception\BadMethodCallException;
use Mailtrap\Exception\InvalidArgumentException;

/**
 * @method  Api\Account  accounts
 *
 * Class MailtrapClient
 */
class MailtrapClient
{
    private const API_MAPPING = [
        'accounts' => Account::class,
    ];

    private ConfigInterface $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function __call($name, $arguments)
    {
        try {
            return $this->initByName($name);
        } catch (InvalidArgumentException $e) {
            throw new BadMethodCallException(sprintf('Undefined method called: "%s"', $name));
        }
    }

    private function initByName(string $name)
    {
        $className = self::API_MAPPING[$name];
        if (empty($className)) {
            throw new InvalidArgumentException(sprintf('Undefined api instance called: "%s"', $name));
        }

        return new $className($this->config);
    }
}