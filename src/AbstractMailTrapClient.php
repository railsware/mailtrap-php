<?php

declare(strict_types=1);

namespace Mailtrap;

use Mailtrap\Api\AbstractApi;
use Mailtrap\Exception\BadMethodCallException;
use Mailtrap\Exception\InvalidArgumentException;

/**
 * Class AbstractMailTrapClient
 */
abstract class AbstractMailTrapClient implements MailTrapClientInterface
{
    protected ConfigInterface $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function __call(string $name, array $arguments)
    {
        try {
            return $this->initByName($name);
        } catch (InvalidArgumentException $e) {
            throw new BadMethodCallException(sprintf('%s -> undefined method called: "%s"', static::class, $name));
        }
    }

    private function initByName(string $name): AbstractApi
    {
        $className = $this->getApiClassByName($name);
        if (null === $className) {
            throw new InvalidArgumentException(sprintf('%s -> undefined api instance called: "%s"', static::class, $name));
        }

        /** @psalm-suppress LessSpecificReturnStatement */
        return new $className($this->config);
    }
}
