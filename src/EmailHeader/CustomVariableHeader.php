<?php

declare(strict_types=1);

namespace Mailtrap\EmailHeader;

use Symfony\Component\Mime\Header\AbstractHeader;

/**
 * MIME Header for Mailtrap custom_variables
 *
 * Class CustomVariableHeader
 */
class CustomVariableHeader extends AbstractHeader implements CustomHeaderInterface
{
    public const VAR_NAME = 'custom_variables';
    public const NAME_PREFIX = 'custom_variables_prefix_';

    private string $value;

    public function __construct(string $name, string $value)
    {
        // add prefix to avoid conflicts with reserved header names Symfony\Component\Mime\Header\Headers::HEADER_CLASS_MAP
        parent::__construct(self::NAME_PREFIX . $name);

        $this->setValue($value);
    }

    /**
     * @param string $body
     */
    public function setBody($body): void
    {
        $this->setValue($body);
    }

    /**
     * @psalm-suppress MethodSignatureMismatch
     */
    public function getBody(): string
    {
        return $this->getValue();
    }

    /**
     * Get the (unencoded) value of this header.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Set the (unencoded) value of this header.
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getNameWithoutPrefix(): string
    {
        return substr($this->getName(), strlen(self::NAME_PREFIX));
    }

    /**
     * Get the value of this header prepared for rendering.
     */
    public function getBodyAsString(): string
    {
        return $this->encodeWords($this, $this->value);
    }
}
