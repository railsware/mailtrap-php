<?php

declare(strict_types=1);

namespace Mailtrap\DTO\Request\Contact;

use Mailtrap\DTO\Request\RequestInterface;

/**
 * Class CreateContactEvent
 */
final class CreateContactEvent implements RequestInterface
{
    public function __construct(
        private string $name,
        private array $params = []
    ) {
    }

    public static function init(string $name, array $params = []): self
    {
        return new self($name, $params);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'params' => $this->getParams(),
        ];
    }
}
