<?php

namespace Mailtrap\DTO\Request\Contact;

use Mailtrap\DTO\Request\RequestInterface;

interface ContactInterface extends RequestInterface
{
    /**
     * The email of the contact.
     *
     * @return string
     */
    public function getEmail(): string;

    /**
     * The fields of the contact.
     *
     * @return array
     */
    public function getFields(): array;
}
