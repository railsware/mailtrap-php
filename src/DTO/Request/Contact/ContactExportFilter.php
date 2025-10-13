<?php

declare(strict_types=1);

namespace Mailtrap\DTO\Request\Contact;

use Mailtrap\DTO\Request\RequestInterface;

/**
 * Represents a single filter for Contact Export.
 */
final class ContactExportFilter implements RequestInterface
{
    public function __construct(
        private string $name,
        private string $operator,
        private mixed $value
    ) {
    }

    public static function init(string $name, string $operator, mixed $value): self
    {
        return new self($name, $operator, $value);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'operator' => $this->getOperator(),
            'value' => $this->getValue(),
        ];
    }
}

