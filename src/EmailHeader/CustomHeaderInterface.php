<?php

declare(strict_types=1);

namespace Mailtrap\EmailHeader;

interface CustomHeaderInterface
{
    public function getNameWithoutPrefix(): string;
}
