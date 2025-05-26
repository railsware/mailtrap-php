<?php

declare(strict_types=1);

namespace Mailtrap\DTO\Request\Contact;

/**
 * Class UpdateContact
 */
final class UpdateContact implements ContactInterface
{
    public function __construct(
        private string $email,
        private array $fields = [],
        private array $listIdsIncluded = [],
        private array $listIdsExcluded = [],
        private ?bool $unsubscribed = null
    ) {
    }

    public static function init(
        string $email,
        array $fields = [],
        array $listIdsIncluded = [],
        array $listIdsExcluded = [],
        ?bool $unsubscribed = null
    ): self {
        return new self($email, $fields, $listIdsIncluded, $listIdsExcluded, $unsubscribed);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function getListIdsIncluded(): array
    {
        return $this->listIdsIncluded;
    }

    public function getListIdsExcluded(): array
    {
        return $this->listIdsExcluded;
    }

    public function getUnsubscribed(): ?bool
    {
        return $this->unsubscribed;
    }

    public function toArray(): array
    {
        return array_filter(
            [
                'email'                => $this->getEmail(),
                'fields'               => $this->getFields(),
                'list_ids_included'    => $this->getListIdsIncluded(),
                'list_ids_excluded'    => $this->getListIdsExcluded(),
                'unsubscribed'         => $this->getUnsubscribed(),
            ],
            fn($value) => $value !== null
        );
    }
}
