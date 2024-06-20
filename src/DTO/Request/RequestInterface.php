<?php

declare(strict_types=1);

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
