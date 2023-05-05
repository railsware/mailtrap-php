<?php

namespace Mailtrap\DTO\Request;

interface RequestInterface
{
    /**
     * Get object as array
     *
     * @return array
     */
    public function toArray(): array;
}
