<?php

namespace Mailtrap\DTO\Request;

/**
 * Class UpdateInbox
 */
class Inbox implements RequestInterface
{
    private ?string $name;
    private ?string $emailUsername;

    public function __construct(string $name = null, string $emailUsername = null)
    {
        $this->name = $name;
        $this->emailUsername = $emailUsername;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getEmailUsername(): ?string
    {
        return $this->emailUsername;
    }

    public function toArray(): array
    {
        $array = [];

        if (!empty($this->getName())) {
            $array['name'] = $this->getName();
        }

        if (!empty($this->getEmailUsername())) {
            $array['email_username'] = $this->getEmailUsername();
        }

        return $array;
    }
}
