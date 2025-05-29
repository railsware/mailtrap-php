<?php

declare(strict_types=1);

namespace Mailtrap\DTO\Request\Contact;

/**
 * Class CreateContact
 */
final class CreateContact implements ContactInterface
{
    public function __construct(
        private string $email,
        private array $fields = [],
        private array $listIds = []
    ) {
    }

    public static function init(string $email, array $fields = [], array $listIds = []): self
    {
        return new self($email, $fields, $listIds);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function getListIds(): array
    {
        return $this->listIds;
    }

    public function toArray(): array
    {
        return [
            'email' => $this->getEmail(),
            'fields' => $this->getFields(),
            'list_ids' => $this->getListIds(),
        ];
    }
}
